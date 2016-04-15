<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;
use Journal\Repositories\Tag\TagRepositoryInterface;

/**
 * Class TagController
 * @package Journal\Http\Controllers\Blog
 */
class TagController extends BlogController
{
    public function page($slug, BlogRepositoryInterface $blogRepository, TagRepositoryInterface $tagRepository)
    {
        // check if slug does not exists
        if (empty($slug)) {
            // return 404 page
            return $this->fourOhFour();
        }

        // get the tag
        $tag = $tagRepository->findBySlug($slug);

        // check if the tag exists
        if (empty($tag)) {
            return $this->fourOhFour();
        }

        // get the posts
        $posts = $blogRepository->tagPosts($tag->id, $this->postPerPage);

        // prepare the data
        $data = [
            'posts' => $posts,
            'tag'   => $tag
        ];

        return $this->loadThemeTemplate('tag', $data);
    }
}
