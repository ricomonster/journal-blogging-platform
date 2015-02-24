<?php //-->
namespace Journal\Http\Controllers;

use Journal\Repositories\Users\UserRepositoryInterface;

class UsersController extends Controller {
    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function addUser()
    {
        return view('admin.users.add');
    }

    public function index()
    {
        return view('admin.users.index', [
            'users' => $this->users->all()
        ]);
    }

    public function profile($id)
    {
        // get the user
        $user = $this->users->findById($id);

        // check if the user exists
        if (empty($user)) {
            // show 404 page
        }

        return view('admin.users.profile', [
            'user' => $user]);
    }
}
