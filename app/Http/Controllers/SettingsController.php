<?php //-->
namespace Journal\Http\Controllers;

use Journal\Repositories\Settings\SettingRepositoryInterface;

class SettingsController extends Controller
{
    public function appearance(SettingRepositoryInterface $settingsRepository)
    {
        return view('admin.settings.appearance', [
            'settings' => $settingsRepository->get([
                'blog_cover', 'blog_logo', 'theme', 'theme_name']),
            'themes' => $this->themes()
        ]);
    }

    public function index(SettingRepositoryInterface $settingsRepository)
    {
        return view('admin.settings.index', [
            'settings' => $settingsRepository->get([
                'blog_title', 'blog_description', 'post_per_page'])
        ]);
    }

    public function services()
    {

    }
}
