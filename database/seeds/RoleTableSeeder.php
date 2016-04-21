<?php

use Illuminate\Database\Seeder;
use Journal\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Owner', 'Administrator', 'Editor'];

        foreach ($roles as $r => $role) {
            Role::create([
                'name' => $role,
                'slug' => str_slug(strtolower($role))]);
        }
    }
}
