<?php //-->
namespace Journal\Repositories\Settings;

use Journal\Repositories\Settings\SettingsRepositoryInterface;
use Journal\Settings;
use DB;

/**
 * Class DbSettingsRepository
 * @package Journal\Repositories\Settings
 */
class DbSettingsRepository implements SettingsRepositoryInterface
{
    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function save($name, $value)
    {
        // check first it exists
        $exists = DB::table('settings')
            ->where('name', '=', $name)
            ->first();

        // if it already exists, just update the setting
        if (!empty($exists)) {
            // update
            DB::table('settings')
                ->where('name', '=', $name)
                ->update([
                    'value' => $value,
                    'updated_at' => date('Y-m-d h:i:s')
                ]);

            // return the settings
            return $this->get($name);
        }

        // create it
        DB::table('settings')
            ->insert([
                'name' => $name,
                'value' => $value,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ]);

        // return the settings
        return $this->get($name);
    }

    /**
     * @param $settings
     * @return mixed
     */
    public function get($settings)
    {
        // check if the parameter has a comma because this is used to separate
        // settings to be taken
        if (strpos($settings, ',')) {
            $query = [];

            // make the parameter an array
            $settings = explode(',', $settings);

            // loop
            foreach ($settings as $key => $value) {
                $query[] = $value;
            }

            // perform search
            $results = DB::table('settings')
                ->whereIn('name', $query)
                ->get();

            return $results;
        }

        // just a single setting
        $result = DB::table('settings')
            ->where('name', '=', $settings)
            ->first();

        // check if there's a result
        if (empty($result)) {
            // return an empty array
            return [];
        }

        return $result;
    }
}
