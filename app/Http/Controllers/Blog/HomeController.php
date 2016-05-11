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
     * The blog repository interface instance.
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * HomeController constructor.
     * @param BlogRepositoryInterface $blog
     */
    public function __construct(BlogRepositoryInterface $blog)
    {
        parent::__construct();

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
