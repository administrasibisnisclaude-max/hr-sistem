<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'PT. Maju Bersama', 'type' => 'text', 'label' => 'Nama Perusahaan'],
            ['key' => 'company_address', 'value' => 'Jl. Sudirman No. 123, Jakarta Pusat', 'type' => 'text', 'label' => 'Alamat'],
            ['key' => 'company_phone', 'value' => '021-1234567', 'type' => 'text', 'label' => 'Telepon'],
            ['key' => 'company_email', 'value' => 'info@majubersama.com', 'type' => 'text', 'label' => 'Email'],
            ['key' => 'company_logo', 'value' => '', 'type' => 'image', 'label' => 'Logo'],
            ['key' => 'work_start_time', 'value' => '08:00', 'type' => 'text', 'label' => 'Jam Masuk'],
            ['key' => 'work_end_time', 'value' => '17:00', 'type' => 'text', 'label' => 'Jam Pulang'],
            ['key' => 'leave_days_per_year', 'value' => '12', 'type' => 'text', 'label' => 'Jatah Cuti Per Tahun'],
            ['key' => 'overtime_rate', 'value' => '1.5', 'type' => 'text', 'label' => 'Tarif Lembur (multiplier)'],
        ];

        foreach ($settings as $setting) {
            CompanySetting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
