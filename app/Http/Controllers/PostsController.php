<?php namespace Journal\Http\Controllers;

use Journal\Repositories\Posts\PostRepositoryInterface;
use Carbon\Carbon;

class PostsController extends Controller {
    public function editor()
    {
        return view('admin.posts.editor', [
            'post'      => [],
            'datetime'  => Carbon::now(),
            'months'    => $this->months()
        ]);
    }

    public function editorWithId($id, PostRepositoryInterface $postsRepository)
    {
        // get the post
        $post = $postsRepository->findById($id);

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

    public function index(PostRepositoryInterface $posts)
    {
        return view('admin.posts.index', [
            'posts' => $posts->all()]);
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
