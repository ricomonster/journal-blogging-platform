<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\API\ApiController;

class ApiUploadController extends ApiController
{
    protected $uploadPath;
    protected $uploadUrl;

    public function __construct()
    {
        $yearMonth = date('Y/m');

        // set the upload path year and month as folder directories
        $this->uploadUrl = '/uploads/' . $yearMonth;
        $this->uploadPath = public_path($this->uploadUrl);
    }

    public function upload(Request $request)
    {
        // check if there's no file passed
        if (!$request->hasFile('files')) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => self::FILE_REQUIRED]);
        }

        // process the upload
        $file = $request->file('files');

        // get the filename
        $filename = $file->getClientOriginalName();

        // get the file extension
        $extension = $file->getClientOriginalExtension();

        // generate a new file name
        $filename = md5($filename + '.' + time());

        // update filename
        $filename .= '.'.$extension;

        // upload it
        $uploaded = $file->move($this->uploadPath, $filename);

        // validate if file is uploaded
        if ($uploaded) {
            // return the url of the image
            return $this->respond([
                'url' => $this->uploadUrl.'/'.urlencode($filename)]);
        }

        // something went wrong, tell to the user
        return $this->setStatusCode(self::INTERNAL_ERROR)
            ->respondWithError(['message' => 'Something went wrong while uploading the file.']);
    }
}
