<?php //-->
namespace Journal\Repositories\Post;

use Journal\Repositories\Post\PostRepositoryInterface;
use Journal\Post;
use Validator;

/**
 * Class DbPostRepository
 * @package Journal\Repositories\Post
 */
class DbPostRepository implements PostRepositoryInterface
{
    /**
     * @param $post
     * @return mixed
     */
    public function create($post)
    {
        $publishedAt = $post['published_at'];

        // validate first the publish timestamp if it is less than the
        // current time
        if ($post['status'] == 1 && ($publishedAt < time())) {
            $publishedAt = time();
        }

        $result = Post::create([
            'author_id'     => $post['author_id'],
            'title'         => $post['title'],
            'content'       => $post['content'],
            'cover_image'   => $post['cover_image'],
            'slug'          => $this->generateSlug($post['title']),
            'status'        => $post['status'],
            'published_at'  => $publishedAt
        ]);

        // check if there are tag ids
        if (isset($post['tag_ids']) && !empty($post['tag_ids'])) {
            $result->tags()->sync($post['tag_ids']);
        }

        return $this->findById($result->id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return Post::with(['author'])
            ->where('active', '=', 1)
            ->where('is_page', '=', 0)
            ->orderBy('published_at', 'DESC')
            ->get();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function search($query)
    {

    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return Post::with(['author', 'tags'])
            ->where('id', '=', $id)
            ->where('active', '=', 1)
            ->where('is_page', '=', 0)
            ->first();
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return Post::with(['author'])
            ->where('slug', '=', $slug)
            ->where('active', '=', 1)
            ->where('is_page', '=', 0)
            ->first();
    }

    /**
     * @param $post
     * @return mixed
     */
    public function update($post)
    {
        $savedPost = $this->findById($post['post_id']);

        // update
        $savedPost->title        = $post['title'];
        $savedPost->content      = $post['content'];
        $savedPost->cover_image  = $post['cover_image'];
        $savedPost->slug         = $this->generateSlug($post['title'], $post['post_id']);
        $savedPost->status       = $post['status'];
        $savedPost->published_at = $post['published_at'];
        $savedPost->save();

        // remove all the tags
        $savedPost->tags()->detach();

        // check if there are tag ids
        if (isset($post['tag_ids']) && !empty($post['tag_ids'])) {
            $savedPost->tags()->sync($post['tag_ids']);
        }

        return $this->findById($savedPost->id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function setToInactive($id)
    {
        $post = $this->findById($id);

        // detach tags
        $post->tags()->detach();

        $post->active = 0;
        $post->save();
    }

    /**
     * @param $string
     * @param $id
     * @return mixed
     */
    public function generateSlug($string, $id = null)
    {
        // slugify the string
        $slug = str_slug($string, '-');

        // prepare the query to check for similar slugs
        $query = Post::where('slug', 'LIKE', $slug.'%');

        // check if the ID is not null
        if (!is_null($id)) {
            $query->where('id', '!=', $id);
        }

        // execute the query and count the results
        $count = count($query->get());

        return ($count > 0) ? $slug.'-'.$count : $slug;
    }

    /**
     * @param $post
     * @return mixed
     */
    public function validatePost($post)
    {

    }
}
