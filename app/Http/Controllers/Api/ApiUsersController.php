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
     * Returns all active users
     *
     * @param UserRepositoryInterface $users
     * @return mixed
     */
    public function allUsers(UserRepositoryInterface $users)
    {
        return $this->respond([
            'data' => [
                'user' => $users->all()]]);
    }

    /**
     * Creates a new user
     *
     * @param UserRepositoryInterface $users
     * @return mixed
     */
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

        return $this->respond([
            'data' => [
                'message'   => 'You have successfully added a new user.',
                'user'      => $user->toArray()]]);
    }

    /**
     * Fetches a user using its id
     *
     * @param UserRepositoryInterface $users
     * @return mixed
     */
    public function get(UserRepositoryInterface $users)
    {
        $id = Input::get('id');

        // check if there is an id supplied
        if (empty($id)) {
            // send error message
        }

        // get user
        $user = $users->findById($id);

        return $this->respond([
            'data' => ['user' => $user]]);
    }

    /**
     * Update the users account
     *
     * @param UserRepositoryInterface $usersRepository
     * @return mixed
     */
    public function updateAccount(UserRepositoryInterface $usersRepository)
    {
        $name       = Input::get('name');
        $email      = Input::get('email');
        $biography  = Input::get('biography');
        $slug       = Input::get('slug');
        $website    = Input::get('website');
        $location   = Input::get('location');
        $id         = Input::get('id');

        // validate
        $messages = $usersRepository->validateUpdate($email, $name, $biography, $website, $location, $slug, $id);

        // check for errors
        if (count($messages) > 0) {
            // return the error messages
            return $this->setStatusCode(400)
                ->respondWithError($messages);
        }

        // update
        $user = $usersRepository->update($id, $email, $name, $biography, $website, $location, $slug);

        return $this->respond([
            'data' => [
                'message' => 'You have successfully updated your account',
                'user' => $user->toArray()]]);
    }
}
