<?php //-->
namespace Journal\Repositories\Post;

use Illuminate\Support\Str;
use Journal\Post;
use Validator;

/**
 * Class DbPostRepository
 * @package Journal\Repositories\Post
 */
class DbPostRepository implements PostRepositoryInterface
{
    /**
     * @param $authorId
     * @param $title
     * @param $markdown
     * @param $slug
     * @param $status
     * @param $publishedAt
     * @return \Journal\Post
     */
    public function create($authorId, $title, $markdown, $slug, $status, $publishedAt, $tagIds)
    {
        $post = Post::create([
            'author_id'     => $authorId,
            'title'         => $title,
            'markdown'      => $markdown,
            'slug'          => $slug,
            'status'        => $status,
            'published_at'  => $publishedAt]);

        // check if there tagIds set
        if ($tagIds) {
            $post->tags()->sync($tagIds);
        }

        // return post
        return $this->findById($post->id);
    }

    /**
     * @return \Journal\Post
     */
    public function all()
    {
        return Post::with(['author', 'tags'])
            ->where('active', '=', 1)
            ->orderBy('published_at', 'DESC')
            ->get();
    }

    /**
     * @param $id
     * @return \Journal\Post
     */
    public function findById($id)
    {
        return Post::with(['author', 'tags'])
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @param $slug
     * @return \Journal\Post
     */
    public function findBySlug($slug)
    {
        return Post::with(['author', 'tags'])
            ->where('slug', '=', $slug)
            ->first();
    }

    /**
     * @param $numberOfPosts
     * @return \Journal\Post
     */
    public function getBlogPosts($numberOfPosts)
    {
        return Post::with(['author', 'tags'])
            // get the active posts
            ->where('active', '=', 1)
            // published post only
            ->where('status', '=', 1)
            // order by timestamp of publish
            ->orderBy('published_at', 'DESC')
            // paginate
            ->paginate($numberOfPosts);
    }

    /**
     * @param $authorId
     * @param $numberOfPosts
     * @return \Journal\Post
     */
    public function getPostsByAuthor($authorId, $numberOfPosts)
    {
        return Post::with(['author', 'tags'])
            // get post by author
            ->where('author_id', '=', $authorId)
            // get the active posts
            ->where('active', '=', 1)
            // published post only
            ->where('status', '=', 1)
            // order by timestamp of publish
            ->orderBy('published_at', 'DESC')
            // paginate it
            ->paginate($numberOfPosts);
    }

    /**
     * @param $id
     * @param $authorId
     * @param $title
     * @param $markdown
     * @param $slug
     * @param $status
     * @param $publishedAt
     * @return \Journal\Post
     */
    public function update($id, $authorId, $title, $markdown, $slug, $status, $publishedAt, $tagIds)
    {
        // get post
        $post = $this->findById($id);

        // update post
        $post->author_id = $authorId;
        $post->title = $title;
        $post->markdown = $markdown;
        $post->slug = $slug;
        $post->status = $status;
        $post->published_at = $publishedAt;
        $post->save();

        // remove all the tags
        $post->tags()->detach();

        // check if there are tags that were given
        if ($tagIds) {
            // add
            $post->tags()->sync($tagIds);
        }

        return $this->findById($post->id);
    }

    /**
     * @param $id
     * @return void
     */
    public function setPostInactive($id)
    {
        // get user
        $post = $this->findById($id);

        // update user
        $post->active = 0;
        $post->save();

        return $post;
    }

    /**
     * @param $data
     * @param $id
     * @return \Illuminate\Support\MessageBag
     */
    public function validatePost($data, $id = null)
    {
        // prepare the rules
        $rules = [
            'slug' => 'required|unique:posts,slug'];

        // prepare the messages
        $messages = [
            'slug.required' => 'Post URL is required.',
            'slug.unique'   => 'Post URL is already taken.'];

        // check if post id is given
        if (!is_null($id)) {
            // append id to the rules
            $rules['slug'] .= ',' . $id;
        }

        // validate
        $validator = Validator::make($data, $rules, $messages);
        $validator->passes();

        // return errors
        return $validator->errors();
    }

    /**
     * @param $string
     * @param $id
     * @return string
     */
    public function validateSlug($string, $id = null)
    {
        // slugify
        $slug = Str::slug(strtolower($string));

        // check if ID is set
        if (is_null($id)) {
            $count = count(Post::where('slug', 'LIKE', $slug.'%')->get());

            // return the slug
            return ($count > 0) ? "{$slug}-{$count}" : $slug;
        }

        // get the post
        $post = $this->findById($id);

        // check if slug is the same with the user slug
        if ($post && $post->slug == $slug) {
            return $post->slug;
        }

        return $slug;
    }
}
