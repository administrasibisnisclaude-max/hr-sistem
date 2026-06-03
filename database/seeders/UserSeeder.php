<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create owner user
        $owner = User::firstOrCreate(
            ['email' => 'owner@hrsistem.com'],
            [
                'name' => 'Owner',
                'email' => 'owner@hrsistem.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $owner->assignRole('owner');

        // Create admin user with employee
        $adminEmployee = Employee::firstOrCreate(
            ['nik' => 'ADM001'],
            [
                'nik' => 'ADM001',
                'name' => 'Administrator',
                'email' => 'admin@hrsistem.com',
                'phone' => '081234567890',
                'hire_date' => '2020-01-01',
                'department_id' => 1,
                'position_id' => 1,
                'employment_status' => 'tetap',
                'gender' => 'L',
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@hrsistem.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@hrsistem.com',
                'password' => Hash::make('password'),
                'employee_id' => $adminEmployee->id,
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // Create sample karyawan
        $emp1 = Employee::firstOrCreate(
            ['nik' => 'EMP001'],
            [
                'nik' => 'EMP001',
                'name' => 'Budi Santoso',
                'email' => 'budi@hrsistem.com',
                'phone' => '081234567891',
                'hire_date' => '2021-03-01',
                'department_id' => 2,
                'position_id' => 4,
                'employment_status' => 'tetap',
                'gender' => 'L',
                'birth_date' => '1990-05-15',
            ]
        );

        $user1 = User::firstOrCreate(
            ['email' => 'budi@hrsistem.com'],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@hrsistem.com',
                'password' => Hash::make('password'),
                'employee_id' => $emp1->id,
                'is_active' => true,
            ]
        );
        $user1->assignRole('karyawan');

        $emp2 = Employee::firstOrCreate(
            ['nik' => 'EMP002'],
            [
                'nik' => 'EMP002',
                'name' => 'Siti Rahayu',
                'email' => 'siti@hrsistem.com',
                'phone' => '081234567892',
                'hire_date' => '2022-01-15',
                'department_id' => 3,
                'position_id' => 7,
                'employment_status' => 'tetap',
                'gender' => 'P',
                'birth_date' => '1993-08-20',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'siti@hrsistem.com'],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@hrsistem.com',
                'password' => Hash::make('password'),
                'employee_id' => $emp2->id,
                'is_active' => true,
            ]
        );
        $user2->assignRole('karyawan');
    }
}
