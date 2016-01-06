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

class ApiAuthController extends ApiController
{
    protected $users;

    public function __construct(SettingRepositoryInterface $settings, UserRepositoryInterface $users)
    {
        $this->settings = $settings;
        $this->users    = $users;
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
        // login the user and update its details
        $user = $this->users->timeLogin($user->id, time());

        // return token
        return $this->respond([
            'user'  => $user->toArray(),
            'token' => $token]);
    }

    public function checkAuthentication(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
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
