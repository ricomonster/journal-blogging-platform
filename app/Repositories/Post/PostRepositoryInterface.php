<?php //-->
namespace Journal\Repositories\Post;

/**
 * Interface PostRepositoryInterface
 * @package Journal\Repositories\Post
 */
interface PostRepositoryInterface
{
    /**
     * @param $post
     * @return mixed
     */
    public function create($post);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $query
     * @return mixed
     */
    public function search($query);

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug);

    /**
     * @param $post
     * @return mixed
     */
    public function update($post);

    /**
     * @param $id
     * @return mixed
     */
    public function setToInactive($id);

    /**
     * @param $string
     * @param $id
     * @return mixed
     */
    public function generateSlug($string, $id);

    /**
     * @param $post
     * @return mixed
     */
    public function validatePost($post);
}
