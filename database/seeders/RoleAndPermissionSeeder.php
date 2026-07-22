<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            'dashboard.view',
            
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            
            'settings.view',
            'settings.edit',
            'settings.security',
            
            'homepage.manage',
            
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.toggle_status',
            
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.delete',
            
            'portfolios.view',
            'portfolios.create',
            'portfolios.edit',
            'portfolios.delete',
            
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
            
            'blogs.view',
            'blogs.create',
            'blogs.edit',
            'blogs.delete',
            'blog_categories.view',
            'blog_categories.create',
            'blog_categories.edit',
            'blog_categories.delete',
            
            'comments.view',
            'comments.reply',
            'comments.approve',
            'comments.delete',
            
            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',
            
            'team.view',
            'team.create',
            'team.edit',
            'team.delete',
            
            'careers.view',
            'careers.create',
            'careers.edit',
            'careers.delete',
            
            'consultations.view',
            'consultations.delete',
            'form_fields.manage',
            
            'estimator.view',
            'estimator.settings.manage',
            'estimator.leads.view',
            'estimator.leads.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Super Admin role and assign all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions($permissions);

        // Create Editor role and assign subset of permissions
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorPermissions = [
            'dashboard.view',
            'categories.view',
            'projects.view', 'projects.create', 'projects.edit',
            'portfolios.view', 'portfolios.create', 'portfolios.edit',
            'services.view', 'services.create', 'services.edit',
            'blogs.view', 'blogs.create', 'blogs.edit',
            'blog_categories.view', 'blog_categories.create', 'blog_categories.edit',
            'comments.view', 'comments.reply', 'comments.approve',
            'gallery.view', 'gallery.create', 'gallery.edit',
            'team.view', 'team.create', 'team.edit',
            'careers.view', 'careers.create', 'careers.edit',
            'consultations.view',
            'estimator.view', 'estimator.leads.view'
        ];
        $editorRole->syncPermissions($editorPermissions);

        // Assign Super Admin role to the default admin users
        $admins = User::whereIn('email', ['admin@gmail.com', 'sajidulcse013@gmail.com'])->get();
        foreach ($admins as $admin) {
            $admin->assignRole($superAdminRole);
        }
    }
}
