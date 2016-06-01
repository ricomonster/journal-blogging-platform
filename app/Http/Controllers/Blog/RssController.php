<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Blog\BlogController;
use Journal\Repositories\Blog\BlogRepositoryInterface;
use App;
use URL;

/**
 * Class RssController
 * @package Journal\Http\Controllers\Blog
 */
class RssController extends BlogController
{
    /**
     * The blog repository interface instance.
     * @var BlogRepositoryInterface
     */
    protected $blog;

    /**
     * RssController constructor.
     * @param BlogRepositoryInterface $blog
     */
    public function __construct(BlogRepositoryInterface $blog)
    {
        parent::__construct();

        $this->blog = $blog;
    }

    /**
     * @return mixed
     */
    public function page()
    {
        // create the feed
        $feed = App::make('feed');

        // cache the feed for an hour
        // $feed->setCache(60);

        // check if there is cached feed and build new only if is not
        if (!$feed->isCached()) {
            // creating rss feed with our most recent 20 posts
            $posts = $this->blog->blogPosts($this->postPerPage);

            // set your feed's title, description, link, pubdate and language
            $feed->title         = blog_title();
            $feed->description   = blog_description();
            // $feed->logo = 'http://yoursite.tld/logo.jpg';
            $feed->link          = url('rss');
            $feed->pubdate       = $posts[0]->created_at;
            $feed->lang          = 'en';

            $feed->setDateFormat('datetime');
            $feed->setShortening(true);
            $feed->setTextLimit(100);

            foreach ($posts as $post) {
                // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                $feed->add(
                    $post->title,
                    $post->author->name,
                    URL::to($post->slug),
                    date('Y-m-d h:i:s', $post->published_at),
                    excerpt($post),
                    markdown($post->content));
            }
        }

        // first param is the feed format
        // optional: second param is cache duration (value of 0 turns off caching)
        // optional: you can set custom cache key with 3rd param as string
        return $feed->render('atom');
    }
}
