<?php namespace Journal\Http\Controllers;

class AuthController extends Controller {
    public function login()
    {
        return view('admin.login');
    }
}
