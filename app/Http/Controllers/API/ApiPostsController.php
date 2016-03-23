<?php //-->
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\API\ApiController;
use Journal\Repositories\Post\PostRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;

class ApiPostsController extends ApiController
{
    const POST_NO_TITLE = 'Untitled';

    protected $posts;
    protected $users;

    public function __construct(PostRepositoryInterface $posts, UserRepositoryInterface $users)
    {
        $this->posts = $posts;
        $this->users = $users;
    }

    /**
     * Fetches the posts either by giving the post id or not.
     *
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        // check if post_id exists
        if ($request->input('post_id')) {
            // get the post
            $post = $this->posts->findById($request->input('post_id'));

            // check if it exists
            if (empty($post)) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError(['message' => self::POST_NOT_FOUND]);
            }

            // return the post
            return $this->respond([
                'post' => $post->toArray()]);
        }

        // return all posts
        $posts = $this->posts->all();

        return $this->respond([
            'posts' => $posts->toArray()]);
    }

    /**
     * Saves the post
     *
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        $postData = [];

        // check if user ID is set
        if (!$request->input('author_id') ||
            empty($request->input('author_id'))) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => self::AUTHOR_ID_REQUIRED]);
        }

        // check if the user exists
        $user = $this->users->findById($request->input('author_id'));

        if (empty($user)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => self::AUTHOR_NOT_FOUND]);
        }

        // check if the title is empty
        $title = (empty($request->input('title'))) ?
            self::POST_NO_TITLE :
            $request->input('title');

        // prepare the data to be sent as post
        $postData = [
            'author_id'     => $user->id,
            'title'         => $title,
            'content'       => $request->input('content'),
            'cover_image'   => $request->input('cover_image'),
            'slug'          => $request->input('slug'),
            'status'        => $request->input('status'),
            'published_at'  => $request->input('published_at')
        ];

        // check if there's a post_id
        if ($request->input('post_id')) {
            // get the post
            $post = $this->posts->findById($request->input('post_id'));

            // append post id
            $postData['post_id'] = $post->id;

            // check if the post exists
            if (empty($post)) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError(['message' => self::POST_NOT_FOUND]);
            }

            // update
            $post = $this->posts->update($postData);

            // update the post
            return $this->respond([
                'post' => $post->toArray()]);
        }

        // check if published date is set
        $postData['published_at'] = ($request->input('published_at')) ?
            $request->input('published_at') :
            strtotime(date('Y-m-d h:i:s'));

        // create the post
        $post = $this->posts->create($postData);

        return $this->respond([
            'post' => $post->toArray()]);
    }
}
