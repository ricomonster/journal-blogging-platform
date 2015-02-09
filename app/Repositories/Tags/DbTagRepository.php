<?php //-->
namespace Journal\Repositories\Tags;

use Journal\Tag;
use Illuminate\Support\Str;

class DbTagRepository implements TagRepositoryInterface
{
    /**
     * Create or check if tag exists and returns the ids of the tags
     *
     * @param $tags
     * @return array
     */
    public function set($tags)
    {
        $tagIds = [];

        // loop the tags
        foreach ($tags as $key => $tag) {
            $tag = strtolower($tag);
            // check if tag exists else it will create it
            $result = Tag::firstOrCreate([
                'tag' => $tag,
                'slug' => Str::slug($tag)]);

            // push the id to the array
            $tagIds[] = $result->id;
        }

        return $tagIds;
    }

    /**
     * @return Tag
     */
    public function all()
    {
        return Tag::orderBy('tag', 'asc')->get();
    }

    /**
     * @param $tag
     * @return mixed
     */
    public function findByTag($tag)
    {
        return Tag::where('tag', '=', $tag)->first();
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return Tag::where('slug', '=', $slug)->first();
    }
}
