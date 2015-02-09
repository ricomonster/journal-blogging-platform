<?php //-->
namespace Journal\Repositories\Settings;

/**
 * Interface SettingRepositoryInterface
 */
interface SettingRepositoryInterface
{
    /**
     * Creates a setting
     *
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function create($setting, $value);

    /**
     * Gets all the setting saved in the database
     *
     * @return mixed
     */
    public function all();

    /**
     * Gets a settings
     *
     * @param $setting
     * @return mixed
     */
    public function get($setting);

    /**
     * Update a setting
     *
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function update($setting, $value);

    /**
     * Removes a setting
     *
     * @param $setting
     * @return mixed
     */
    public function delete($setting);
}
