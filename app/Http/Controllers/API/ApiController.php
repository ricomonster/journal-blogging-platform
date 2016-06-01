<?php //-->
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;

use Journal\Http\Controllers\Controller;
use Journal\Http\Requests;

/**
 * Class ApiController
 * @package Journal\Http\Controllers\API
 */
class ApiController extends Controller
{
    const OK                            = 200;
    const BAD_REQUEST                   = 400;
    const UNAUTHORIZED                  = 401;
    const FORBIDDEN                     = 403;
    const NOT_FOUND                     = 404;
    const INTERNAL_SERVER_ERROR         = 500;

    const AUTHOR_ID_REQUIRED            = 'Author ID is required.';
    const AUTHOR_NOT_FOUND              = 'Author not found.';
    const CURRENT_PASSWORD_NOT_THE_SAME = 'That is not your current password.';
    const FILE_REQUIRED                 = 'A file is required to be uploaded.';
    const INVALID_CREDENTIALS           = 'Invalid e-mail or password.';
    const POST_ID_REQUIRED              = 'Post ID is required.';
    const POST_NOT_FOUND                = 'Post not found.';
    const TAG_ID_REQUIRED               = 'Tag ID is required.';
    const TAG_NOT_FOUND                 = 'Tag not found.';
    const TAG_TITLE_REQUIRED            = 'Title of the tag is required.';
    const TAGS_NOT_FOUND                = 'Tag not found.';
    const UNAUTHORIZED_ACCESS           = 'You are not authorized to perform this action.';
    const USER_ID_REQUIRED              = 'User ID is required.';
    const USER_NOT_FOUND                = 'User not found.';

    /**
     * Request status code
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * Returns the status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns a response message
     *
     * @param $message
     * @return mixed
     */
    public function respond($message)
    {
        return response()->json($message, $this->getStatusCode());
    }

    /**
     * Returns an error response and message
     *
     * @param $message
     * @return mixed
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'errors' => $message
        ]);
    }

    /**
     * Sets the status code
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
