<?php //-->
namespace Journal\Repositories\Blog;

/**
 * Interface BlogRepositoryInterface
 * @package Journal\Repositories\Blog
 */
interface BlogRepositoryInterface
{
    /**
     * Fetches the posts of an author based on the given ID.
     *
     * @param $authorId
     * @param $postPerPage
     * @return \Journal\Post
     */
    public function authorPosts($authorId, $postPerPage);

    /**
     * Fetches all the published and ready to show posts.
     *
     * @param $postPerPage
     * @return \Journal\Post
     */
    public function blogPosts($postPerPage);

    /**
     * Fetch a post based on the given permalink,slug or ID.
     *
     * @param $permalink
     * @return \Journal\Post
     */
    public function post($permalink);

    /**
     * Fetches posts based on the given tag ID.
     *
     * @param $tagId
     * @param $postPerPage
     * @return \Journal\Post
     */
    public function tagPosts($tagId, $postPerPage);
}
