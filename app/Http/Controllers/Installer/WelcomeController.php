<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;

use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    protected $folders;
    protected $server;

    public function __construct()
    {
        $this->folders  = config('installer.permissions');
        $this->server   = config('installer.requirements');
    }

    public function page()
    {
        // check for the system requirement
        $server = $this->checkExtensions();

        // check for the folders
        $folders = $this->checkFolders();

        // check if there are errors or requirements that wasn't met
        if (!$server['errors'] && !$folders['errors']) {
            // show the welcome page
            return view('installer.welcome');
        }

        // show errors
        return view('installer.requirements',
            compact('server', 'folders'));
    }

    /**
     * Checks the system requirements of the server
     *
     * @param $requirements
     * @return array
     */
    public function checkExtensions()
    {
        $requirements = $this->server;

        $results = [];

        // initialize array key
        $results['errors'] = false;

        foreach($requirements as $requirement)
        {
            $results['requirements'][$requirement] = true;

            if(!extension_loaded($requirement))
            {
                $results['requirements'][$requirement] = false;
                $results['errors'] = true;
            }
        }

        return $results;
    }

    protected function checkFolders()
    {
        $folders = $this->folders;

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
