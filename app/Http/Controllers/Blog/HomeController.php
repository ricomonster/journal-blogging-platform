<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;

/**
 * Class HomeController
 * @package Journal\Http\Controllers\Blog
 */
class HomeController extends BlogController
{
    public function page(BlogRepositoryInterface $blogRepository)
    {
        // initialize the data to be passed
        // posts
        $data['posts'] = $blogRepository->blogPosts($this->postPerPage);

        return $this->loadThemeTemplate('index', $data);
    }
}
