<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $karyawan = Role::firstOrCreate(['name' => 'karyawan', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            // Employee
            'view employees', 'create employees', 'edit employees', 'delete employees',
            // Department
            'view departments', 'create departments', 'edit departments', 'delete departments',
            // Position
            'view positions', 'create positions', 'edit positions', 'delete positions',
            // Attendance
            'view attendance', 'manage attendance', 'view own attendance',
            // Leave
            'view leaves', 'manage leaves', 'approve leaves', 'view own leaves', 'create own leaves',
            // Payroll
            'view payroll', 'manage payroll', 'view own payroll',
            // Contract
            'view contracts', 'manage contracts',
            // Documents
            'view documents', 'manage documents',
            // Performance
            'view performance', 'manage performance',
            // Announcements
            'view announcements', 'manage announcements',
            // Reports
            'view reports',
            // Settings
            'manage settings',
            // Audit Log
            'view audit log',
            // Profile
            'view own profile', 'edit own profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Owner permissions - read access + approvals
        $owner->syncPermissions([
            'view employees', 'view departments', 'view positions',
            'view attendance', 'manage attendance',
            'view leaves', 'approve leaves',
            'view payroll',
            'view contracts',
            'view documents',
            'view performance', 'manage performance',
            'view announcements',
            'view reports',
            'view audit log',
            'view own profile',
        ]);

        // Admin permissions - full CRUD
        $admin->syncPermissions([
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view positions', 'create positions', 'edit positions', 'delete positions',
            'view attendance', 'manage attendance',
            'view leaves', 'manage leaves', 'approve leaves',
            'view payroll', 'manage payroll',
            'view contracts', 'manage contracts',
            'view documents', 'manage documents',
            'view performance', 'manage performance',
            'view announcements', 'manage announcements',
            'view reports',
            'manage settings',
            'view own profile', 'edit own profile',
        ]);

        // Karyawan permissions - own data only
        $karyawan->syncPermissions([
            'view own attendance',
            'view own leaves', 'create own leaves',
            'view own payroll',
            'view announcements',
            'view own profile', 'edit own profile',
        ]);
    }
}
