<?php //-->
namespace Journal\Repositories\Settings;

/**
 * Interface SettingsRepositoryInterface
 * @package Journal\Repositories\Settings
 */
interface SettingsRepositoryInterface
{
    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function save($name, $value);

    /**
     * @param $settings
     * @return mixed
     */
    public function get($settings);
}
