<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\User\UserRepositoryInterface;
use Journal\Repositories\Setting\SettingRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;
use Schema;

/**
 * Class ApiAuthController
 * @package Journal\Http\Controllers\API
 */
class ApiAuthController extends ApiController
{
    /**
     * [$users description]
     * @var [type]
     */
    protected $users;

    /**
     * [__construct description]
     * @param SettingRepositoryInterface $settings [description]
     * @param UserRepositoryInterface    $users    [description]
     */
    public function __construct(SettingRepositoryInterface $settings, UserRepositoryInterface $users)
    {
        $this->settings = $settings;
        $this->users    = $users;
    }

    /**
     * Performs authentication to validated the given email and password if
     * valid and it will return a token to be used in all requests.
     *
     * @param  Request $request
     * @return array
     */
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
        // login the user and update its details
        $user = $this->users->timeLogin($user->id, time());

        // return token
        return $this->respond([
            'user'  => $user->toArray(),
            'token' => $token]);
    }

    /**
     * [checkAuthentication description]
     * @return [type] [description]
     */
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
        // check first if the table is installed
        $tableInstalled = Schema::hasTable('settings');

        if (!$tableInstalled) {
            return $this->setStatusCode(self::INTERNAL_ERROR)
                ->respondWithError(['message' => 'Journal is not yet installed.']);
        }

        $installed = $this->settings->get('installed');

        return $this->respond([
            'installed' => !empty(current($installed))]);
    }
}
