<?php //-->
namespace Journal\Repositories\Role;

/**
 * Interface RoleRepositoryInterface
 * @package Journal\Repositories\Role
 */
interface RoleRepositoryInterface
{
    /**
     * @param $name
     * @return \Journal\Role
     */
    public function create($name);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $id
     * @return \Journal\Role
     */
    public function findById($id);

    /**
     * @param $slug
     * @return \Journal\Role
     */
    public function findBySlug($slug);

    /**
     * @param $id
     * @return void
     */
    public function remove($id);
}
