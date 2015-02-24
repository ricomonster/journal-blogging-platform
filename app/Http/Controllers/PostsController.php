<?php namespace Journal\Http\Controllers;

use Journal\Repositories\Posts\PostRepositoryInterface;
use Carbon\Carbon;

class PostsController extends Controller {
    protected $posts;

    public function __construct(PostRepositoryInterface $posts)
    {
        $this->posts = $posts;
    }

    public function editor()
    {
        return view('admin.posts.editor', [
            'post'      => [],
            'datetime'  => Carbon::now(),
            'months'    => $this->months()
        ]);
    }

    public function editorWithId($id)
    {
        // get the post
        $post = $this->posts->findById($id);

        // check if the post exists
        if (empty($post)) {
            // show 404 page
        }

        return view('admin.posts.editor', [
            'post'      => $post,
            'datetime'  => Carbon::now(),
            'months'    => $this->months()
        ]);
    }

    public function index()
    {
        return view('admin.posts.index', [
            'posts' => $this->posts->all()]);
    }

    /**
     * Sets the list of months
     *
     * @return array
     */
    private function months()
    {
        return [
            1 => "Jan",     2 => "Feb",     3 => "Mar",
            4 => "Apr",     5 => "May",     6 => "Jun",
            7 => "Jul",     8 => "Aug",     9 => "Sep",
            10 => "Oct",    11 => "Nov",    12 => "Dec"];
    }
}
