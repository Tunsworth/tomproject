<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // edit view and restricted 

        $edit = Role::create(['name' => 'edit']);
        $view = Role::create(['name' => 'view']);
        $restricted = Role::create(['name' => 'restricted']);


        $permission_view = Permission::create(['name' => 'view quizzes']);
        $permission_answer = Permission::create(['name' => 'answer quizzes']);
        $permission_write = Permission::create(['name' => 'create quizzes']);
        $permission_edit = Permission::create(['name' => 'edit quizzez']);
        $permission_delete = Permission::create(['name' => 'delete quizzes']);

        $edit->syncPermissions([$permission_view, $permission_edit, $permission_write, $permission_delete, $permission_answer]);
        $view->syncPermissions([$permission_view, $permission_answer]);
        $restricted->givePermissionTo($permission_view);

        $user_edit = new \App\Models\User();
        $user_edit->email = 'edit@webbiskools.com';
        $user_edit->password = \Illuminate\Support\Facades\Hash::make('webbiskools_edit');
        $user_edit->name = 'Edit user';
        $user_edit->save();
        $user_edit->assignRole($edit);

        $user_view = new \App\Models\User();
        $user_view->email = 'view@webbiskools.com';
        $user_view->password = \Illuminate\Support\Facades\Hash::make('webbiskools_view');
        $user_view->name = 'View user';
        $user_view->save();
        $user_view->assignRole($view);

        $user_restricted = new \App\Models\User();
        $user_restricted->email = 'restricted@webbiskools.com';
        $user_restricted->password = \Illuminate\Support\Facades\Hash::make('webbiskools_restricted');
        $user_restricted->name = 'Restricted user';
        $user_restricted->save();
        $user_restricted->assignRole($restricted);
    }
}
