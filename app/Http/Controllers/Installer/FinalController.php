<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use DB;

class FinalController extends Controller
{
    public function page()
    {
        // get the first ever user
        $user = DB::table('users')
            ->where('active', '=', 1)
            ->first();

        return view('installer.final', compact('user'));
    }
}
