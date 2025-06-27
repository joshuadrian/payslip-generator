<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'create attendance period'],

            ['name' => 'create attendance'],

            ['name' => 'create overtime'],

            ['name' => 'create reimbursement'],

            ['name' => 'view payrolls'],
            ['name' => 'create payroll'],

            ['name' => 'view specified payslip'],
            ['name' => 'create payslip'],
            ['name' => 'create payslip summary on specified payroll'],

        ];

        $roles = [
            [
                'role' => ['name' => 'Admin'],
                'permissions' => [
                    'create attendance period',
                    'view payrolls',
                    'create payroll',
                    'create payslip summary on specified payroll',
                ]
            ],
            [
                'role' => ['name' => 'Employee'],
                'permissions' => [
                    'create attendance',
                    'create overtime',
                    'create reimbursement',
                    'view specified payslip',
                    'create payslip',
                ]
            ],
        ];

        array_walk($permissions, fn($d) => Permission::create($d));
        array_walk($roles, function ($d) {
            $role = Role::create($d['role']);
            if (!empty($d['permissions'])) {
                $role->syncPermissions($d['permissions']);
            }
        });
    }
}
