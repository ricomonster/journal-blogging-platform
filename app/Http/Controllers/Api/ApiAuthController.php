<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Users\UserRepositoryInterface;
use Auth, Input, Request;

class ApiAuthController extends ApiController
{
    public function handshake()
    {
        $parameter = Input::get('verify');

        if (isset($parameter) && $parameter == 'true') {
            // return response
            return $this->respond(array(
                'validated' => true,
                'url' => Request::root()));
        }
    }

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
            return $this->respond(array(
                'data' => array(
                    'url'   => Request::root().'/journal',
                    'user'  => $user->toArray())));
        }

        // authentication is not successful due to email is not found or incorrect
        // or password is incorrect
        return $this->setStatusCode(401)
            ->respondWithError('Incorrect username or password');
    }

    public function logout()
    {
        // destroy auth
        Auth::logout();

        return redirect('journal/login');
    }
}
