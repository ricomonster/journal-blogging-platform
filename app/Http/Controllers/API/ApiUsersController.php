<?php //-->
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Controllers\API\ApiController;
use Journal\Http\Requests;
use Journal\Repositories\User\UserRepositoryInterface;

/**
 * Class ApiUsersController
 * @package Journal\Http\Controllers\API
 */
class ApiUsersController extends ApiController
{
    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @param UserRepositoryInterface $users
     */
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * Will validate and update the given password of the user.
     *
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        // check if there's a given user_id
        if (!$request->input('user_id')) {
            // return an error message
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => self::USER_ID_REQUIRED
                ]);
        }

        // check if the user exists
        $user = $this->users->findById($request->input('user_id'));

        if (empty($user)) {
            // return an error telling that the user does not exists
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError([
                    'message' => self::USER_NOT_FOUND
                ]);
        }

        // validate the inputted passwords
        $messages = $this->users->validatePasswords($request->all());

        // check for errors
        if (count($messages) > 0) {
            // return the errors
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // check the current password of the user
        $same = $this->users->checkUserCurrentPassword(
            $request->input('user_id'),
            $request->input('current_password'));

        if (!$same) {
            return $this->setStatusCode(self::FORBIDDEN)
                ->respondWithError([
                    'message' => self::CURRENT_PASSWORD_NOT_THE_SAME
                ]);
        }

        // update password
        $this->users->updatePassword(
            $request->input('user_id'),
            $request->input('new_password'));

        // assuming everything went well
        return $this->respond([
            'error' => false
        ]);
    }

    public function create(Request $request)
    {

    }

    /**
     * Fetches the users either by giving the user id or not.
     *
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        // check if user_id exists
        if ($request->input('user_id')) {
            // get the user
            $user = $this->users->findById($request->input('user_id'));

            // check if it exists
            if (empty($user)) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError([
                        'message' => self::USER_NOT_FOUND
                    ]);
            }

            // return the user
            return $this->respond([
                'user' => $user->toArray()
            ]);
        }

        // return all users
        $users = $this->users->all();

        return $this->respond([
            'users' => $users->toArray()
        ]);
    }

    /**
     * Updates the details of the user.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        // check if there's a user id
        if (!$request->input('user_id')) {
            // return an error message
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => self::USER_ID_REQUIRED
                ]);
        }

        // check if the user exists
        $user = $this->users->findById($request->input('user_id'));

        if (empty($user)) {
            // user not found, return an error message
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError([
                    'message' => self::USER_NOT_FOUND
                ]);
        }

        // validate the data
        $messages = $this->users->validateUser($request->all());

        // check for errors
        if (count($messages) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // it seems we're good to go so let's update the user
        $user = $this->users->updateDetails($request->all());

        return $this->respond([
            'user' => $user->toArray()
        ]);
    }
}
