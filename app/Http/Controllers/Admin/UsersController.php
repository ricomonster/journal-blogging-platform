<?php //-->
namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function changePassword()
    {
        return view('admin.users.change-password');
    }

    public function lists()
    {
        return view('admin.users.list');
    }

    public function profile($id)
    {
        return view('admin.users.profile', compact('id'));
    }
}
