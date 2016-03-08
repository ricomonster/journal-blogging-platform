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
        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make($user->password),
            'slug' => $this->generateSlug($user->name)
        ]);
    }

    /**
     * Search a user based on the given parameters
     *
     * @param array $parameters
     * @return [type] [description]
     */
    public function search($parameters)
    {

    }

    /**
     * [findByEmail description]
     * @param  [type] $email [description]
     * @return [type]        [description]
     */
    public function findByEmail($email)
    {
        return User::where('active', '=', 1)
            ->where('email', '=', $email)
            ->first();
    }

    /**
     * [findById description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findById($id)
    {
        return User::where('active', '=', 1)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * [findBySlug description]
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function findBySlug($slug)
    {
        return User::where('active', '=', 1)
            ->where('slug', '=', $slug)
            ->first();
    }

    /**
     * [updateDetails description]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function updateDetails($user)
    {

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

        return $slug.'-'.$count;
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
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email should be in valid format.',
            'email.unique' => 'Email already exists.'
        ];

        // check if the given data has an ID on it
        if ($user->id) {
            // fix the rules for email unique because we will exclude the email
            // of the current request based on the given ID
            $rules['email'] += ','.$user->id;
        }

        // ID is not set
        if (!$user->id) {
            // we're expecting this to be a create so we will validate the
            // password given if there is given.
            $rules['password'] = 'required|min:6';

            // set the message
            $messages['password.required'] = 'A password is required.';
            $messages['password.min'] = 'Password should be :min+ characters.';
        }

        // validate
        $validator = Validator::make($user, $rules, $messages);
        $validator->passes();

        // return the errors if there are
        return $validator->errors();
    }
}
