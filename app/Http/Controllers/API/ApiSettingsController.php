<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

class ApiSettingsController extends Controller
{
    public function get(Request $request)
    {
        $fields = $request->input('fields');
    }
}
