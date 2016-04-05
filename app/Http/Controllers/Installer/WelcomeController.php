<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;

use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use Journal\Support\PermissionsChecker;
use Journal\Support\RequirementsChecker;

class WelcomeController extends Controller
{
    protected $folders;
    protected $permissions;
    protected $requirements;
    protected $server;

    public function __construct(PermissionsChecker $permissions, RequirementsChecker $requirements)
    {
        $this->folders  = config('installer.permissions');
        $this->server   = config('installer.requirements');

        $this->permissions  = $permissions;
        $this->requirements = $requirements;
    }

    public function page()
    {
        // check for the system requirement
        $server = $this->requirements->checkExtensions($this->server);

        // check for the folders
        $folders = $this->permissions->check($this->folders);

        // check if there are errors or requirements that wasn't met
        if (!$server['errors'] && !$folders['errors']) {
            // show the welcome page
            return view('installer.welcome');
        }

        // show errors
        return view('installer.requirements',
            compact('server', 'folders'));
    }
}
