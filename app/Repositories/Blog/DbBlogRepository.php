<?php //-->
namespace Journal\Repositories\Blog;

use Journal\Repositories\Blog\BlogRepositoryInterface;
use Journal\Post;

/**
 * Class DbBlogRepository
 * @package Journal\Repositories\Blog
 */
class DbBlogRepository implements BlogRepositoryInterface
{
    /**
     * Fetches the posts of an author based on the given ID.
     *
     * @param $authorId
     * @param $postPerPage
     * @return \Journal\Post
     */
    public function authorPosts($authorId, $postPerPage)
    {
        $post = Post::with(['author', 'tags'])
            // get active posts
            ->where('active', '=', 1)
            // get published posts
            ->where('status', '=', 2)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // filter
            ->where('author_id', '=', $authorId)
            // paginate
            ->paginate($postPerPage);

        return $post;
    }

    /**
     * Fetches all the published and ready to show posts.
     *
     * @param $postPerPage
     * @return \Journal\Post
     */
    public function blogPosts($postPerPage)
    {
        $posts = Post::with(['author', 'tags'])
            // get active posts
            ->where('active', '=', 1)
            // get published posts
            ->where('status', '=', 2)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // sort it according to published date
            ->orderBy('published_at', 'DESC')
            // paginate
            ->paginate($postPerPage);

        return $posts;
    }

    /**
     * Fetch a post based on the given permalink,slug or ID.
     *
     * @param $permalink
     * @return \Journal\Post
     */
    public function post($permalink)
    {
        $post = Post::with(['author', 'tags'])
            // get active posts
            ->where('active', '=', 1)
            // get published posts
            ->where('status', '=', 2)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // filter
            ->where('slug', '=', $permalink)
            // get it
            ->first();

        return $post;
    }

    /**
     * Fetches posts based on the given tag ID.
     *
     * @param $tagId
     * @param $postPerPage
     * @return \Journal\Post
     */
    public function tagPosts($tagId, $postPerPage)
    {
        $posts = Post::with(['author', 'tags'])
            // we need to get posts based on the tags
            ->whereHas('tags', function($query) use ($tagId) {
                // set a query to fetch posts according to the tag ID to which
                // a tag is related to
                $query->where('id', '=', $tagId);
            })
            // get active posts
            ->where('active', '=', 1)
            // get published posts
            ->where('status', '=', 2)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // sort it according to published date
            ->orderBy('published_at', 'DESC')
            // paginate
            ->paginate($postPerPage);

        return $posts;
    }
}
