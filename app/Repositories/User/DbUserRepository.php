<?php //-->
namespace Journal\Repositories\User;

use Journal\User;
use Journal\Repositories\User\UserRepositoryInterface;
use Hash;
use Validator;

class DbUserRepository implements UserRepositoryInterface
{
    /**
     * Creates a user
     *
     * @param array $user
     * @return \Journal\User
     */
    public function create($user)
    {
        $user = User::create([
            'role_id'   => $user['role'],
            'name'      => $user['name'],
            'email'     => $user['email'],
            'password'  => Hash::make($user['password']),
            'slug'      => $this->generateSlug($user['name'])
        ]);

        return $user;
    }

    /**
     * Search a user based on the given parameters
     *
     * @param array $parameters
     * @return \Journal\User
     */
    public function search($parameters)
    {

    }

    /**
     * Gets all the active users.
     *
     * @return \Journal\User
     */
    public function all()
    {
        return User::with(['role'])
            ->where('active', '=', 1)
            ->get();
    }

    /**
     * Gets a user based on the saved email.
     *
     * @param $email
     * @return \Journal\User
     */
    public function findByEmail($email)
    {
        return User::where('active', '=', 1)
            ->where('email', '=', $email)
            ->first();
    }

    /**
     * Gets a user based on the given ID.
     *
     * @param $id
     * @return \Journal\User
     */
    public function findById($id)
    {
        return User::where('active', '=', 1)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Finds a user based on the given slug.
     *
     * @param $slug
     * @return \Journal\User
     */
    public function findBySlug($slug)
    {
        return User::where('active', '=', 1)
            ->where('slug', '=', $slug)
            ->first();
    }

    /**
     * Updates the details of the user
     *
     * @param $user
     * @return \Journal\User
     */
    public function updateDetails($user)
    {
        // get the user
        $row = $this->findById($user['id']);

        // update
        $row->name = $user['name'];
        $row->slug = $this->generateSlug($user['slug'], $user['id']);

        // optional fields
        if (isset($user['biography'])) {
            $row->biography = $user['biography'];
        }

        if (isset($user['avatar_url'])) {
            $row->avatar_url = $user['avatar_url'];
        }

        if (isset($user['cover_url'])) {
            $row->cover_url = $user['cover_url'];
        }

        if (isset($user['location'])) {
            $row->location = $user['location'];
        }

        if (isset($user['website'])) {
            $row->website = $user['website'];
        }

        // save
        $row->save();

        return $row;
    }

    /**
     * [updatePassword description]
     * @param  [type] $passwords [description]
     * @return [type]            [description]
     */
    public function updatePassword($passwords)
    {

    }

    /**
     * [setToInactive description]
     * @param [type] $user [description]
     */
    public function setToInactive($user)
    {

    }

    /**
     * [generateSlug description]
     * @param  [type] $string [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function generateSlug($string, $id = null)
    {
        // prepare the string
        $slug = str_slug($string, '-');

        // prepare the query to check for slugs
        $query = User::where('slug', 'LIKE', $slug.'%');

        // check if the ID is set
        if (!is_null($id)) {
            $query->where('id', '!=', $id);
        }

        // execute the query and count the results
        $count = count($query->get());

        return ($count > 0) ? $slug.'-'.$count : $slug;
    }

    /**
     * Validate the user creation and update.
     *
     * @param Request $user
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUser($user)
    {
        // prepare the basic rules for both creating and updating a user
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email'
        ];

        // prepare some custom error messages
        $messages = [
            'name.required'     => 'Name is required.',
            'email.required'    => 'Email is required.',
            'email.email'       => 'Email should be in valid format.',
            'email.unique'      => 'Email already exists.'
        ];

        // check if the given data has an ID on it
        if (isset($user['id'])) {
            // NOTE: I removed the email validation for when the user is being
            // updated because the update of email will be in a seperate page.
            // We are going to unset all email related validations
            unset($rules['email']);
            unset($messages['email.required']);
            unset($messages['email.email']);
            unset($messages['email.unique']);
        }

        // ID is not set
        if (!isset($user['id'])) {
            // we're expecting this to be a create so we will validate the
            // password given if there is given.
            $rules['password'] = 'required|min:6';

            // set the message
            $messages['password.required']  = 'A password is required.';
            $messages['password.min']       = 'Password should be :min+ characters.';
        }

        // validate
        $validator = Validator::make($user, $rules, $messages);
        $validator->passes();

        // return the errors if there are
        return $validator->errors();
    }
}
