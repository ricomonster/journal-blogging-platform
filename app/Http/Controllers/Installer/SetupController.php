<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use Journal\Role;

class SetupController extends Controller
{
    public function page()
    {
        // get the owner
        $ownerRole = Role::where('slug', '=', 'owner')
            ->first();

        // check if there's a result
        if (empty($ownerRole)) {
            // 404 page
            return $this->fourOhFourPage();
        }

        return view('installer.setup', compact('ownerRole'));
    }
}
