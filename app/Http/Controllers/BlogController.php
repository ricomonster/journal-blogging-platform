<?php namespace Journal\Http\Controllers;

use Journal\Repositories\Posts\PostRepositoryInterface;
use Journal\Repositories\Settings\SettingRepositoryInterface;
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
     * @param PostRepositoryInterface $posts
     * @param SettingRepositoryInterface $settings
     */
    public function __construct(PostRepositoryInterface $posts, SettingRepositoryInterface $settings)
    {
        // setup the view
        View::addLocation(base_path('themes'));

        // set the repositories
        $this->posts    = $posts;
        $this->settings = $settings;
    }

    /**
     * @return View
     */
    public function index()
    {
        return view($this->theme.'.index', [
            'blog' => $this->settings->get([
                'blog_title', 'blog_description', 'blog_logo', 'blog_cover']),
            'posts' => $this->posts->all()
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
        }

        return view($this->theme.'.post', [
            'blog' => $this->settings->get([
                'blog_title', 'blog_description', 'blog_logo', 'blog_cover']),
            'post' => $post
        ]);
    }

    /**
     * @return View
     */
    public function author()
    {
        return view($this->theme.'.author');
    }
}
