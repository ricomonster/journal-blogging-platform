<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Settings\SettingRepositoryInterface;
use Input, Request;

/**
 * Class ApiSettingsController
 * @package Journal\Http\Controllers\Api
 */
class ApiSettingsController extends ApiController
{
    /**
     * The upload directory
     *
     * @var string
     */
    protected $uploadPath;

    /**
     * The url of the upload directory
     *
     * @var string
     */
    protected $uploadUrl;

    /**
     * The settings repository implementation
     *
     * @var SettingRepositoryInterface
     */
    protected $settings;

    /**
     * Creates a new API Settings Controller
     *
     * @param SettingRepositoryInterface $settings
     */
    public function __construct(SettingRepositoryInterface $settings)
    {
        // set the directory paths
        $this->uploadPath   = public_path('uploads/user');
        $this->uploadUrl    = Request::root().'/uploads/user';

        $this->settings = $settings;
    }

    /**
     * Update general settings (blog title, blog description, post per page)
     *
     * @return mixed
     */
    public function updateGeneralSettings()
    {
        $title          = Input::get('blog_title');
        $description    = Input::get('blog_description');
        $postPerPage    = Input::get('post_per_page');

        // save data
        $this->settings->set('blog_title', $title);
        $this->settings->set('blog_description', $description);
        $this->settings->set('post_per_page', $postPerPage);

        // return results
        return $this->respond(['data' => [
            'settings' => $this->settings->get([
                'blog_title', 'blog_description', 'post_per_page'])]]);
    }

    /**
     * Updates the theme of the blog
     *
     * @return mixed
     */
    public function updateTheme()
    {
        $theme = Input::get('theme');

        // check if theme is set
        if (empty($theme)) {
            return $this->setStatusCode(400)
                ->respondWithError('Please select a theme for your blog.');
        }

        // save
        $this->settings->set('theme', $theme);
        $this->settings->set('theme_name', ucfirst($theme));

        return $this->respond(['data' => [
            'message'   => 'You have successfully applied your theme',
            'settings'  => $this->settings->get(['theme', 'theme_name'])
        ]]);
    }

    /**
     * Uploads a file for the blog (cover or logo)
     *
     * @return mixed
     */
    public function uploader()
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
            $this->settings->set($settingName, $imageUrl);

            return $this->respond(['data' => [
                'settings' => $this->settings->get($settingName)
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
        $this->settings->set($settingName, $fileUrl);

        // return
        return $this->respond(['data' => [
            'settings' => $this->settings->get($settingName)
        ]]);
    }
}
