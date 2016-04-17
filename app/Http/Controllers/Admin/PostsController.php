<?php //-->
namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

class PostsController extends Controller
{
    public function lists()
    {
        return view('admin.posts.list');
    }
}
