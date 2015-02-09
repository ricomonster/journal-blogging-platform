<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Settings\SettingRepositoryInterface;
use Input;

class ApiSettingsController extends ApiController
{
    public function updateGeneralSettings(SettingRepositoryInterface $settingsRepository)
    {
        $title          = Input::get('blog_title');
        $description    = Input::get('blog_description');
        $postPerPage    = Input::get('post_per_page');

        // save data
        $settingsRepository->create('blog_title', $title);
        $settingsRepository->create('blog_description', $description);
        $settingsRepository->create('post_per_page', $postPerPage);

        // return results
        return $this->respond(['data' => [
            'settings' => $settingsRepository->get([
                'blog_title', 'blog_description', 'post_per_page'])]]);
    }

    public function uploader()
    {

    }
}
