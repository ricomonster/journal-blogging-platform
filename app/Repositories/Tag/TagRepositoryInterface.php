<?php //-->
namespace Journal\Repositories\Tag;

/**
 * Interface TagRepositoryInterface
 * @package Journal\Repositories\Tag
 */
interface TagRepositoryInterface
{
    /**
     * @param $tag
     * @return \Journal\Tag
     */
    public function create($tag);

    /**
     * @return \Journal\Tag
     */
    public function all();

    /**
     * @param $id
     * @return \Journal\Tag
     */
    public function findById($id);

    /**
     * @param $slug
     * @return \Journal\Tag
     */
    public function findBySlug($slug);

    /**
     * @param $id
     * @param $numberOfPosts
     * @return \Journal\Post
     */
    public function getPosts($id, $numberOfPosts);
}
