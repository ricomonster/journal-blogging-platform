<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;
use Auth;

class PageController extends BlogController
{
    /**
     * The blog repository interface instance.
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * PageController constructor.
     * @param BlogRepositoryInterface $blog
     */
    public function __construct(BlogRepositoryInterface $blog)
    {
        parent::__construct();

        $this->blog = $blog;
    }

    /**
     * @param $parameter
     * @param Request $request
     * @return View
     */
    public function page($parameter, Request $request)
    {
        // check if is just a preview and there should a logged in user.
        $preview = $request->input('preview');

        $post = $this->blog->post($parameter, $preview);

        // check if the post is empty
        if (empty($post)) {
            return $this->fourOhFourPage();
        }

        // render it!
        return $this->loadThemeTemplate('post',[
            'post' => $post
        ]);
    }
}
