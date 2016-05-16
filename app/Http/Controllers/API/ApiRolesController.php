<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\Role\RoleRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

/**
 * Class ApiRolesController
 * @package Journal\Http\Controlles\Api
 */
class ApiRolesController extends ApiController
{
    /**
     * @var RoleRepositoryInterface
     */
    protected $roles;

    /**
     * @param RoleRepositoryInterface $roles
     */
    public function __construct(RoleRepositoryInterface $roles)
    {
        // set the JWT middleware
        $this->middleware('jwt.auth', ['except' => ['all']]);

        $this->roles = $roles;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function all(Request $request)
    {
        // get all the roles
        $roles = $this->roles->all();

        return $this->respond([
            'roles' => $roles]);
    }
}
