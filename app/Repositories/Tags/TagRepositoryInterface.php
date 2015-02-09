<?php //-->
namespace Journal\Repositories\Tags;

/**
 * Interface TagRepositoryInterface
 * @package Journal\Core\Repositories\Tags
 */
interface TagRepositoryInterface
{
    /**
     * Create or check if tag exists and returns the ids of the tags
     *
     * @param $tags
     * @return array
     */
    public function set($tags);

    /**
     * Get all tags
     *
     * @return Tag
     */
    public function all();

    /**
     * Get a tag by its name
     *
     * @param $tag
     * @return mixed
     */
    public function findByTag($tag);

    /**
     * Get a tag by its slug
     *
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug);
}
