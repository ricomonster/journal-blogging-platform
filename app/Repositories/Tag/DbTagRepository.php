<?php //-->
namespace Journal\Repositories\Tag;

use Illuminate\Support\Str;
use Journal\Post;
use Journal\Tag;
use Validator;

class DbTagRepository implements TagRepositoryInterface
{
    /**
     * @param $tag
     * @param $imageUrl
     * @param $description
     * @param $slug
     * @return \Journal\Tag
     */
    public function create($tag, $imageUrl, $description, $slug = null)
    {
        if (is_null($slug)) {
            $slug = $this->generateSlugUrl($tag);
        }

        return Tag::firstOrCreate([
            'name'          => $tag,
            'slug'          => $slug,
            'description'   => $description,
            'image_url'     => $imageUrl]);
    }

    /**
     * @return \Journal\Tag
     */
    public function all()
    {
        return Tag::with(['posts'])
            ->where('active', '=', 1)
            ->orderBy('name', 'ASC')
            ->get();
    }

    /**
     * @param $id
     * @return \Journal\Tag
     */
    public function findById($id)
    {
        return Tag::with(['posts'])->where('id', '=', $id)->first();
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
     * @param $name
     * @param $slug
     * @param $imageUrl
     * @return \Journal\Tag
     */
    public function update($id, $name, $slug, $imageUrl)
    {
        // get the tag
        $tag = $this->findById($id);

        // update the fields
        $tag->name      = $name;
        $tag->slug      = $slug;
        $tag->image_url = $imageUrl;

        // save it
        $tag->save();

        // return the tag
        return $tag;
    }

    /**
     * @param $id
     * @return void
     */
    public function deactivate($id)
    {
        // get the tag
        $tag = $this->findById($id);

        // unlink
        $tag->posts()->detach();

        // deactivate
        $tag->active = 0;
        $tag->save();
    }

    /**
     * @param $string
     * @param $id
     * @return string
     */
    public function generateSlugUrl($string, $id = null)
    {
        // slugify
        $slug = Str::slug(strtolower($string));

        // check if ID is set
        if (is_null($id)) {
            $count = count(Tag::where('slug', 'LIKE', $slug.'%')->get());

            // return the slug
            return ($count > 0) ? "{$slug}-{$count}" : $slug;
        }

        // get the post
        $tag = $this->findById($id);

        // check if slug is the same with the user slug
        if ($tag && $tag->slug == $slug) {
            return $tag->slug;
        }

        return $slug;
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

    /**
     * @param $tag
     * @return \Illuminate\Support\MessageBag
     */
    public function validateTags($tag)
    {
        // set the rules
        $rules = [
            'name' => 'required'];

        // prepare the custom error messages
        $messages = [
            'name.required' => 'Name of the tag is required.'];

        // validate
        $validator = Validator::make($tag, $rules, $messages);
        $validator->passes();

        return $validator->errors();
    }
}
