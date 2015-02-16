<?php namespace Journal\Http\Controllers;

use View;

class BlogController extends Controller {
    protected $theme = 'casper';
    public function __construct()
    {
        // setup the view
        View::addLocation(base_path('themes'));
    }

    public function index()
    {
        return view($this->theme.'.index');
    }

    public function post()
    {
        return view($this->theme.'.posts');
    }

    public function author()
    {
        return view($this->theme.'.author');
    }
}
