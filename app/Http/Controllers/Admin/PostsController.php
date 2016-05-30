<?php //-->
namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

/**
 * Class PostsController
 * @package Journal\Http\Controllers\Admin
 */
class PostsController extends Controller
{
    public function index()
    {
        return view('admin.posts.index');
    }
}
