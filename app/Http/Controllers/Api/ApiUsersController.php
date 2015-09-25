<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\User\UserRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class ApiUsersController extends ApiController
{
    protected $users;

    public function __construct(UserRepositoryInterface $user)
    {
        // set the JWT middleware
        $this->middleware('jwt.auth', ['except' => ['all', 'getUser']]);

        $this->users = $user;
    }

    public function all()
    {
        // get all users
        $users = $this->users->all();

        return $this->respond([
            'users' => $users->toArray()]);
    }

    public function create(Request $request)
    {
        // validate first
        $messages = $this->users->validateUserCreate($request->all());

        // check if there are errors
        if (count($messages) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // create the user
        $user = $this->users->create(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'));

        // return
        return $this->respond([
            'user' => $user->toArray()]);
    }

    public function changePassword(Request $request)
    {
        $id = $request->input('user_id');

        // check if ID is not empty or set
        if (!$id || empty($id)) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'User ID is not set.']);
        }

        // get the user
        $user = $this->users->findById($id);

        // check if user exists
        if (empty($user)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => 'User not found.']);
        }

        // do basic validation
        $messages = $this->users->validateChangePassword($request->all());
        // check for errors
        if (count($messages) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // check if the user inputted its current password
        // update
        // return user details
    }

    public function getUser(Request $request)
    {
        $id = $request->input('user_id');

        // check if ID is not empty or set
        if (!$id || empty($id)) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'User ID is not set.']);
        }

        // get the user
        $user = $this->users->findById($id);

        // check if user exists
        if (empty($user)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => 'User not found.']);
        }

        // return the error
        return $this->respond([
            'user' => $user->toArray()]);
    }

    public function updateDetails(Request $request)
    {
        $id = $request->input('user_id');

        // check if ID is not empty or set
        if (!$id || empty($id)) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'User ID is not set.']);
        }

        // get the user
        $user = $this->users->findById($id);

        // check if user exists
        if (empty($user)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => 'User not found.']);
        }

        // validate user details
        $messages = $this->users->validateUserUpdate($request->all(), $id);

        // check if there are errors
        if (count($messages) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // update
        $user = $this->users->updateDetails(
            $id,
            $request->input('name'),
            $request->input('email'),
            $request->input('biography'),
            $request->input('location'),
            $request->input('website'),
            $request->input('avatar_url'),
            $request->input('cover_url'));

        // return user details
        return $this->respond([
            'user' => $user->toArray()]);
    }
}
