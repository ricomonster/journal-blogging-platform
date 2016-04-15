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
    /**
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * @param BlogRepositoryInterface $blog
     * @return HomeController
     */
    public function __construct(BlogRepositoryInterface $blog)
    {
        $this->blog = $blog;
    }

    public function page()
    {
        // initialize the data to be passed
        // posts
        $data['posts'] = $this->blog->blogPosts($this->postPerPage);

        return $this->loadThemeTemplate('index', $data);
    }
}
