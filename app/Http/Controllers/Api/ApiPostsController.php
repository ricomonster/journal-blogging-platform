<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\Post\PostRepositoryInterface;
use Journal\Repositories\Tag\TagRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

/**
 * Class ApiPostsController
 * @package Journal\Http\Controllers\Api
 */
class ApiPostsController extends ApiController
{
    /**
     * @var PostRepositoryInterface
     */
    protected $posts;

    /**
     * @param PostRepositoryInterface $posts
     * @param TagRepositoryInterface $tags
     */
    public function __construct(PostRepositoryInterface $posts, TagRepositoryInterface $tags)
    {
        // set the JWT middleware
        $this->middleware('jwt.auth', [
            'except' => ['all', 'checkSlug', 'getPost']]);

        $this->posts = $posts;
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        // get all posts
        $posts = $this->posts->all();

        // return the posts
        return $this->respond([
            'posts' => $posts->toArray()]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function checkSlug(Request $request)
    {
        $slug   = $request->input('slug');
        $id     = $request->input('post_id');

        // check if slug is empty or set
        if (!$slug || empty($slug)) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'Slug is required.']);
        }

        // check the slug if it is valid
        $slug = $this->posts->validateSlug($slug, $id);

        // return
        return $this->respond(['slug' => $slug]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deletePost(Request $request)
    {
        $postId = $request->input('post_id');

        // check if post id is empty
        if (!$postId || empty($postId)) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'Post ID is not set.']);
        }

        // check if post exists
        $post = $this->posts->findById($postId);

        // check if post exists
        if (empty($post)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => 'Post not found.']);
        }

        // delete post
        $this->posts->setPostInactive($post->id);

        return $this->respond(['error' => false]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPost(Request $request)
    {
        $id     = $request->input('post_id');
        $slug   = $request->input('slug');

        // check if post_id is set
        if ($id && !empty($id)) {
            // get the post
            $post = $this->posts->findById($id);

            // check if the post exists
            if (empty($post)) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError(['message' => 'Post not found.']);
            }

            // return the post
            return $this->respond([
                'post' => $post->toArray()]);
        }

        // check if slug is set
        if ($slug && !empty($slug)) {
            // get the post
            $post = $this->posts->findBySlug($slug);

            // check if the post exists
            if (empty($post)) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError(['message' => 'Post not found.']);
            }

            // return the post
            return $this->respond([
                'post' => $post->toArray()]);
        }

        // return an error
        return $this->setStatusCode(self::BAD_REQUEST)
            ->respondWithError(['message' => 'Post ID or slug is not set.']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        // check first the slug
        $slug = $this->generateSlug($request->all());

        // check if title is empty
        $title = (empty($request->input('title'))) ?
            'Untitled' : $request->input('title');

        // get the ID of the tags
        $tagIds = $this->generateTags($request->input('tags'));

        // check if post_id is set
        if ($request->input('post_id')) {
            // check if post exists
            $post = $this->posts->findById($request->input('post_id'));

            // check if post exists
            if (empty($post)) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError(['message' => 'Post not found.']);
            }

            // update post
            $post = $this->posts->update(
                $request->input('post_id'),
                $request->input('author_id'),
                $title,
                $request->input('markdown'),
                $request->input('featured_image'),
                $slug,
                $request->input('status'),
                $request->input('published_at'),
                $tagIds);

            // return post
            return $this->respond(['post' => $post->toArray()]);
        }

        // create the post
        $post = $this->posts->create(
            $request->input('author_id'),
            $title,
            $request->input('markdown'),
            $request->input('featured_image'),
            $slug,
            $request->input('status'),
            $request->input('published_at'),
            $tagIds);

        // return
        return $this->respond([
            'post' => $post->toArray()]);
    }

    /**
     * @param $request
     * @return string
     */
    protected function generateSlug($request)
    {
        $postId = (isset($request['post_id'])) ? $request['post_id'] : null;

        // check if there's a slug
        if ($request['slug'] && !empty($request['slug'])) {
            // check if title is set
            if ($request['title'] && !empty($request['title'])) {
                return $this->posts->validateSlug($request['title'], $postId);
            }

            return $this->posts->validateSlug($request['slug'], $postId);
        }

        // check if there's title
        if ($request['title'] && !empty($request['title'])) {
            return $this->posts->validateSlug($request['title'], $postId);
        }

        // no title?
        return $this->posts->validateSlug('Untitled');
    }

    /**
     * @param $tags
     * @return array
     */
    protected function generateTags($tags)
    {
        $tagIds = [];

        // check if there are tags
        if ($tags) {
            // loop
            foreach ($tags as $key => $tag) {
                $tag = $this->tags->create($tag['name'], null, null);
                // get the ID and push it to the array
                array_push($tagIds, $tag->id);
            }
        }

        return $tagIds;
    }
}
