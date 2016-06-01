<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

/**
 * Class InstallerController
 * @package Journal\Http\Controllers\Installer
 */
class InstallerController extends Controller
{
    public function page()
    {
        return view('installer.final');
    }
}
