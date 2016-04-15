<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;

class AuthorController extends BlogController
{
    /**
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * @var UserRepositoryInterface
     */
    protected $user;

    /**
     * @param BlogRepositoryInterface $blog
     * @param UserRepositoryInterface $user
     * @return PageController
     */
    public function __construct(BlogRepositoryInterface $blog, UserRepositoryInterface $user)
    {
        $this->blog = $blog;
        $this->user = $user;
    }

    public function page($slug)
    {
        // check if slug does not exists
        if (empty($slug)) {
            // return 404 page
            return $this->fourOhFour();
        }

        // get the user
        $user = $this->user->findBySlug($slug);

        // check if the user exists
        if (empty($user)) {
            return $this->fourOhFour();
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
