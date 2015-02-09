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
}
