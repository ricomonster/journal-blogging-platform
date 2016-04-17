<?php //-->
namespace Journal\Repositories\Tag;

use Journal\Tag;

class DbTagRepository implements TagRepositoryInterface
{
    /**
     * Creates a tag
     *
     * @param $tag
     * @return \Journal\Tag
     */
    public function create($tag)
    {
        $tag = Tag::create([
            'title'         => $tag['title'],
            'description'   => (isset($tag['description'])) ?
                $tag['description'] : null,
            'cover_image'   => (isset($tag['cover_image'])) ?
                $tag['cover_image'] : null,
            'slug'          => $this->generateSlug($tag['title'])
        ]);

        return $tag;
    }

    /**
     * Fetch all tags
     *
     * @return \Journal\Tag
     */
    public function all()
    {
        return Tag::where('active', '=', 1)
            ->get();
    }

    /**
     * Fetch a tag based on the ID.
     *
     * @param $id
     * @return \Journal\Tag
     */
    public function findById($id)
    {
        return Tag::where('active', '=', 1)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Fetch a tag based on the slug.
     *
     * @param $id
     * @return \Journal\Tag
     */
    public function findBySlug($slug)
    {
        return Tag::where('active', '=', 1)
            ->where('slug', '=', $slug)
            ->first();
    }

    /**
     * Searches thru the tags based on the given query.
     *
     * @param $query
     * @return \Journal\Tag
     */
    public function search($query)
    {

    }

    /**
     * Updates the given tag
     *
     * @param $tag
     * @return \Journal\Tag
     */
    public function update($tag)
    {

    }

    /**
     * Set the given Tag ID to inactive.
     *
     * @param $id
     * @return void
     */
    public function setToInactive($id)
    {

    }

    /**
     * Checks the tags if they are already saved in the database or if it does
     * not exists, it will create the tag.
     *
     * @param $tags
     * @return array
     */
    public function generatePostTags($tags)
    {
        $tagIds = [];

        if (empty($tags)) {
            return [];
        }

        // loop the tags
        foreach ($tags as $key => $tag) {
            // check if there's an id or title
            if (isset($tag['id'])) {
                // it's already an existing tag
                // get the ID
                $tagIds[] = $tag['id'];
                continue;
            }

            // make the tag name as slug
            $slug = str_slug($tag['tag'], '-');

            // check if it exists
            $exists = $this->findBySlug($slug);

            if (empty($exists)) {
                // create the tag
                $result = $this->create([
                    'title'         => $tag['tag'],
                    'slug'          => $slug
                ]);

                // get the ID
                $tagIds[] = $result->id;
                continue;
            }

            // get the ID
            $tagIds[] = $exists->id;
        }

        return $tagIds;
    }

    /**
     * Generates a slug based on the given name of the tag.
     *
     * @param $string
     * @param $id
     * @return mixed
     */
    public function generateSlug($string, $id = null)
    {
        // slugify the string
        $slug = str_slug($string, '-');

        // prepare the query to check for similar slugs
        $query = Tag::where('slug', 'LIKE', $slug.'%');

        // check if the ID is not null
        if (!is_null($id)) {
            $query->where('id', '!=', $id);
        }

        // execute the query and count the results
        $count = count($query->get());

        return ($count > 0) ? $slug.'-'.$count : $slug;
    }
}
