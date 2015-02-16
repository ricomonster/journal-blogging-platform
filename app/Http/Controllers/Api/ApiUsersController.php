<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Users\UserRepositoryInterface;
use Input;

class ApiUsersController extends ApiController
{
    public function allUsers(UserRepositoryInterface $users)
    {
        $users = $users->all();

        return $this->respond(array(
            'data' => array(
                'user' => $users)));
    }

    public function createUser(UserRepositoryInterface $users)
    {
        $email      = Input::get('email');
        $password   = Input::get('password');
        $name       = Input::get('name');
        $role       = Input::get('role');

        // validate
        $messages = $users->validateCreate($email, $password, $name);

        // check for errors
        if (count($messages) > 0) {
            // return the error messages
            return $this->setStatusCode(400)
                ->respondWithError($messages);
        }

        // check if role is set
        $role = (empty($role)) ? '2' : $role;

        // create user
        $user = $users->create($email, $password, $name, $role);

        return $this->respond(array(
            'data' => array(
                'message'   => 'You have successfully added a new user.',
                'user'      => $user->toArray())));
    }

    public function get(UserRepositoryInterface $users)
    {
        $id = Input::get('id');

        // check if there is an id supplied
        if (empty($id)) {
            // send error message
        }

        // get user
        $user = $users->findById($id);

        return $this->respond(array(
            'data' => array('user' => $user)));
    }
}
