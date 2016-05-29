<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use Journal\Repositories\Settings\DbSettingsRepository;
use Journal\Support\MetaGenerator;

/**
 * Class BlogController
 * @package Journal\Http\Controllers\Blog
 */
class BlogController extends Controller
{
    /**
     * Settings that is needed to be fetched from the database.
     */
    const SETTING_FIELDS = 'blog_title,blog_description,theme_template,post_per_page,logo_url,cover_url,simple_pagination';

    /**
     * Settings that are fetched from the database.
     */
    protected $blogSettings = [];

    /**
     * Number of posts to be shown in the home, tags and author pages.
     */
    protected $postPerPage = 10;

    /**
     * Type of pagination that we're going to use. This pagination is provided
     * by Laravel's Eloquent.
     */
    protected $simplePagination = false;

    /**
     * The theme that will be used to render the blog pages.
     */
    protected $theme = 'casper';

    /**
     * BlogController constructor.
     */
    public function __construct()
    {
        $settings = new DbSettingsRepository();

        // set the blog settings
        $this->blogSettings = $settings->get(self::SETTING_FIELDS, true);

        // set the theme
        $this->theme = $this->blogSettings['theme_template'];

        // set the post per page
        $this->postPerPage = $this->blogSettings['post_per_page'];
    }

    /**
     * Returns the name of the class based on the page being rendered.
     *
     * @param $template
     * @return string
     */
    public function bodyClass($template)
    {
        // check if the template is an index
        if ($template == 'index') {
            return 'home-template';
        }

        return $template . '-template';
    }

    /**
     * Render the 404 page.
     *
     * @return mixed
     */
    public function fourOhFourPage()
    {
        // TODO: check if the template provided a 404 page
        return view('errors.404');
    }

    /**
     * Fetches the value of the given name of the setting.
     *
     * @param $key
     * @return mixed
     */
    public function getSetting($key)
    {
        if (empty($key)) {
            return false;
        }

        // loop the settings to get what we need
        foreach ($this->blogSettings as $b => $setting) {
            if ($setting->name == $key) {
                return $setting->value;
            }
        }

        return false;
    }

    /**
     * Just a wrapper for the view() helper.
     *
     * @param  $template
     * @param  $data
     * @return View
     */
    public function loadThemeTemplate($template, $data = [])
    {
        // setup the path of the where the themes are located
        view()->addLocation(base_path('themes'));

        // generate the meta
        $data['journal_head'] = $this->meta($data, $template);

        // set the body class
        $data['body_class'] = $this->bodyClass($template);

        return view($this->theme . '.' . $template, $data);
    }

    /**
     * Generates the meta data for a given page
     *
     * @param  object|array|null $data
     * @param  string|null $type
     * @return array
     */
    protected function meta($data = null, $type = null)
    {
        // instantiate the meta generator class
        $metaGenerator = new MetaGenerator();

        // check the type given based on the name of the template
        if (!is_null($type)) {
            switch ($type) {
                case 'author' :
                    // get the data for author
                    $data = $data['author'];
                    break;
                case 'post' :
                    $data = $data['post'];
                    break;
                case 'tag' :
                    // get the data for author
                    $data = $data['tag'];
                    break;
                default:
                    // let's just make it null so it will render the default metas
                    $type = null;
                    break;
            }
        }

        // generate and return the data
        return $metaGenerator
            ->generate($data, $type)
            ->withTemplate();
    }
}
