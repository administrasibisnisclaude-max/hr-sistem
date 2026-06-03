<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Human Resources', 'description' => 'Departemen Sumber Daya Manusia'],
            ['name' => 'Teknologi Informasi', 'description' => 'Departemen IT dan Pengembangan'],
            ['name' => 'Keuangan', 'description' => 'Departemen Keuangan dan Akuntansi'],
            ['name' => 'Operasional', 'description' => 'Departemen Operasional'],
            ['name' => 'Marketing', 'description' => 'Departemen Pemasaran'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }

        // Create positions for each department
        $positions = [
            ['name' => 'HR Manager', 'department_id' => 1, 'basic_salary' => 8000000],
            ['name' => 'HR Staff', 'department_id' => 1, 'basic_salary' => 5000000],
            ['name' => 'IT Manager', 'department_id' => 2, 'basic_salary' => 10000000],
            ['name' => 'Software Developer', 'department_id' => 2, 'basic_salary' => 8000000],
            ['name' => 'IT Support', 'department_id' => 2, 'basic_salary' => 5000000],
            ['name' => 'Finance Manager', 'department_id' => 3, 'basic_salary' => 9000000],
            ['name' => 'Accountant', 'department_id' => 3, 'basic_salary' => 6000000],
            ['name' => 'Operations Manager', 'department_id' => 4, 'basic_salary' => 8000000],
            ['name' => 'Operations Staff', 'department_id' => 4, 'basic_salary' => 5000000],
            ['name' => 'Marketing Manager', 'department_id' => 5, 'basic_salary' => 8000000],
            ['name' => 'Marketing Staff', 'department_id' => 5, 'basic_salary' => 5500000],
        ];

        foreach ($positions as $pos) {
            Position::firstOrCreate(
                ['name' => $pos['name'], 'department_id' => $pos['department_id']],
                $pos
            );
        }
    }
}
