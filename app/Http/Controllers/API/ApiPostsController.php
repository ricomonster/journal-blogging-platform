<?php //-->
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\API\ApiController;
use Journal\Repositories\Post\PostRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;
use Journal\Repositories\Tag\TagRepositoryInterface;
use Validator;

class ApiPostsController extends ApiController
{
    const POST_NO_TITLE = 'Untitled';

    protected $posts;
    protected $tags;
    protected $users;

    public function __construct(PostRepositoryInterface $posts, TagRepositoryInterface $tags, UserRepositoryInterface $users)
    {
        $this->posts    = $posts;
        $this->tags     = $tags;
        $this->users    = $users;
    }

    /**
     * Performs the action to delete a post.
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $postId = $request->input('post_id');
        $userId = $request->input('user_id');

        // validate
        $errors = $this->validateDeletePost($request->all());

        if (count($errors) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($errors);
        }

        // check if the post exists
        $post = $this->posts->findById($postId);

        if (empty($post)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError([
                    'message' => self::POST_NOT_FOUND]);
        }

        // check if the user exists
        $user = $this->users->findById($userId);

        if (empty($user)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError([
                    'message' => self::USER_NOT_FOUND]);
        }

        // check if the post is owned by the given author
        if ($post->author_id != $user->id) {
            return $this->setStatusCode(self::FORBIDDEN)
                ->respondWithError([
                    'message' => self::UNAUTHORIZED_ACCESS]);
        }

        // delete post
        $this->posts->setToInactive($post->id);

        return $this->respond([
            'error' => false]);
    }

    public function generateSlug(Request $request)
    {
        // check if there's a string given
        if (empty($request->input('string'))) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'String is required.']);
        }

        // check if there's a post id
        $postId = (empty($request->input('post_id'))) ?
            null : $request->input('post_id');

        // generate slug
        $slug = $this->posts->generateSlug($request->input('string'), $postId);

        return $this->respond(['slug' => $slug]);
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

        // set the tags to be sent
        $tagIds = $this->tags->generatePostTags($request->input('tags'));

        // prepare the data to be sent as post
        $postData = [
            'author_id'     => $user->id,
            'title'         => $title,
            'content'       => $request->input('content'),
            'cover_image'   => $request->input('cover_image'),
            'slug'          => $request->input('slug'),
            'status'        => $request->input('status'),
            'published_at'  => $request->input('published_at'),
            'tag_ids'       => $tagIds
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

    /**
     * Validate the data passed to delete a post
     *
     * @param $data
     * @return \Illuminate\Support\MessageBag
     */
    protected function validateDeletePost($data)
    {
        // prepare the rules
        $rules = [
            'post_id'   => 'required',
            'user_id'   => 'required'
        ];

        // set the custom messages
        $messages = [
            'post_id.required'  => self::POST_ID_REQUIRED,
            'user_id.required'  => self::USER_ID_REQUIRED
        ];

        // validate
        $validator = Validator::make($data, $rules, $messages);
        $validator->passes();

        // return errors if there are
        return $validator->errors();
    }
}
