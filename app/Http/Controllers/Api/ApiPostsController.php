<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Posts\PostRepositoryInterface;
use Journal\Repositories\Tags\TagRepositoryInterface;
use Input;

/**
 * Class ApiPostController
 * @package Journal\Core\Controllers\Api
 */
class ApiPostsController extends ApiController
{
    /**
     * The post repository implementation
     *
     * @var PostRepositoryInterface
     */
    protected $posts;

    /**
     * Creates a new API Posts Controller
     *
     * @param PostRepositoryInterface $posts
     */
    public function __construct(PostRepositoryInterface $posts)
    {
        $this->posts = $posts;
    }

    /**
     * Checks and creates a slug
     *
     * @return mixed
     */
    public function generateSlug()
    {
        $string = Input::get('string');
        $id     = Input::get('id');

        // generate slug
        $slug = $this->createSlug($string, $id);

        return $this->respond(array(
            'data' => array('slug' => $slug)));
    }

    /**
     * Get all posts
     *
     * @return mixed
     */
    public function getAllPosts()
    {
        // get all posts
        $posts = $this->posts->all();

        // return
        return $this->respond(array(
            'data' => array(
                'posts' => $posts->toArray())));
    }

    /**
     * Get a specific post using its id
     *
     * @return mixed
     */
    public function getPost()
    {
        $id = Input::get('id');
        // check if id is set
        if (empty($id)) {
            return $this->setStatusCode(400)
                ->respondWithError('Please set the post ID.');
        }

        // get the post
        $post = $this->posts->findById($id);

        // check if post exists
        if (empty($post)) {
            return $this->setStatusCode(400)
                ->respondWithError('Post does not exists.');
        }

        return $this->respond([
            'data' => [
                'post' => $post->toArray()]]);
    }

    /**
     * Creates or updates a post
     *
     * @param TagRepositoryInterface $tagsRepository
     * @return mixed
     */
    public function savePost(TagRepositoryInterface $tagsRepository)
    {
        // prepare the variables
        $tagsId = [];

        $id             = Input::get('post_id');
        $authorId       = Input::get('author_id');
        $title          = Input::get('title');
        $content        = Input::get('content');
        $slug           = Input::get('slug');
        $status         = Input::get('status');
        $publishDate    = Input::get('publish_date');
        $tags           = Input::get('tags');

        // check if title is set
        $title = (isset($title) && !empty($title)) ? $title : 'Untitled';
        // check if slug is set
        $slug = (isset($slug) && !empty($slug)) ? $slug : $this->createSlug($title, $id);

        // check if there are tags
        if (!empty($tags)) {
            // set the tags to an array
            $tagsArray = explode(',', $tags);
            // create the tags and returns the id
            $tagsId = $tagsRepository->set($tagsArray);
        }

        // check if id of the post is set
        if (isset($id) && $id != 0) {
            // update post
            $post = $this->posts->update($id, $authorId, $title, $content, $slug, $status, $tagsId);

            // return a response
            return $this->respond(array(
                'data' => array(
                    'message' => 'Updated!',
                    'post' => $post->toArray())));
        }

        // create post
        $post = $this->posts->create($authorId, $title, $content, $slug, $status, $publishDate, $tagsId);

        // return a response
        return $this->respond(array(
            'data' => array(
                'message'   => 'Success!',
                'post'      => $post->toArray())));
    }

    /**
     * Creates a slug
     *
     * @param $title
     * @param $id
     * @return mixed
     */
    protected function createSlug($title, $id)
    {
        $id = (empty($id) || $id == 0) ? null : $id;
        return $this->posts->createSlug($title, $id);
    }
}
