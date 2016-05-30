<?php //-->
namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

/**
 * Class TagsController
 * @package Journal\Http\Controllers\Admin
 */
class TagsController extends Controller
{
    /**
     * Shows the details of the tag and also the option to edit.
     *
     * @param $tagId
     * @return View
     */
    public function edit($tagId)
    {
        // check if there's a given tagId
        if (!isset($tagId) || empty($tagId)) {
            return $this->fourOhFour();
        }

        return view('admin.tags.details', compact('tagId'));
    }

    /**
     * Shows the list of saved tags
     *
     * @return View
     */
    public function index()
    {
        return view('admin.tags.index');
    }
}
