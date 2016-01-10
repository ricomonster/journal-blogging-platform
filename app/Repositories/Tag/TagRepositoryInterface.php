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
     * @param $imageUrl
     * @param $description
     * @param $slug
     * @return \Journal\Tag
     */
    public function create($tag, $imageUrl, $description, $slug);

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
     * @param $name
     * @param $slug
     * @param $imageUrl
     * @return \Journal\Tag
     */
    public function update($id, $name, $slug, $imageUrl);

    /**
     * @param $id
     * @return void
     */
    public function deactivate($id);

    /**
     * @param $string
     * @param $id
     * @return string
     */
    public function generateSlugUrl($string, $id);

    /**
     * @param $id
     * @param $numberOfPosts
     * @return \Journal\Post
     */
    public function getPosts($id, $numberOfPosts);

    /**
     * @param $tag
     * @return \Illuminate\Support\MessageBag
     */
    public function validateTags($tag);
}
