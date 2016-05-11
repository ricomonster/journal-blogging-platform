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
     * @param $simplePagination
     * @return \Journal\Post
     */
    public function authorPosts($authorId, $postPerPage, $simplePagination);

    /**
     * Fetches all the published and ready to show posts.
     *
     * @param $postPerPage
     * @param $simplePagination
     * @return \Journal\Post
     */
    public function blogPosts($postPerPage, $simplePagination);

    /**
     * Fetch a post based on the given permalink,slug or ID.
     *
     * @param $permalink
     * @param $preview
     * @return \Journal\Post
     */
    public function post($permalink, $preview);

    /**
     * Fetches posts based on the given tag ID.
     *
     * @param $tagId
     * @param $postPerPage
     * @param $simplePagination
     * @return \Journal\Post
     */
    public function tagPosts($tagId, $postPerPage, $simplePagination);
}
