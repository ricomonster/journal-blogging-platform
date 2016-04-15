<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;

class PageController extends BlogController
{
    /**
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * @param BlogRepositoryInterface $blog
     * @return PageController
     */
    public function __construct(BlogRepositoryInterface $blog)
    {
        $this->blog = $blog;
    }

    public function page($parameter)
    {
        $data = [];

        // get the post
        $data['post'] = $this->blog->post($parameter);

        // check if it's empty
        if (empty($data['post'])) {
            // show 404 page
            return $this->fourOhFour();
        }

        return $this->loadThemeTemplate('post', $data);
    }
}
