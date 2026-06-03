<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $settings = CompanySetting::all()->keyBy('key');
        return view('company.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:50',
            'company_email' => 'nullable|email',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
            'leave_days_per_year' => 'nullable|integer|min:1|max:365',
            'overtime_rate' => 'nullable|numeric|min:1',
            'logo' => 'nullable|image|max:2048',
        ]);

        $fields = ['company_name', 'company_address', 'company_phone', 'company_email', 'work_start_time', 'work_end_time', 'leave_days_per_year', 'overtime_rate'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                CompanySetting::set($field, $request->input($field));
            }
        }

        if ($request->hasFile('logo')) {
            $oldLogo = CompanySetting::get('company_logo');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);
            $path = $request->file('logo')->store('company', 'public');
            CompanySetting::set('company_logo', $path);
        }

        activity()->causedBy(auth()->user())->log('Pengaturan perusahaan diperbarui');

        return back()->with('success', 'Pengaturan perusahaan berhasil diperbarui.');
    }
}
