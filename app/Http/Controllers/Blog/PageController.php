<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;

class PageController extends BlogController
{
    /**
     * @param $parameter
     * @param Request $request
     * @param BlogRepositoryInterface $blogRepository
     * @return View
     */
    public function page($parameter, Request $request, BlogRepositoryInterface $blogRepository)
    {
        // initialize array where to save some data
        $data = [];

        // get the post
        $post = $blogRepository->post($parameter);

        // check if we have a preview flag
        if ($request->input('preview')) {
            // check if the post is already published
            if ($post->status == 1) {
                // redirect to a 404 page
                return $this->fourOhFourPage();
            }

            // TODO: check if there's a logged in user and check the privilege
        }

        // check if it's empty
        if (empty($post)) {
            // show 404 page
            return $this->fourOhFourPage();
        }

        $data['post'] = $post;

        return $this->loadThemeTemplate('post', $data);
    }
}
