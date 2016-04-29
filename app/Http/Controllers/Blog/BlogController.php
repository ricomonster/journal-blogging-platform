<?php //-->
namespace Journal\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use Journal\Repositories\Settings\SettingsRepositoryInterface;

/**
 * Class BlogController
 * @package Journal\Http\Controllers\Blog
 */
class BlogController extends Controller
{
    /**
     * Settings that is needed to be fetched from the database.
     */
    const SETTING_FIELDS = 'blog_title,blog_description,theme_template,post_per_page,logo_url,cover_url';

    /**
     * Settings that are fetched from the database.
     */
    protected $blogSettings = [];

    /**
     * Number of posts to be shown in the home, tags and author pages.
     */
    protected $postPerPage = 10;

    /**
     * The theme that will be used to render the blog pages.
     */
    protected $theme = 'casper';

    public function __construct(SettingsRepositoryInterface $settings)
    {
        // set the blog settings
        $this->blogSettings = $settings->get(self::SETTING_FIELDS, true);

        // set the theme
        $this->theme = $this->blogSettings['theme_template'];

        // set the post per page
        $this->postPerPage = $this->blogSettings['post_per_page'];
    }

    public function bodyClass($template)
    {
        // check if the template is an index
        if ($template == 'index') {
            return 'home-template';
        }

        return $template . '-template';
    }

    public function fourOhFourPage()
    {
        // TODO: check if the template provided a 404 page
        return view('vendor.404');
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

        // set the body class
        $data['body_class'] = $this->bodyClass($template);

        // // set the cover url
        // $data['cover_url'] = (empty($this->blogSettings['cover_url'])) ?
        //     '' : $this->blogSettings['cover_url'];

        // // set the logo url
        // $data['logo_url'] = $this->blogSettings['logo_url'];

        return view($this->theme . '.' . $template, $data);
    }

    public function meta()
    {

    }
}
