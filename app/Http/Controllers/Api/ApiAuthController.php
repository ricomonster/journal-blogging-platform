<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\User\UserRepositoryInterface;
use Journal\Repositories\Setting\SettingRepositoryInterface;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ApiAuthController extends ApiController
{
    protected $users;

    public function __construct(SettingRepositoryInterface $settings, UserRepositoryInterface $users)
    {
        $this->settings = $settings;
        $this->users = $users;
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // verify user credentials and generate token
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                return $this->setStatusCode(self::UNAUTHORIZED)
                    ->respondWithError(['message' => 'Incorrect e-mail or password.']);
            }
        } catch (JWTException $e) {
            // something went wrong
            return $this->setStatusCode(self::INTERNAL_ERROR)
                ->respondWithError(['message' => 'Something went wrong.']);

        }

        // get first user details
        $user = $this->users->findByEmail($request->input('email'));

        // return token
        return $this->respond([
            'user'  => $user->toArray(),
            'token' => $token]);
    }

    public function checkAuthentication()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError(['message' => 'User not found.']);
            }
        } catch (TokenExpiredException $e) {
            return $this->setStatusCode($e->getStatusCode())
                ->respondWithError(['message' => 'Token expired.']);
        } catch (TokenInvalidException $e) {
            return $this->setStatusCode($e->getStatusCode())
                ->respondWithError(['message' => 'Token invalid.']);
        } catch (JWTException $e) {
            return $this->setStatusCode($e->getStatusCode())
                ->respondWithError(['message' => 'Token required.']);
        }

        // the token is valid and we have found the user via the sub claim
        return $this->respond(['user' => $user]);
    }

    public function checkInstallation()
    {
        $installed = $this->settings->get('installed');

        return $this->respond([
            'installed' => !empty(current($installed))]);
    }
}
