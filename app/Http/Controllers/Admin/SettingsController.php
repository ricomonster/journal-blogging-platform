<?php //-->
namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

/**
 * Class SettingsController
 * @package Journal\Http\Controllers\Admin
 */
class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function navigation()
    {
        return view('admin.settings.navigation');
    }
}
