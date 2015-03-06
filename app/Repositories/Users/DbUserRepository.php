<?php //-->
namespace Journal\Repositories\Users;

use Journal\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Hash, Auth, Validator;

/**
 * Class DbUserRepository
 * @package Journal\Core\Repositories\Users
 */
class DbUserRepository implements UserRepositoryInterface {
    /**
     * Creates a new user
     *
     * @param  string $email
     * @param  string $password
     * @param  string $name
     * @param  int $role
     * @return User
     */
    public function create($email, $password, $name, $role)
    {
        return User::create([
            'email'         => $email,
            'password'      => Hash::make($password),
            'name'          => $name,
            'role'          => $role,
            'slug'          => $this->validateSlug($name)]);
    }

    /**
     * Returns all active user
     *
     * @return User
     */
    public function all()
    {
        return User::where('active', '=', 1)
            ->orderBy('email', 'ASC')
            ->get();
    }

    /**
     * Fetches a user by its id
     *
     * @param int $id
     * @return User
     */
    public function findById($id)
    {
        return User::where('id', '=', $id)->first();
    }

    /**
     * Fetches a user by its email
     * @param string $email
     * @return User
     */
    public function findByEmail($email)
    {
        return User::where('email', '=', $email)->first();
    }

    /**
     * Updates user details
     *
     * @param  int $id
     * @param  string $email
     * @param  string $name
     * @param  string $biography
     * @param  string $website
     * @param  string $location
     * @param  string $slug
     * @return User
     */
    public function update($id, $email, $name, $biography, $website, $location, $slug)
    {
        // fetch user details
        $user = $this->findById($id);
        // update
        $user->fill([
            'email'         => $email,
            'name'          => $name,
            'biography'     => $biography,
            'website'       => $website,
            'location'      => $location,
            'slug'          => $slug])->save();

        return $user;
    }

    /**
     * Saves a user image either cover or avatar
     *
     * @param $target
     * @param $url
     * @param $id
     * @return User
     */
    public function updateImage($target, $url, $id)
    {
        // get user first
        $user = $this->findById($id);

        // update
        $user->fill([ $target => $url ])->save();

        return $user;
    }

    /**
     * Updates the users password
     *
     * @param $id
     * @param $password
     * @return User
     */
    public function updatePassword($id, $password)
    {
        // get user first
        $user = $this->findById($id);

        // update
        $user->fill([
            'password' => Hash::make($password)])->save();
    }

    /**
     * Sets a user to be inactive
     *
     * @param int $id
     * @return void
     */
    public function setInactive($id)
    {
        // get user
        $user = $this->findById($id);
        // update and set field active to 0
        $user->fill(['active' => 0])->save();
    }

    /**
     * Logs in the user to the platform
     *
     * @param  string $email
     * @param  string $password
     * @param  bool   $apiRequest
     * @return bool
     */
    public function login($email, $password, $apiRequest = false)
    {
        // check if the login request is from 3rd party (mobile app or via api)
        if ($apiRequest) {
            // validate
            if (Auth::validate(['email' => $email, 'password' => $password])) {
                return true;
            }
            return false;
        }
        // normal login authentication
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return true;
        }
        return false;
    }

    /**
     * Logs out the to the platform
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();
    }

    /**
     * Validates user details for creation
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @return \Illuminate\Support\MessageBag
     */
    public function validateCreate($email, $password, $name)
    {
        // prep data
        $data = [
            'email'     => $email,
            'password'  => $password,
            'name'      => $name];

        // prepare the rules
        $rules = [
            'email'     => 'required|unique:users,email',
            'password'  => 'required|min:6',
            'name'      => 'required'];

        // prepare the messages, customized!
        $messages = [
            'email.required'    => 'An email is required',
            'name.required'     => 'A name is required',
            'password.required' => 'A password is required',
            'password.min'      => 'Password should be :min+ characters'];

        // validate!
        $validator = Validator::make($data, $rules, $messages);
        $validator->passes();

        return $validator->errors();
    }

    /**
     * Validates if data is valid for update
     *
     * @param string $email
     * @param string $name
     * @param string $biography
     * @param string $website
     * @param string $location
     * @param string $slug
     * @param int $id
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUpdate($email, $name, $biography, $website, $location, $slug, $id)
    {
        // prep data
        $data = [
            'email' => $email,
            'name'  => $name,
            'slug'  => $slug];

        // prepare the rules
        $rules = [
            'email' => 'required|unique:users,email,'.$id,
            'name'  => 'required',
            'slug'  => 'required|unique:users,slug,'.$id];

        // prepare the messages, customized!
        $messages = [
            'email.required'    => 'An email is required',
            'email.unique'      => 'Email already exists',
            'name.required'     => 'A name is required',
            'slug.required'     => 'A slug is required',
            'slug.unique'       => 'Slug already exists'];

        // check if website exists
        if (!is_null($website)) {
            // add to the data to be validated
            $data['website'] = $website;
            // add a rule
            $rules['website'] = 'url';
            // prepare the message
            $messages['website.url'] = 'Website URL is not valid';
        }

        // validate!
        $validator = Validator::make($data, $rules, $messages);
        $validator->passes();

        return $validator->errors();
    }

    /**
     * Validates if passwords are valid
     *
     * @param int $id
     * @param string $currentPassword
     * @param string $newPassword
     * @param string $confirmNewPasswords
     * @return \Illuminate\Support\MessageBag
     */
    public function validateChangePassword($id, $currentPassword, $newPassword, $confirmNewPasswords)
    {
        // create a new custom validator to check the current password if it's
        // the same
        Validator::extend('current_password', function($attribute, $value, $parameters) use ($id) {
            $user = User::where('id', '=', $id)->first();
            return Hash::check($value, $user->password);
        });

        $data = [
            'current_password'  => $currentPassword,
            'new_password'      => $newPassword,
            'repeat_password'   => $confirmNewPasswords];

        $rules = [
            'current_password'  => 'required|current_password',
            'new_password'      => 'required|min:6',
            'repeat_password'   => 'required|same:new_password'];

        $messages = [
            'current_password.required'         => 'We need your current password',
            'current_password.current_password' => 'This is not your current password',
            'new_password.required'             => 'A new password is required',
            'new_password.min'                  => 'Your new password should :min+ characters',
            'repeat_password.required'          => 'We need again your new password',
            'repeat_password.same'              => 'Passwords should be the same'];

        // validate
        $validator = Validator::make($data, $rules, $messages);
        $validator->passes();

        return $validator->errors();
    }

    /**
     * Checks if slug exists and generates a slugified string
     *
     * @param string $slug
     * @param int|null $id
     * @return bool|string
     */
    public function validateSlug($slug, $id = null)
    {
        // slugify
        $slugified = Str::slug(strtolower($slug));

        // check if id is set
        if (is_null($id)) {
            $slugCount = count(User::where('slug', 'LIKE', $slugified.'%')->get());
            // return the slug
            return ($slugCount > 0) ? "{$slugified}-{$slugCount}" : $slugified;
        }

        // there is an id set, get user
        $user = $this->findById($id);
        // check if slug is the same with the user slug
        if ($user->slug == $slugified) {
            return $user->slug;
        }

        return false;
    }
}
