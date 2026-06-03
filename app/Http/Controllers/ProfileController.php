<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load('employee.department', 'employee.position');
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user()->load('employee.department', 'employee.position');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update user
        $user->name = $validated['name'];

        // Update password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }
        $user->save();

        // Update employee data
        if ($user->employee) {
            $empData = ['phone' => $validated['phone'] ?? null, 'address' => $validated['address'] ?? null];
            if ($request->hasFile('photo')) {
                if ($user->employee->photo) Storage::disk('public')->delete($user->employee->photo);
                $empData['photo'] = $request->file('photo')->store('photos', 'public');
            }
            $user->employee->update($empData);
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
