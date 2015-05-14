<?php //-->
namespace Journal\Repositories\Settings;

use Journal\Setting;
use Schema;

class DbSettingRepository implements SettingRepositoryInterface
{
    /**
     * Creates or updates a setting
     *
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function set($setting, $value)
    {
        // check first if setting exists
        $result = $this->get($setting);

        // there's a setting set
        if ($result) {
            // update
            $update = Setting::where('key', '=', $setting)->first();
            $update->fill([
                'value' => $value])->save();

            $response = new Setting();
            $response[$update->key] = $update->value;

            return $response;
        }

        // create
        $result = Setting::create([
            'key' => $setting, 'value' => $value]);

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
     * Removes a setting
     *
     * @param $setting
     * @return mixed
     */
    public function delete($setting)
    {

    }

    /**
     * Checks the installation of the platform
     *
     * @return bool
     */
    public function installation()
    {
        // check if there's a setting table or a row with a key of installed
        if (Schema::hasTable('settings') && $this->get('installed')) {
            return true;
        }

        return false;
    }
}
