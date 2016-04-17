<?php //-->
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Controllers\API\ApiController;
use Journal\Http\Requests;
use Journal\Repositories\User\UserRepositoryInterface;

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
                    ->respondWithError(['message' => self::USER_NOT_FOUND]);
            }

            // return the user
            return $this->respond([
                'user' => $user->toArray()]);
        }

        // return all users
        $users = $this->users->all();

        return $this->respond([
            'users' => $users->toArray()]);
    }
}
