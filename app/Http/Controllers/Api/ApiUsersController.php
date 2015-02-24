<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Users\UserRepositoryInterface;
use Input;

/**
 * Class ApiUsersController
 * @package Journal\Http\Controllers\Api
 */
class ApiUsersController extends ApiController
{
    /**
     * The user repository implementation
     *
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Creates a new API User Controller
     *
     * @param UserRepositoryInterface $users
     */
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * Returns all active users
     *
     * @return mixed
     */
    public function allUsers()
    {
        return $this->respond([
            'data' => [
                'user' => $this->users->all()]]);
    }

    /**
     * Creates a new user
     *
     * @return mixed
     */
    public function createUser()
    {
        $email      = Input::get('email');
        $password   = Input::get('password');
        $name       = Input::get('name');
        $role       = Input::get('role');

        // validate
        $messages = $this->users->validateCreate($email, $password, $name);

        // check for errors
        if (count($messages) > 0) {
            // return the error messages
            return $this->setStatusCode(400)
                ->respondWithError($messages);
        }

        // check if role is set
        $role = (empty($role)) ? '2' : $role;

        // create user
        $user = $this->users->create($email, $password, $name, $role);

        return $this->respond([
            'data' => [
                'message'   => 'You have successfully added a new user.',
                'user'      => $user->toArray()]]);
    }

    /**
     * Fetches a user using its id
     *
     * @return mixed
     */
    public function get()
    {
        $id = Input::get('id');

        // check if there is an id supplied
        if (empty($id)) {
            // send error message
            return $this->setStatusCode(400)
                ->respondWithError('Please set the user ID.');
        }

        // get user
        $user = $this->users->findById($id);

        return $this->respond([
            'data' => [
                'user' => $user]]);
    }

    /**
     * Update the users account
     *
     * @return mixed
     */
    public function updateAccount()
    {
        $name       = Input::get('name');
        $email      = Input::get('email');
        $biography  = Input::get('biography');
        $slug       = Input::get('slug');
        $website    = Input::get('website');
        $location   = Input::get('location');
        $id         = Input::get('id');

        // validate
        $messages = $this->users->validateUpdate($email, $name, $biography, $website, $location, $slug, $id);

        // check for errors
        if (count($messages) > 0) {
            // return the error messages
            return $this->setStatusCode(400)
                ->respondWithError($messages);
        }

        // update
        $user = $this->users->update($id, $email, $name, $biography, $website, $location, $slug);

        return $this->respond([
            'data' => [
                'message'   => 'You have successfully updated your account',
                'user'      => $user->toArray()]]);
    }
}
