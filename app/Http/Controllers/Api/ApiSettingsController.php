<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\Setting\SettingRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class ApiSettingsController extends ApiController
{
    protected $settings;
    protected $allowedSettings = ['title', 'description', 'installed', 'post_per_page',
        'cover_url', 'logo_url', 'google_analytics', 'disqus', 'theme'];

    public function __construct(SettingRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function getSettings(Request $request)
    {
        $fields = $request->input('fields');

        // check multiple fields to be fetched
        if (strpos($fields, ',')) {
            // make this an array
            $fieldsOnRequest = explode(',', $fields);

            // get the fields from the database
            $settings = $this->settings->get($fieldsOnRequest);

            // return
            return $this->respond(['settings' => $settings]);
        }

        // single field only
        $settings = $this->settings->get($fields);

        // return
        return $this->respond(['settings' => $settings]);
    }

    public function saveSettings(Request $request)
    {
        $requests = $request->all();

        // check first if there are requests
        if ($requests) {
            // save to array the fields that are inputted
            $settingFields = [];

            // loop
            foreach ($requests as $field => $value) {
                // check first if there's a value
                if ($field == 'token') {
                    continue;
                }

                // check if first if the given field is valid
                if (!in_array($field, $this->allowedSettings)) {
                    // stop this loop and return an error to the user
                    return $this->setStatusCode(self::BAD_REQUEST)
                        ->respondWithError(['message' => 'Field "'.$field.'" is invalid.']);
                }

                // save the settings
                $setting = $this->settings->save($field, $value);

                // check if it returned something
                if ($setting) {
                    // good to go, get the field and put in the setting fields array
                    array_push($settingFields, $field);
                }
            }

            // return the results
            return $this->respond([
                'settings' => $this->settings->get($settingFields)]);
        }

        // no request, send an error message
        return $this->setStatusCode(self::BAD_REQUEST)
            ->respondWithError(['message' => 'No fields to fetch.']);
    }

    public function themes()
    {
        $themeLists = [];
        $themesPath = base_path('themes');
        $themes = array_diff(scandir($themesPath), array('..', '.'));

        // check contents of the theme
        foreach ($themes as $key => $theme) {
            $themeDirectory = scandir($themesPath.'/'.$theme);
            if (in_array('theme.json', $themeDirectory)) {
                // get the contents of the theme.json
                $content = json_decode(
                    file_get_contents($themesPath.'/'.$theme.'/theme.json'), true);
                array_push($themeLists, $content);
            }
        }

        return $this->respond(['themes' => $themeLists]);
    }
}
