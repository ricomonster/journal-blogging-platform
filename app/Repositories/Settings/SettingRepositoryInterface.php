<?php //-->
namespace Journal\Repositories\Settings;

/**
 * Interface SettingRepositoryInterface
 */
interface SettingRepositoryInterface
{
    /**
     * Creates or updates a setting
     *
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function set($setting, $value);

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
     * Removes a setting
     *
     * @param $setting
     * @return mixed
     */
    public function delete($setting);

    /**
     * Checks the installation of the platform
     *
     * @return bool
     */
    public function installation();
}
