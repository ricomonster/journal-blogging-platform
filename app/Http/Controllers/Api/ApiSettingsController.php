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

            // check if the data to be saved is the theme
            if ($field == 'theme_template') {
                $result = $this->getThemeSettings($value);

                // now save
                $settings[] = $this->settings->save($field, $value);

                // get the pagination settings and save
                $this->settings->save('simple_pagination', $result['simple_pagination']);
                continue;
            }

            // check if the field to be updated is navigation
            if ($field == 'navigation') {
                // make it json so we can save it to the database
                $navigation = json_encode($value);

                // save it
                $settings[] = $this->settings->save($field, $navigation);
                continue;
            }

            $settings[] = $this->settings->save($field, $value);
        }

        return $this->respond([
            'settings' => $settings]);
    }

    /**
     * API endpoint to get and return the list of available themes.
     *
     */
    public function themes()
    {
        return $this->respond([
            'themes' => $this->getInstalledThemes()
        ]);
    }

    protected function getInstalledThemes()
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

        return $themeLists;
    }

    /**
     * Reads the content of the theme.json of the given theme
     *
     * @param string
     * @return array
     */
    protected function getThemeSettings($theme)
    {
        $settings = [];
        $themes = $this->getInstalledThemes();

        // find the theme
        foreach ($themes as $k => $t) {
            if ($t['theme_name'] == $theme) {
                $settings = $t;
                break;
            }
        }

        return $settings;
    }
}
