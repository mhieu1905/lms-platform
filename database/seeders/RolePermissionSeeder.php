<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        $defaultUser = User::firstOrCreate(
            ['email' => 'defaultusereduma@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Eduma@123'),
            ]
        );

        $onlyAdminUser = User::firstOrCreate(
            ['email' => 'edumadefault@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Eduma@123'),
            ]
        );

        $defaultUser->roles()->syncWithoutDetaching([$adminRole->id, $superAdminRole->id]);
        $onlyAdminUser->roles()->sync([$adminRole->id]);

        $permission = [
            // auth
            'auth',

            // Search
            'admin_search',

            // courses (management)
            'view_courses',
            'create_courses',
            'edit_courses',
            'delete_courses',
            'toggle_courses',

            // Upload files
            'upload_temp_image',

            // levels (management)
            'manage_levels',

            // categories (management)
            'manage_categories',

            // chapter (management)
            'manage_chapters',
            'view_chapters',

            // lesson (management)
            'manage_lesson',

            // major (management)
            'view_majors',
            'create_majors',
            'edit_majors',
            'delete_majors',

            // event (management)
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            'toggle_events',

            // buy ticket
            'buy_ticket_events',

            // slider (CMS)
            'view_homepage_slider',
            'create_homepage_slider',
            'edit_homepage_slider',
            'toggle_homepage_slider',
            'delete_homepage_slider',

            // footer (CMS)
            'view_footer_section',
            'create_footer_section',
            'edit_footer_section',
            'delete_footer_section',
            // courses (home)
            'buy_course',
            'finish_course',
            'retake_course',
            'enroll-free',

            // lessons (home)
            'view_lesson',
            'complete_lesson',

            // profile (home)
            'view_profile',
            'edit_profile',
            'change_password',

            // news management
            'news_management',

            // order management
            'orders.create',
            'orders.store',
            'orders.orderPayPal',

            // payment management
            'payments.show',
            'payments.check_status',
            'payments.update_status',
            
            // user (management)
            // teacher application (management)
            'view_applications',
            'approve_application',
            'reject_application',
            // students (management)
            'manage_students',
            // teachers (management)
            'manage_teachers',

            // register teacher
            'register_teacher',

            // dashboard
            'view_dashboard',
        ];

        $permissionModels = [];
        foreach ($permission as $perm) {
            $permissionModels[$perm] = Permission::firstOrCreate(['name' => $perm]);
        }

        $studentRole->permissions()->sync([
            $permissionModels['auth']->id,
            $permissionModels['buy_course']->id,
            $permissionModels['finish_course']->id,
            $permissionModels['retake_course']->id,
            $permissionModels['complete_lesson']->id,
            $permissionModels['view_profile']->id,
            $permissionModels['edit_profile']->id,
            $permissionModels['change_password']->id,
            $permissionModels['buy_ticket_events']->id,
            $permissionModels['orders.create']->id,
            $permissionModels['orders.store']->id,
            $permissionModels['payments.show']->id,
            $permissionModels['payments.check_status']->id,
            $permissionModels['payments.update_status']->id,
            $permissionModels['enroll-free']->id,
            $permissionModels['orders.orderPayPal']->id

        ]);

        $teacherRole->permissions()->syncWithoutDetaching(
            $studentRole->permissions->pluck('id')->toArray()
        );

        $teacherRole->permissions()->syncWithoutDetaching([
            $permissionModels['view_courses']->id,
            $permissionModels['create_courses']->id,
            $permissionModels['edit_courses']->id,
            $permissionModels['delete_courses']->id,
            $permissionModels['upload_temp_image']->id,
            $permissionModels['manage_levels']->id,
            $permissionModels['manage_categories']->id,
            $permissionModels['manage_chapters']->id,
            $permissionModels['view_chapters']->id,
            $permissionModels['manage_lesson']->id,
            $permissionModels['admin_search']->id,
            $permissionModels['view_dashboard']->id,
        ]);

        $teacherRole->permissions()->detach([
            $permissionModels['manage_levels']->id,
            $permissionModels['manage_categories']->id,
        ]);

        $manageAdminsPerm = Permission::firstOrCreate(['name' => 'manage_admins']);

        $adminPermissions = Permission::whereNotIn('name', ['manage_admins'])
            ->pluck('id')
            ->toArray();

        $adminRole->permissions()->sync($adminPermissions);

        $superAdminRole->permissions()->sync(
            array_merge($adminPermissions, [$manageAdminsPerm->id])
        );
    }
}
