<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;

/**
 * Class 
 * @package Journal\Http\Controllers\API
 */
class AuthorController extends BlogController
{
    /**
     * The blog repository interface instance.
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * The user repository interface instance.
     * @var UserRepositoryInterface
     */
    protected $user;

    /**
     * AuthorController constructor.
     *
     * @param BlogRepositoryInterface $blog
     * @param UserRepositoryInterface $user
     */
    public function __construct(BlogRepositoryInterface $blog, UserRepositoryInterface $user)
    {
        parent::__construct();

        $this->blog = $blog;
        $this->user = $user;
    }

    /**
     * Renders the author page.
     *
     * @param $slug
     * @return View
     */
    public function page($slug)
    {
        // check if slug does not exists
        if (empty($slug)) {
            // return 404 page
            return $this->fourOhFourPage();
        }

        // get the user
        $user = $this->user->findBySlug($slug);

        // check if the user exists
        if (empty($user)) {
            return $this->fourOhFourPage();
        }

        // get the posts
        $posts = $this->blog->authorPosts($user->id, $this->postPerPage);

        // prepare the data
        $data = [
            'posts'     => $posts,
            'author'    => $user
        ];

        return $this->loadThemeTemplate('author', $data);
    }
}
