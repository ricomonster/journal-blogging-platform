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
     * @param $simplePagination
     * @return \Journal\Post
     */
    public function authorPosts($authorId, $postPerPage, $simplePagination = false)
    {
        $posts = Post::with(['author', 'tags'])
            // get active posts
            ->where('active', '=', 1)
            // get published posts
            ->where('status', '=', 1)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // filter
            ->where('author_id', '=', $authorId)
            // sort it according to published date
            ->orderBy('published_at', 'DESC');

        // check if it will use simple pagination
        if ($simplePagination) {
            return $posts->simplePaginate($postPerPage);
        }

        // just use the normal pagination
        return $posts->paginate($postPerPage);
    }

    /**
     * Fetches all the published and ready to show posts.
     *
     * @param $postPerPage
     * @param $simplePagination
     * @return \Journal\Post
     */
    public function blogPosts($postPerPage, $simplePagination = false)
    {
        $posts = Post::with(['author', 'tags'])
            // get active posts
            ->where('active', '=', 1)
            // get published posts
            ->where('status', '=', 1)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // sort it according to published date
            ->orderBy('published_at', 'DESC');

        // check if it will use simple pagination
        if ($simplePagination) {
            return $posts->simplePaginate($postPerPage);
        }

        // just use the normal pagination
        return $posts->paginate($postPerPage);
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
            ->where('status', '=', 1)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // filter
            ->where('slug', '=', $permalink)
            // sort it according to published date
            ->orderBy('published_at', 'DESC')
            // get it
            ->first();

        return $post;
    }

    /**
     * Fetches posts based on the given tag ID.
     *
     * @param $tagId
     * @param $postPerPage
     * @param $simplePagination
     * @return \Journal\Post
     */
    public function tagPosts($tagId, $postPerPage, $simplePagination = false)
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
            ->where('status', '=', 1)
            // get posts that are not converted to a page
            ->where('is_page', '=', 0)
            // get posts that are beyond the current timestamp
            ->where('published_at', '<=', time())
            // sort it according to published date
            ->orderBy('published_at', 'DESC');

        // check if it will use simple pagination
        if ($simplePagination) {
            return $posts->simplePaginate($postPerPage);
        }

        // just use the normal pagination
        return $posts->paginate($postPerPage);
    }
}
