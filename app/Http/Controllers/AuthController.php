<?php //-->
namespace Journal\Http\Controllers;

use Auth;

class AuthController extends Controller {
    public function login()
    {
        return view('admin.login');
    }

    public function logout()
    {
        // destroy auth
        Auth::logout();

        return redirect('journal/login');
    }
}
