<?php //-->
namespace Journal\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;

class TagsController extends Controller
{
    /**
     * Shows the list of saved tags
     *
     * @return View
     */
    public function lists()
    {
        return view('admin.tags.list');
    }

    /**
     * Shows the details of the tag and also the option to update it.
     *
     * @param $tagId
     * @return View
     */
    public function update($tagId)
    {
        // check if there's a given tagId
        if (!isset($tagId) || empty($tagId)) {
            return $this->fourOhFour();
        }

        return view('admin.tags.details', compact('tagId'));
    }
}
