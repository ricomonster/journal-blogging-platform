<?php namespace Journal\Http\Controllers;

use Journal\Repositories\Posts\PostRepositoryInterface;
use Journal\Repositories\Settings\SettingRepositoryInterface;
use Journal\Repositories\Tags\TagRepositoryInterface;
use Journal\Repositories\Users\UserRepositoryInterface;
use View;

/**
 * Class BlogController
 * @package Journal\Http\Controllers
 */
class BlogController extends Controller {
    /**
     * @var string
     */
    protected $theme = 'casper';

    /**
     * @var PostRepositoryInterface
     */
    protected $posts;

    /**
     * @var SettingRepositoryInterface
     */
    protected $settings;

    /**
     * @var TagRepositoryInterface
     */
    protected $tags;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @param PostRepositoryInterface $posts
     * @param SettingRepositoryInterface $settings
     * @param TagRepositoryInterface $tags
     * @param UserRepositoryInterface $users
     */
    public function __construct(PostRepositoryInterface $posts, SettingRepositoryInterface $settings, TagRepositoryInterface $tags, UserRepositoryInterface $users)
    {
        // setup the view
        View::addLocation(base_path('themes'));

        // set the repositories
        $this->posts    = $posts;
        $this->settings = $settings;
        $this->tags     = $tags;
        $this->users    = $users;
    }

    /**
     * @param $slug
     * @return View
     */
    public function author($slug)
    {
        // check if slug is empty or the user doesn't exists
        $user = $this->users->findBySlug($slug);

        if (empty($slug) || empty($user)) {
            // show 404 page
            $this->fourOhFour();
        }

        return view($this->theme.'.author', [
            'author'    => $user,
            'blog'      => $this->settings->get([
                'blog_title', 'blog_description', 'blog_logo', 'blog_cover']),
            'posts'     => $this->posts->getPostsByAuthor($user->id)
        ]);
    }

    /**
     * @return View
     */
    public function index()
    {
        return view($this->theme.'.index', [
            'blog' => $this->settings->get([
                'blog_title', 'blog_description', 'blog_logo', 'blog_cover']),
            'posts' => $this->posts->getBlogPosts()
        ]);
    }

    /**
     * @param $slug
     * @return View
     */
    public function post($slug)
    {
        // get post
        $post = $this->posts->findBySlug($slug);

        // check if there's a slug or post
        if (empty($slug) || empty($post)) {
            // show 404 page
            $this->fourOhFour();
        }

        return view($this->theme.'.post', [
            'blog' => $this->settings->get([
                'blog_title', 'blog_description', 'blog_logo', 'blog_cover']),
            'post' => $post
        ]);
    }

    public function tag($tag)
    {
        // check if tag exists
        $tag = $this->tags->findBySlug(urldecode($tag));

        if (empty($tag)) {
            // show 404 page
            $this->fourOhFour();
        }

        return view($this->theme . '.tag', [
            'blog' => $this->settings->get([
                'blog_title', 'blog_description', 'blog_logo', 'blog_cover']),
            'posts' => $this->posts->getPostsByTag($tag->id),
            'tag'   => $tag
        ]);
    }

    protected function fourOhFour()
    {
        return view($this->theme . '.404');
    }
}
