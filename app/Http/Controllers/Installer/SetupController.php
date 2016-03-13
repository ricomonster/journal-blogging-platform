<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

class SetupController extends Controller
{
    public function page()
    {
        return view('installer.setup');
    }
}
