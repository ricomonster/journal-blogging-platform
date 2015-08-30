<?php //-->
namespace Journal\Repositories\Setting;

use DB;

class DbSettingRepository implements SettingRepositoryInterface
{
    /**
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function save($setting, $value)
    {
        // check if setting exists
        $existing = DB::table('settings')
            ->where('setting', $setting)
            ->first();

        if ($existing) {
            // update
            DB::table('settings')
                ->where('setting', $setting)
                ->update(['value' => $value]);

            // get the settings
            return $this->get($setting);
        }

        // setting does not exists
        // create this
        DB::table('settings')
            ->insert([
                'setting' => $setting,
                'value' => $value]);

        // get the settings
        return $this->get($setting);
    }

    /**
     * @param $setting
     * @return mixed
     */
    public function get($setting)
    {
        if (is_array($setting)) {
            $settings = [];

            // loop
            foreach ($setting as $key => $field) {
                $result = $this->get($field);
                if (!empty($result)) {
                    $settings[key($result)] = current($result);
                }
            }

            return $settings;
        }

        // single field is just needed
        $settings = DB::table('settings')
            ->where('setting', $setting)
            ->first();

        return $settings ? [$settings->setting => $settings->value] : [$setting => null];
    }
}
