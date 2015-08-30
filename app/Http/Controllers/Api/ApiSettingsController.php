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
}
