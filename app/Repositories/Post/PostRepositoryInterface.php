<?php //-->
namespace Journal\Repositories\Post;

/**
 * Interface PostRepositoryInterface
 * @package Journal\Repositories\Post
 */
interface PostRepositoryInterface
{
    /**
     * @param $authorId
     * @param $title
     * @param $markdown
     * @param $featuredImage
     * @param $slug
     * @param $status
     * @param $publishedAt
     * @param $tagIds
     * @return \Journal\Post
     */
    public function create($authorId, $title, $markdown, $featuredImage, $slug,
        $status, $publishedAt, $tagIds);

    /**
     * @return \Journal\Post
     */
    public function all();

    /**
     * @param $id
     * @return \Journal\Post
     */
    public function findById($id);

    /**
     * @param $slug
     * @return \Journal\Post
     */
    public function findBySlug($slug);

    /**
     * @param $numberOfPosts
     * @return \Journal\Post
     */
    public function getBlogPosts($numberOfPosts);

    /**
     * @param $authorId
     * @param $numberOfPosts
     * @return \Journal\Post
     */
    public function getPostsByAuthor($authorId, $numberOfPosts);

    /**
     * @param $id
     * @param $authorId
     * @param $title
     * @param $markdown
     * @param $featuredImage
     * @param $slug
     * @param $status
     * @param $publishedAt
     * @param $tagIds
     * @return \Journal\Post
     */
    public function update($id, $authorId, $title, $markdown, $featuredImage,
        $slug, $status, $publishedAt, $tagIds);

    /**
     * @param $id
     * @return void
     */
    public function setPostInactive($id);

    /**
     * @param $data
     * @param $id
     * @return \Illuminate\Support\MessageBag
     */
    public function validatePost($data, $id);

    /**
     * @param $string
     * @param $id
     * @return string
     */
    public function validateSlug($string, $id = null);
}
