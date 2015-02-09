<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Http\Controllers\Controller;
use Response, File;

/**
 * Class ApiController
 * @package Journal\Controllers\Api
 */
class ApiController extends Controller {
    /**
     * Stores the status code
     *
     * @var int
     */
    protected $statusCode = 200;
    /**
     * Creates an API Controller
     */
    public function __construct() {
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
    /**
     * Returns the status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    public function respondWithError($message = 'Error')
    {
        return $this->respond(array(
            'errors' => array(
                'message' => $message)));
    }
    /**
     * Prepare data to return JSON
     *
     * @param $data
     * @return mixed
     */
    public function respond($data)
    {
        return Response::json($data, $this->getStatusCode());
    }
}
