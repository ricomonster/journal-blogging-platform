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
    protected $theme = 'medium';

    /**
     *
     */
    public function __construct()
    {
        // setup the view
        View::addLocation(base_path('themes'));
    }

    /**
     * @param PostRepositoryInterface $postsRepository
     * @param SettingRepositoryInterface $settingsRepository
     * @return View
     */
    public function index(PostRepositoryInterface $postsRepository, SettingRepositoryInterface $settingsRepository)
    {
        return view($this->theme.'.index', [
            'blog' => $settingsRepository->get([
                'blog_title', 'blog_description', 'blog_logo', 'blog_cover']),
            'posts' => $postsRepository->all()
        ]);
    }

    public function post($slug, PostRepositoryInterface $postsRepository, SettingRepositoryInterface $settingsRepository)
    {
        // get post
        $post = $postsRepository->findBySlug($slug);

        // check if there's a slug or post
        if (empty($slug) || empty($post)) {
            // show 404 page
        }

        return view($this->theme.'.post', [
            'blog' => $settingsRepository->get([
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
