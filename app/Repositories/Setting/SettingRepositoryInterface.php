<?php //-->
namespace Journal\Repositories\Setting;

/**
 * Interface SettingRepositoryInterface
 * @package Journal\Repositories\Setting
 */
interface SettingRepositoryInterface
{
    /**
     * @param $setting
     * @param $value
     * @return mixed
     */
    public function save($setting, $value);

    /**
     * @param $setting
     * @return mixed
     */
    public function get($setting);
}
