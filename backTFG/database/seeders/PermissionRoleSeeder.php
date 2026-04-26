<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    private array $roles = ['admin', 'manager', 'employee', 'hr'];

    private array $permissions = [
        'users.view', 'users.create', 'users.edit', 'users.delete',
        'time_logs.view', 'time_logs.create', 'time_logs.edit', 'time_logs.delete',
        'absence_requests.view', 'absence_requests.create', 'absence_requests.edit', 'absence_requests.delete',
        'approvals.manage',
        'departments.view', 'departments.manage',
        'shifts.view', 'shifts.manage',
        'holidays.view', 'holidays.manage',
        'reports.view',
        'audit_logs.view',
    ];

    private array $rolePermissions = [
        'admin' => [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'time_logs.view', 'time_logs.create', 'time_logs.edit', 'time_logs.delete',
            'absence_requests.view', 'absence_requests.create', 'absence_requests.edit', 'absence_requests.delete',
            'approvals.manage',
            'departments.view', 'departments.manage',
            'shifts.view', 'shifts.manage',
            'holidays.view', 'holidays.manage',
            'reports.view',
            'audit_logs.view',
        ],
        'manager' => [
            'users.view',
            'time_logs.view', 'time_logs.edit',
            'absence_requests.view',
            'approvals.manage',
            'departments.view',
            'shifts.view',
            'holidays.view',
            'reports.view',
        ],
        'employee' => [
            'time_logs.view', 'time_logs.create',
            'absence_requests.view', 'absence_requests.create',
            'shifts.view',
            'holidays.view',
        ],
        'hr' => [
            'users.view', 'users.edit',
            'time_logs.view', 'time_logs.edit',
            'absence_requests.view', 'absence_requests.edit',
            'approvals.manage',
            'departments.view',
            'holidays.view',
            'reports.view',
        ],
    ];

    public function run(): void
    {
        foreach ($this->roles as $roleName) {
            Role::create(['name' => $roleName]);
        }

        foreach ($this->permissions as $permName) {
            Permission::create(['name' => $permName]);
        }

        foreach ($this->rolePermissions as $roleName => $permNames) {
            $role        = Role::where('name', $roleName)->first();
            $permIds     = Permission::whereIn('name', $permNames)->pluck('id');
            $role->permissions()->attach($permIds);
        }
    }
}
