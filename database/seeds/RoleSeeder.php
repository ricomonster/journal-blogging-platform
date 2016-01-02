<?php

use Illuminate\Database\Seeder;
use Journal\Repositories\Role\RoleRepositoryInterface;

class RoleSeeder extends Seeder
{
    protected $roles;
    protected $roleLists = ['Owner', 'Administrator', 'Editor'];

    public function __construct(RoleRepositoryInterface $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // perform a loop
        foreach ($this->roleLists as $role) {
            // creates the role
            $this->roles->create($role);
        }
    }
}
