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
    /**
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * @var TagRepositoryInterface
     */
    protected $tag;

    /**
     * @param BlogRepositoryInterface $blog
     * @param TagRepositoryInterface $tag
     * @return HomeController
     */
    public function __construct(BlogRepositoryInterface $blog, TagRepositoryInterface $tag)
    {
        $this->blog = $blog;
        $this->tag  = $tag;
    }

    public function page($slug)
    {
        // check if slug does not exists
        if (empty($slug)) {
            // return 404 page
            return $this->fourOhFour();
        }

        // get the tag
        $tag = $this->tag->findBySlug($slug);

        // check if the tag exists
        if (empty($tag)) {
            return $this->fourOhFour();
        }

        // get the posts
        $posts = $this->blog->tagPosts($tag->id, $this->postPerPage);

        // prepare the data
        $data = [
            'posts' => $posts,
            'tag'   => $tag
        ];

        return $this->loadThemeTemplate('tag', $data);
    }
}
