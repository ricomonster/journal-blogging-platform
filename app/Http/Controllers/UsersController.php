<?php //-->
namespace Journal\Http\Controllers;

use Journal\Repositories\Users\UserRepositoryInterface;

class UsersController extends Controller {
    public function addUser()
    {
        return view('admin.users.add');
    }

    public function index(UserRepositoryInterface $usersRepository)
    {
        return view('admin.users.index', [
            'users' => $usersRepository->all()
        ]);
    }

    public function profile($id, UserRepositoryInterface $usersRepository)
    {
        // get the user
        $user = $usersRepository->findById($id);

        // check if the user exists
        if (empty($user)) {
            // show 404 page
        }

        return view('admin.users.profile', [
            'user' => $user]);
    }
}
