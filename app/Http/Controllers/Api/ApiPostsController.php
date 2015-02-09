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
     * Handles request for slug generation
     *
     * @return mixed
     */
    public function generateSlug(PostRepositoryInterface $posts)
    {
        $string = Input::get('string');
        $id     = Input::get('id');

        // generate slug
        $slug = $this->createSlug($string, $id, $posts);

        return $this->respond(array(
            'data' => array('slug' => $slug)));
    }

    /**
     * Returns all posts saved
     *
     * @return mixed
     */
    public function getAllPosts(PostRepositoryInterface $posts)
    {
        // get all posts
        $posts = $posts->all();

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
    public function getPost(PostRepositoryInterface $posts)
    {
        $id = Input::get('post_id');
        // get the post
        $post = $posts->findById($id);

        return $this->respond(array(
            'data' => array(
                'post' => $post->toArray())));
    }

    /**
     * Update and creates a post
     *
     * @return mixed
     */
    public function savePost(PostRepositoryInterface $postsRepository, TagRepositoryInterface $tagsRepository)
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
        $slug = (isset($slug) && !empty($slug)) ? $slug : $this->createSlug($title, $id, $postsRepository);

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
            $post = $postsRepository->update($id, $authorId, $title, $content, $slug, $status, $tagsId);

            // return a response
            return $this->respond(array(
                'data' => array(
                    'message' => 'Updated!',
                    'post' => $post->toArray())));
        }

        // create post
        $post = $postsRepository->create($authorId, $title, $content, $slug, $status, $publishDate, $tagsId);

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
    protected function createSlug($title, $id, $posts)
    {
        $id = (empty($id) || $id == 0) ? null : $id;
        return $posts->createSlug($title, $id);
    }
}
