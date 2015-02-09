<?php //-->
namespace Journal\Repositories\Posts;

/**
 * Interface PostRepositoryInterface
 * @package Journal\Core\Repositories\Posts
 */
interface PostRepositoryInterface
{
    /**
     * Creates posts
     *
     * @param int $authorId
     * @param string $title
     * @param string $content
     * @param string $slug
     * @param int $status
     * @param string $publishDate
     * @param array $tagIds
     * @return Post
     */
    public function create($authorId, $title, $content, $slug, $status, $publishDate, $tagIds);

    /**
     * Returns all active posts
     *
     * @return Post
     */
    public function all();

    /**
     * Finds a posts using its id
     *
     * @param int $id
     * @return Post
     */
    public function findById($id);

    /**
     * Find a posts using its slug
     *
     * @param string $slug
     * @return Post
     */
    public function findBySlug($slug);

    /**
     * Updates a posts
     *
     * @param int $id
     * @param int $authorId
     * @param string $title
     * @param string $content
     * @param string $slug
     * @param int $status
     * @param array $tagIds
     * @return Post
     */
    public function update($id, $authorId, $title, $content, $slug, $status, $tagIds);

    /**
     * Set post to inactive
     *
     * @param int $id
     * @return Post
     */
    public function setPostInactive($id);

    /**
     * Creates a slug
     *
     * @param string $slug
     * @param int $id
     * @return mixed
     */
    public function createSlug($slug, $id);
}
