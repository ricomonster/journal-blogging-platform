<?php //-->
namespace Journal\Repositories\Role;

use Illuminate\Support\Str;
use Journal\Role;

class DbRoleRepository implements RoleRepositoryInterface
{
    /**
     * @param $name
     * @return \Journal\Role
     */
    public function create($name)
    {
        $role = Role::create([
            'name' => $name,
            'slug' => $this->generateSlug($name)]);

        return $this->findById($role->id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return Role::where('active', '=', 1)->get();
    }

    /**
     * @param $id
     * @return \Journal\Role
     */
    public function findById($id)
    {
        return Role::where('active', '=', 1)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @param $slug
     * @return \Journal\Role
     */
    public function findBySlug($slug)
    {
        return Role::where('active', '=', 1)
            ->where('slug', '=', $slug)
            ->first();
    }

    /**
     * @param $id
     * @return void
     */
    public function remove($id)
    {
        // get the role
        $role = $this->findById($id);

        // update the active column
        $role->active = 0;
        $role->save();
    }

    /**
     * @param $string
     * @return string
     */
    protected function generateSlug($string)
    {
        // slugify
        $slug = Str::slug(strtolower($string));

        $count = count(Role::where('slug', 'LIKE', $slug.'%')->get());

        // return the slug
        return ($count > 0) ? "{$slug}-{$count}" : $slug;
    }
}
