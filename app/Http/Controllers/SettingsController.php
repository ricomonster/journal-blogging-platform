<?php //-->
namespace Journal\Http\Controllers;

use Journal\Repositories\Settings\SettingRepositoryInterface;

class SettingsController extends Controller
{
    public function appearance()
    {
        return view('admin.settings.appearance');
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
