<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class ApiUploadController extends ApiController
{
    protected $uploadPath;
    protected $uploadUrl;

    public function __construct()
    {
        $yearMonth = date('Y/m');
        // set the upload path year and month as folder directories
        $this->uploadUrl = '/uploads/'.$yearMonth;
        $this->uploadPath = public_path($this->uploadUrl);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            // process the upload
            $file = $request->file('file');

            // get the filename
            $filename = $file->getClientOriginalName();

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

        // no file, send an error message
        return $this->setStatusCode(self::BAD_REQUEST)
            ->respondWithError(['message' => 'File to uploaded is required.']);
    }
}
