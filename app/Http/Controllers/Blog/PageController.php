<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;

class PageController extends BlogController
{
    public function page($parameter, BlogRepositoryInterface $blogRepository)
    {
        $data = [];

        // get the post
        $data['post'] = $blogRepository->post($parameter);

        // check if it's empty
        if (empty($data['post'])) {
            // show 404 page
            return $this->fourOhFour();
        }

        return $this->loadThemeTemplate('post', $data);
    }
}
