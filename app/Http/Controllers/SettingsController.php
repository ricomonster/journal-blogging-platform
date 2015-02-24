<?php //-->
namespace Journal\Http\Controllers;

use Journal\Repositories\Settings\SettingRepositoryInterface;

class SettingsController extends Controller
{
    protected $settings;

    public function __construct(SettingRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function appearance()
    {
        return view('admin.settings.appearance', [
            'settings' => $this->settings->get([
                'blog_cover', 'blog_logo', 'theme', 'theme_name']),
            'themes' => $this->themes()
        ]);
    }

    public function index()
    {
        return view('admin.settings.index', [
            'settings' => $this->settings->get([
                'blog_title', 'blog_description', 'post_per_page'])
        ]);
    }

    public function services()
    {

    }
}
