<?php //-->
namespace Journal\Http\Controllers;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\Post\PostRepositoryInterface;
use Journal\Repositories\Setting\SettingRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;
use Journal\Repositories\Tag\TagRepositoryInterface;
use Feed;
use View;

/**
 * Class BlogController
 * @package Journal\Http\Controllers
 */
class BlogController extends Controller
{
    /**
     * @var int
     */
    protected $postPerPage = 10;
    /**
     * @var PostRepositoryInterface
     */
    protected $posts;
    /**
     * @var mixed
     */
    protected $settings;
    /**
     * @var TagRepositoryInterface
     */
    protected $tags;
    /**
     * @var string
     */
    protected $theme = 'casper';
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
        // setup the path of the where the themes are located
        View::addLocation(base_path('themes'));

        // repositories
        $this->posts    = $posts;
        $this->tags     = $tags;
        $this->users    = $users;

        // set the settings
        $this->settings = $settings->get(['title', 'description', 'cover_url', 'logo_url',
            'google_analytics', 'theme']);
        // set the theme to be loaded
        $this->theme = $this->settings['theme'];
    }

    /**
     * @param $slug
     * @return View
     */
    public function author($slug)
    {
        // check if the slug exists
        if (!$slug || empty($slug)) {
            // redirect to 404 page
            return $this->fourOhFour();
        }

        // get the user
        $user = $this->users->findBySlug($slug);

        // user does not exists
        if (empty($user)) {
            return $this->fourOhFour();
        }

        // attach the settings to the view
        $data = $this->settings;

        // set the body class
        $data['body_class'] = 'author-page '.$user->slug;
        // temporary list of posts
        $data['author'] = $user;
        // get the posts of the author
        $data['posts'] = $this->posts->getPostsByAuthor($user->id, $this->postPerPage);
        // set the head
        $data['journal_head'] = $this->meta('author', $user);

        return view($this->theme.'.author', $data);
    }

    /**
     * @return View
     */
    public function index()
    {
        // attach the settings to the view
        $data = $this->settings;

        // set the body class
        $data['body_class'] = 'home-page';
        // temporary list of posts
        $data['posts'] = $this->posts->getBlogPosts($this->postPerPage);
        // set the head
        $data['journal_head'] = $this->meta();

        return view($this->theme.'.index', $data);
    }

    /**
     * @param $slug
     * @param Request $request
     * @return View
     */
    public function post($slug, Request $request)
    {
        // check if the request is just to preview the post

        // check if parameter is empty
        if (empty($slug)) {
            return $this->fourOhFour();
        }

        // check if post exists
        $post = $this->posts->findBySlug($slug);

        if (empty($post)) {
            return $this->fourOhFour();
        }

        // attach the settings to the view
        $data = $this->settings;

        // set the body class
        $data['body_class'] = 'post-page';
        // set the post
        $data['post'] = $post;
        // set the head
        $data['journal_head'] = $this->meta('post', $post);

        return view($this->theme.'.post', $data);
    }

    /**
     * Generates the RSS feed for the blog
     * @return mixed
     */
    public function rss()
    {
        // attach the settings to the view
        $settings   = $this->settings;

        // create instance of the feed maker
        $feed = Feed::make();

        // set feed cache
        $feed->setCache(60, env('APP_KEY', 'SomeRandomString'));

        // check if there's a feed cache
        if (!$feed->isCached()) {
            // get the posts
            $posts = $this->posts->getBlogPosts($this->postPerPage);

            // set the feed
            $feed->title        = $settings['title'];
            $feed->description  = $settings['description'];
            //$feed->logo = null;
            $feed->link     = url('rss');
            $feed->pubdate  = $posts[0]->published_at;
            $feed->lang     = 'en';
            $feed->setDateFormat('timestamp');
            $feed->setShortening(true);
            $feed->setTextLimit(100);

            foreach ($posts as $post) {
                // set the feed content
                $feed->add(
                    $post->title,
                    $post->author->name,
                    $post->permalink,
                    $post->published_at,
                    null,
                    htmlentities(markdown($post->markdown)));
            }
        }

        // render the rss
        return $feed->render('atom');
    }

    /**
     * @param $slug
     * @return View
     */
    public function tags($slug)
    {
        // check if parameter is empty
        if (empty($slug)) {
            return $this->fourOhFour();
        }

        // get the tag
        $tag = $this->tags->findBySlug($slug);

        // check if the tag exists
        if (empty($tag)) {
            return $this->fourOhFour();
        }

        // attach the settings to the view
        $data = $this->settings;

        // set the body class
        $data['body_class'] = 'tag-page '.$tag->slug;
        // set the tag
        $data['tag'] = $tag;
        // get the posts under this tag
        $data['posts'] = $this->tags->getPosts($tag->id, $this->postPerPage);
        // set the head
        $data['journal_head'] = $this->meta('tag', $tag);

        return view($this->theme.'.tag', $data);
    }

    /**
     * @return View
     */
    protected function fourOhFour()
    {
        // check if the theme provided a 404 page
        if (view()->exists($this->theme.'.404')) {
            // load it
            return view($this->theme.'.404');
        }

        // use the default one
        return view('errors.404');
    }

    /**
     * @param null $type
     * @param null $content
     * @return View
     */
    protected function meta($type = null, $content = null)
    {
        // get the settings of the blog
        $settings = $data = $this->settings;

        // set the default
        $meta = [
            'siteUrl'       => url('/'),
            'title'         => $settings['title'],
            'type'          => 'website',
            'description'   => $settings['description'],
            'url'           => url('/'),
            'imageUrl'      => (strpos($settings['cover_url'], 'http')) ?
                $settings['cover_url'] : url($settings['cover_url'])];

        // check first what type of page is being accessed
        if (!is_null($type)) {
            if ($type == 'author') {
                // override the default contents
                $meta['title']          = $content->name.' - '.$settings['title'];
                $meta['type']           = 'profile';
                $meta['description']    = null;
                $meta['url']            = url('author/'.$content->slug);
                $meta['imageUrl']       = (strpos($content->cover_url, 'http')) ?
                    $content->cover_url : url($content->cover_url);
            }

            if ($type == 'post') {
                // override default contents
                $meta['title']          = $content->title;
                $meta['type']           = 'article';
                $meta['description']    = markdown($content->markdown, true, 20);
                $meta['url']            = url($content->slug);
                $meta['imageUrl']       = null;

                // let's check first if there's a post featured image
                if ($content->featured_image) {
                    // check first if the featured image is a link else attach
                    // the url of the site to the value
                    $meta['imageUrl'] = (strpos($content->featured_image, 'http')) ?
                        $content->featured_image : url($content->featured_image);
                }
            }

            if ($type == 'tag') {
                // override the default contents
                $meta['title'] = $content->name.' - '.$settings['title'];
                $meta['type'] = 'website';
                $meta['description'] = null;
                $meta['url'] = url('tag/'.$content->slug);
            }
        }

        $data['meta'] = [
            ['rel' => 'canonical', 'href' => $meta['url']],
            ['attribute' => 'name', 'value' => 'referrer', 'content' => 'origin'],
            ['attribute' => 'property', 'value' => 'og:site_name', 'content' => $settings['title']],
            ['attribute' => 'property', 'value' => 'og:type', 'content' => $meta['type']],
            ['attribute' => 'property', 'value' => 'og:title', 'content' => $meta['title']],
            ['attribute' => 'property', 'value' => 'og:description', 'content' => $content['description']],
            ['attribute' => 'property', 'value' => 'og:url', 'content' => $meta['url']],
            ['attribute' => 'property', 'value' => 'og:image', 'content' => $meta['imageUrl']],
            ['attribute' => 'name', 'value' => 'twitter:card', 'content' => 'summary'],
            ['attribute' => 'name', 'value' => 'twitter:title', 'content' => $meta['title']],
            ['attribute' => 'name', 'value' => 'twitter:description', 'content' => $content['description']],
            ['attribute' => 'name', 'value' => 'twitter:url', 'content' => $meta['url']],
            ['attribute' => 'name', 'value' => 'twitter:image:src', 'content' => $meta['imageUrl']],
            ['attribute' => 'name', 'value' => 'generator', 'content' => 'Journal v1.6.0']];

        return view('vendor.meta', $data);
    }
}
