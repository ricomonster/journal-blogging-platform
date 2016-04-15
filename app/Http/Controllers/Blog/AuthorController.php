<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;

class AuthorController extends BlogController
{
    public function page($slug, BlogRepositoryInterface $blogRepository, UserRepositoryInterface $userRepository)
    {
        // check if slug does not exists
        if (empty($slug)) {
            // return 404 page
            return $this->fourOhFour();
        }

        // get the user
        $user = $userRepository->findBySlug($slug);

        // check if the user exists
        if (empty($user)) {
            return $this->fourOhFour();
        }

        // get the posts
        $posts = $blogRepository->authorPosts($user->id, $this->postPerPage);

        // prepare the data
        $data = [
            'posts'     => $posts,
            'author'    => $user
        ];

        return $this->loadThemeTemplate('author', $data);
    }
}
