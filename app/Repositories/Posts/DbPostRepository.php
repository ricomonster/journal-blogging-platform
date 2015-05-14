<?php //-->
namespace Journal\Repositories\Posts;

use Journal\Post;
use Illuminate\Support\Str;

/**
 * Class DbPostRepository
 * @package Journal\Core\Repositories\Posts
 */
class DbPostRepository implements PostRepositoryInterface
{
    /**
     * Creates posts
     *
     * @param int $authorId
     * @param string $title
     * @param string $markdown
     * @param string $slug
     * @param int $status
     * @param string $publishDate
     * @param array $tagIds
     * @return Post
     */
    public function create($authorId, $title, $markdown, $slug, $status, $publishDate, $tagIds)
    {
        $post = Post::create([
            'author_id'         => $authorId,
            'title'             => $title,
            'markdown'          => $markdown,
            'slug'              => $slug,
            'status'            => $status,
            'published_at'      => $publishDate]);

        // check if there are tags
        if (!empty($tagIds)) {
            $post->tag()->sync($tagIds);
        }

        return $this->findById($post->id);
    }

    /**
     * Returns all active posts
     *
     * @return Post
     */
    public function all()
    {
        return Post::with(['author', 'tag'])
            ->where('active', '=', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Finds a posts using its id
     *
     * @param int $id
     * @return Post
     */
    public function findById($id)
    {
        return Post::with(['author', 'tag'])
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Find a posts using its slug
     *
     * @param string $slug
     * @return Post
     */
    public function findBySlug($slug)
    {
        return Post::with(['author', 'tag'])
            ->where('slug', '=', $slug)
            ->first();
    }

    /**
     * Get the posts for the blog
     *
     * @return Post
     */
    public function getBlogPosts()
    {
        return Post::with(['author', 'tag'])
            ->where('active', '=', 1)
            ->where('status', '=', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get all posts according to its tag ID
     *
     * @param $tagId
     * @return Post
     */
    public function getPostsByTag($tagId)
    {
        return Post::with(['author', 'tag'])
            ->whereHas('tag', function($query) use ($tagId) {
                // set a query to fetch posts according to the tag ID to which
                // a tag is related to
                $query->where('id', '=', $tagId);
            })
            ->where('active', '=', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get posts by its author
     *
     * @param $authorId
     * @return Post
     */
    public function getPostsByAuthor($authorId)
    {
        return Post::with(['author', 'tag'])
            ->where('author_id', '=', $authorId)
            ->where('active', '=', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Updates a posts
     *
     * @param int $id
     * @param int $authorId
     * @param string $title
     * @param string $markdown
     * @param string $slug
     * @param int $status
     * @param array $tagIds
     * @return Post
     */
    public function update($id, $authorId, $title, $markdown, $slug, $status, $tagIds)
    {
        // get post
        $post = $this->findById($id);

        // update
        $post->author_id    = $authorId;
        $post->title        = $title;
        $post->markdown     = $markdown;
        $post->slug         = $slug;
        $post->status       = $status;
        // save
        $post->save();

        // remove rows in the post_tag
        $post->tag()->detach();

        // check if there are tags
        if (!empty($tagIds)) {
            $post->tag()->sync($tagIds);
        }

        return $this->findById($post->id);
    }

    /**
     * Set post to inactive
     *
     * @param int $id
     * @return Post
     */
    public function setPostInactive($id)
    {
        // get the post
        $post = $this->findById($id);
        $post->fill(array('active' => 0))->save();
    }

    /**
     * Creates a slug
     *
     * @param string $slug
     * @param int $id
     * @return mixed
     */
    public function createSlug($slug, $id = null)
    {
        $slugified = Str::slug($slug);

        // check if there's an id set
        if (is_null($id)) {
            $slugCount = count(Post::where('slug', 'LIKE', $slugified.'%')->get());

            // return the slug
            return ($slugCount > 0) ? "{$slugified}-{$slugCount}" : $slugified;
        }

        // there is an id set, get user
        $post = $this->findById($id);

        // check if slug is the same with the user slug
        if ($post->slug == $slugified) {
            return $post->slug;
        }

        $slugCount = count(Post::where('slug', 'LIKE', $slugified.'%')->get());

        // return the slug
        return ($slugCount > 0) ? "{$slugified}-{$slugCount}" : $slugified;
    }
}
