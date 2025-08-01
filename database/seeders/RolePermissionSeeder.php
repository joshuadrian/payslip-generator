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
            ['name' => 'view attendance periods'],
            ['name' => 'create attendance period'],

            ['name' => 'create attendance'],

            ['name' => 'create overtime'],

            ['name' => 'create reimbursement'],

            ['name' => 'view payrolls'],
            ['name' => 'create payroll'],
            ['name' => 'view specific payroll'],

            ['name' => 'generate specific payslip'],
            ['name' => 'generate payslip summary on specific payroll'],

        ];

        $roles = [
            [
                'role' => ['name' => 'Admin'],
                'permissions' => [
                    'view attendance periods',
                    'create attendance period',
                    'view payrolls',
                    'create payroll',
                    'view specific payroll',
                    'generate payslip summary on specific payroll',
                ]
            ],
            [
                'role' => ['name' => 'Employee'],
                'permissions' => [
                    'create attendance',
                    'create overtime',
                    'create reimbursement',
                    'generate specific payslip',
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
