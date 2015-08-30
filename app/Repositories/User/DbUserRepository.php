<?php //-->
namespace Journal\Repositories\User;

use Illuminate\Support\Str;
use Journal\User;
use Hash, Validator;

class DbUserRepository implements UserRepositoryInterface
{
    /**
     * @param $name
     * @param $email
     * @param $password
     * @return \Journal\User
     */
    public function create($name, $email, $password)
    {
        // insert data
        $user = User::create([
            'name'      => $name,
            'email'     => $email,
            'slug'      => $this->validateSlug($name),
            'password'  => Hash::make($password)]);

        // get the full data of the user
        return $this->findById($user->id);
    }

    /**
     * @return \Journal\User
     */
    public function all()
    {
        return User::where('active', '=', 1)->get();
    }

    /**
     * @param $id
     * @return \Journal\User
     */
    public function findById($id)
    {
        return User::where('id', '=', $id)->first();
    }

    /**
     * @param $email
     * @return \Journal\User
     */
    public function findByEmail($email)
    {
        return User::where('email', '=', $email)->first();
    }

    /**
     * @param $slug
     * @return \Journal\User
     */
    public function findBySlug($slug)
    {
        return User::where('slug', '=', $slug)->first();
    }

    /**
     * @param $id
     * @param $oldPassword
     * @param $newPassword
     * @return \Journal\User
     */
    public function changePassword($id, $oldPassword, $newPassword)
    {
        // get user
        $user = $this->findById($id);

        // update
        $user->password = Hash::make($newPassword);
        $user->save();

        // return user details
        return $user;
    }

    /**
     * @param $id
     * @param $name
     * @param $email
     * @param $biography
     * @param $location
     * @param $website
     * @param $avatarUrl
     * @param $coverUrl
     * @return \Journal\User
     */
    public function updateDetails($id, $name, $email, $biography, $location, $website, $avatarUrl, $coverUrl)
    {
        // get user
        $user = $this->findById($id);

        // update
        $user->name = $name;
        $user->email = $email;
        $user->biography = $biography;
        $user->avatar_url = $avatarUrl;
        $user->cover_url = $coverUrl;
        $user->location = $location;
        $user->website = $website;
        $user->save();

        // return
        return $user;
    }

    /**
     * @param $id
     * @return void
     */
    public function setToInactive($id)
    {
        // get the user
        $user = $this->findById($id);

        // set it to inactive
        $user->active = 0;
        $user->save();
    }

    /**
     * @param $data
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUserCreate($data)
    {
        return $this->validateUser($data);
    }

    /**
     * @param $data
     * @param $id
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUserUpdate($data, $id)
    {
        return $this->validateUser($data, $id);
    }

    /**
     * @param $id
     * @param $oldPassword
     * @param $newPassword
     * @param $repeatNewPassword
     * @return \Illuminate\Support\MessageBag
     */
    public function validateChangePassword($id, $oldPassword, $newPassword, $repeatNewPassword)
    {

    }

    /**
     * @param $string
     * @param null $id
     * @return mixed|string
     */
    protected function validateSlug($string, $id = null)
    {
        $slug = Str::slug(strtolower($string));

        // check if id is set
        if (is_null($id)) {
            $count = count(User::where('slug', 'LIKE', $slug.'%')->get());
            // return the slug
            return ($count > 0) ? "{$slug}-{$count}" : $slug;
        }

        // there is an ID set, get user
        $user = $this->findById($id);

        // check if slug is the same with the user slug
        if ($user->slug == $slug) {
            return $user->slug;
        }

        return $slug;
    }

    /**
     * @param $data
     * @param $id
     * @return \Illuminate\Support\MessageBag
     */
    protected function validateUser($data, $id = null)
    {
        // prepare the rules
        $rules = [
            'email'     => 'required|unique:users,email',
            'name'      => 'required',
            'website'   => 'url'];

        // prepare the messages
        $messages = [
            'email.required'    => 'An email is required.',
            'email.unique'      => 'Email is already taken.',
            'name.required'     => 'A name is required',
            'website.url'       => 'Website is not a valid URL.'];

        // check if ID is set
        if ($id) {
            // add addition parameter for the email rules
            $rules['email'] .= ',' . $id;
        }

        // check if ID is not set
        if (!$id) {
            // add password rules
            $rules['password'] = 'required|min:6';

            // prepare the message
            $messages['password.required'] = 'A password is required.';
            $messages['password.min'] = 'Password should be :min+ characters.';
        }

        // validate
        $validator = Validator::make($data, $rules, $messages);
        $validator->passes();

        // return errors
        return $validator->errors();
    }
}
