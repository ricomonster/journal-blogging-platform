<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\API\ApiController;
use Journal\Repositories\Settings\SettingsRepositoryInterface;

class ApiSettingsController extends ApiController
{
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function get(Request $request)
    {
        $fields = $request->input('fields');

        if (empty($fields)) {
            // fetch all settings and return it
        }

        // get the settings
        $settings = $this->settings->get($fields);

        return $this->respond(['settings' => $settings]);
    }

    public function saveSettings(Request $request)
    {
        $requests = $request->all();

        // check first if there are requests
        if (!$requests) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => 'Request is empty']);
        }

        // save to array the fields that are inputted
        $settings = [];

        // loop
        foreach ($requests as $field => $value) {
            // make sure that the field is not a token
            if ($field == 'token' || $field == 'csrf') {
                continue;
            }

            $settings[] = $this->settings->save($field, $value);
        }

        return $this->respond([
            'settings' => $settings]);
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
