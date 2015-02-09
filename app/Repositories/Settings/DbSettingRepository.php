<?php //-->
namespace Journal\Repositories\Settings;

use Journal\Setting;

class DbSettingRepository implements SettingRepositoryInterface
{
    /**
     * Creates a setting
     *
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function create($setting, $value)
    {
        $result = Setting::create(array(
            'key' => $setting, 'value' => $value));

        $setting = new Setting();
        $setting[$result->key] = $result->value;

        return $setting;
    }

    /**
     * Gets all the setting saved in the database
     *
     * @return mixed
     */
    public function all()
    {
        $results = Setting::all();

        $setting = new Setting();
        foreach($results as $key => $result) {
            $setting[$result->key] = $result->value;
        }

        return $setting;
    }

    /**
     * Gets a settings
     *
     * @param $setting
     * @return mixed
     */
    public function get($setting)
    {
        // check if setting is array
        if (is_array($setting)) {
            $response = new Setting();
            // get the multiple items
            foreach ($setting as $key => $value) {
                // check if setting exists
                $result = Setting::where('key', '=', $value)->first();
                if (!empty($result)) {
                    // add to the object
                    $response[$result->key] = $result->value;
                }
            }

            return $response;
        }

        // setting is a string
        $result = Setting::where('key', '=', $setting)->first();

        if ($result) {
            $setting = new Setting();
            $setting[$result->key] = $result->value;

            return $setting;
        }

        return false;
    }

    /**
     * Update a setting
     *
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function update($setting, $value)
    {
        // check first if setting exists
        $result = $this->get($setting);

        // there's a setting set
        if ($result) {
            // update
            $update = Setting::where('key', '=', $setting)->first();
            $update->fill(array(
                'value' => $value))->save();

            $response = new Setting();
            $response[$update->key] = $update->value;

            return $response;
        }

        // create
        $response = $this->create($setting, $value);

        return $response;
    }

    /**
     * Removes a setting
     *
     * @param $setting
     * @return mixed
     */
    public function delete($setting)
    {

    }
}
