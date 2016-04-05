<?php //-->
namespace Journal\Support;

class PermissionsChecker
{
    /**
     * Gets the permission of the given set of folders.
     *
     * @param $folders
     * @return array
     */
    public function check(array $folders)
    {
        $results = [
            'folders'   => [],
            'errors'    => false];

        foreach ($folders as $folder => $permission) {
            // check the permission of the folder
            $result = $this->getPermission($folder);

            // push the results to an array
            array_push($results['folders'], [
                'folder'    => $folder,
                'expected'  => $permission,
                'current'   => $result,
                'set'       => $result >= $permission]);

            if ($result >= $permission) {
                $results['errors'] = true;
            }
        }

        return $results;
    }

    /**
     * Get the folders permission
     *
     * @param $folder
     * @return string
     */
    private function getPermission($folder)
    {
        $permission = substr(sprintf('%o', fileperms(base_path($folder))), -4);

        // check if the permission starts with a 0
        $prefix = substr($permission, 0, 1);

        if ($prefix == 0 || $prefix == '0') {
            // remove the 0 and return
            return ltrim($permission, 0);
        }
    }
}
