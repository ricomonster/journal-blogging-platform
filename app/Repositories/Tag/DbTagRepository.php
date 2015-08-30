<?php //-->
namespace Journal\Repositories\Tag;

use Illuminate\Support\Str;
use Journal\Post;
use Journal\Tag;

class DbTagRepository implements TagRepositoryInterface {
    /**
     * @param $tag
     * @return \Journal\Tag
     */
    public function create($tag)
    {
        return Tag::firstOrCreate([
            'name' => $tag,
            'slug' => Str::slug(strtolower($tag))]);
    }

    /**
     * @return \Journal\Tag
     */
    public function all()
    {
        return Tag::where('active', '=', 1)
            ->orderBy('name', 'ASC')
            ->get();
    }

    /**
     * @param $id
     * @return \Journal\Tag
     */
    public function findById($id)
    {
        return Tag::where('id', '=', $id)->first();
    }

    /**
     * @param $slug
     * @return \Journal\Tag
     */
    public function findBySlug($slug)
    {
        return Tag::where('slug', '=', $slug)->first();
    }

    /**
     * @param $id
     * @param $numberOfPosts
     * @return \Journal\Post
     */
    public function getPosts($id, $numberOfPosts)
    {
        return Post::with(['author', 'tags'])
            ->whereHas('tags', function($query) use ($id) {
                // set a query to fetch posts according to the tag ID to which
                // a tag is related to
                $query->where('id', '=', $id);
            })
            // get the active posts
            ->where('active', '=', 1)
            // published post only
            ->where('status', '=', 1)
            // order by timestamp of publish
            ->orderBy('published_at', 'DESC')
            ->paginate($numberOfPosts);
    }
}
