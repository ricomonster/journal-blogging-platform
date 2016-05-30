<?php //-->
namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Requests\AuthFormRequest;
use Journal\Http\Controllers\Controller;
use Auth;

/**
 * Class AuthController
 * @package Journal\Http\Controllers\Admin
 */
class AuthController extends Controller
{
    public function authenticate(AuthFormRequest $request)
    {
        // authenticate
        $credentials = [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
            // make sure that the user is active
            'active'    => 1
        ];

        if (Auth::attempt($credentials)) {
            // login is success
            return redirect('journal/posts');
        }

        // redirect back
        return redirect('journal/login')
            ->with('error_message', 'Invalid e-mail or password.');
    }

    public function login()
    {
        return view('admin.auth.login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('journal/login');
    }
}
