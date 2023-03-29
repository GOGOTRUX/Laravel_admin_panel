<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Super Admin']);
	$role->givePermissionTo(Permission::all()); //assign all permission to the role Super Admin
        $user = User::create(['name' => 'GoGoTrux Admin', 'email' => 'admin@gogotrux.com', 'password' => bcrypt('g@g@T#ux@321')]);
        $user->assignRole('Super Admin');
    }
}
