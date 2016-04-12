<?php //-->
namespace Journal\Repositories\Tag;

interface TagRepositoryInterface
{
    /**
     * Creates a tag
     *
     * @param $tag
     * @return \Journal\Tag
     */
    public function create($tag);

    /**
     * Fetch all tags
     *
     * @return \Journal\Tag
     */
    public function all();

    /**
     * Fetch a tag based on the ID.
     *
     * @param $id
     * @return \Journal\Tag
     */
    public function findById($id);

    /**
     * Fetch a tag based on the slug.
     *
     * @param $id
     * @return \Journal\Tag
     */
    public function findBySlug($slug);

    /**
     * Searches thru the tags based on the given query.
     *
     * @param $query
     * @return \Journal\Tag
     */
    public function search($query);

    /**
     * Updates the given tag
     *
     * @param $tag
     * @return \Journal\Tag
     */
    public function update($tag);

    /**
     * Set the given Tag ID to inactive.
     *
     * @param $id
     * @return void
     */
    public function setToInactive($id);

    /**
     * Generates a slug based on the given name of the tag.
     *
     * @param $string
     * @param $id
     * @return mixed
     */
    public function generateSlug($string, $id);

    /**
     * Checks the tags if they are already saved in the database or if it does
     * not exists, it will create the tag.
     *
     * @param $tags
     * @return array
     */
    public function generatePostTags($tags);
}
