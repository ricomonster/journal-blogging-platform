<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Settings\SettingRepositoryInterface;
use Input, Request;

class ApiSettingsController extends ApiController
{
    protected $uploadPath;
    protected $uploadUrl;

    public function __construct()
    {
        $this->uploadPath   = public_path('uploads/user');
        $this->uploadUrl    = Request::root().'/uploads/user';
    }

    public function updateGeneralSettings(SettingRepositoryInterface $settingsRepository)
    {
        $title          = Input::get('blog_title');
        $description    = Input::get('blog_description');
        $postPerPage    = Input::get('post_per_page');

        // save data
        $settingsRepository->set('blog_title', $title);
        $settingsRepository->set('blog_description', $description);
        $settingsRepository->set('post_per_page', $postPerPage);

        // return results
        return $this->respond(['data' => [
            'settings' => $settingsRepository->get([
                'blog_title', 'blog_description', 'post_per_page'])]]);
    }

    public function uploader(SettingRepositoryInterface $settingsRepository)
    {
        $files          = Input::file('files');
        $settingName    = Input::get('setting_name');
        $imageUrl       = Input::get('image_url');

        // check if setting name is set
        if (empty($settingName)) {
            return $this->setStatusCode(400)
                ->respondWithError('You request cannot be processed. Please try again later.');
        }

        // check if there's a file
        if (empty($files)) {
            // image is just a url, update the field
            $settingsRepository->set($settingName, $imageUrl);

            return $this->respond(['data' => [
                'settings' => $settingsRepository->get($settingName)
            ]]);
        }

        // upload image
        // get the file name
        $filename = $files->getClientOriginalName();
        // upload
        $files->move($this->uploadPath, $filename);

        // validate if file is uploaded
        if (!Input::hasFile('files')) {
            // send error message
            return $this->setStatusCode(400)
                ->respondWithError($filename.' could not be uploaded. Please try again later');
        }

        // file uploaded is uploaded
        // prep url of the file
        $fileUrl = $this->uploadUrl.'/'.rawurlencode($filename);

        // save
        $settingsRepository->set($settingName, $fileUrl);

        // return
        return $this->respond(['data' => [
            'settings' => $settingsRepository->get($settingName)
        ]]);
    }
}
