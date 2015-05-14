<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Users\UserRepositoryInterface;
use Auth, Input, Request;

/**
 * Class ApiAuthController
 * @package Journal\Http\Controllers\Api
 */
class ApiAuthController extends ApiController
{
    /**
     * Validates that application exists
     *
     * @return mixed
     */
    public function handshake()
    {
        $parameter = Input::get('verify');

        if (isset($parameter) && $parameter == 'true') {
            // return response
            return $this->respond([
                'validated' => true,
                'url' => Request::root()]);
        }
    }

    /**
     * Validates the user credentials via API or normal log in
     *
     * @param UserRepositoryInterface $userRepository
     * @return mixed
     */
    public function login(UserRepositoryInterface $userRepository)
    {
        // validate credentials
        $email      = Input::get('email');
        $password   = Input::get('password');
        $apiRequest = Input::get('api_call');
        $apiCall    = (isset($apiRequest) && $apiRequest == 'true');

        if (empty($email) || empty($password)) {
            // return response
            return $this->setStatusCode(400)
                ->respondWithError('Username or password is not defined');
        }

        // authenticate user
        if($userRepository->login($email, $password, $apiCall)) {
            // get user details
            $user = $userRepository->findByEmail($email);
            // login user
            return $this->respond([
                'data' => [
                    'url'   => Request::root().'/journal',
                    'user'  => $user->toArray()]]);
        }

        // authentication is not successful due to email is not found or incorrect
        // or password is incorrect
        return $this->setStatusCode(401)
            ->respondWithError('Incorrect username or password');
    }
}
