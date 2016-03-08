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
        return Post::create([
            'author_id'     => $post->author_id,
            'title'         => $post->title,
            'content'       => $post->content,
            'cover_image'   => $post->cover_image,
            'slug'          => $post->slug,
            'status'        => $post->status,
            'published_at'  => $post->published_at
        ]);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return Post::where('active', '=', 1)->get();
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
        return Post::where('id', '=', $id)
            ->where('active', '=', 1)
            ->first();
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return Post::where('slug', '=', $slug)
            ->where('active', '=', 1)
            ->first();
    }

    /**
     * @param $post
     * @return mixed
     */
    public function update($post)
    {

    }

    /**
     * @param $id
     * @return mixed
     */
    public function setToInactive($id)
    {

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

        return $slug.'-'.$count;
    }

    /**
     * @param $post
     * @return mixed
     */
    public function validatePost($post)
    {

    }
}
