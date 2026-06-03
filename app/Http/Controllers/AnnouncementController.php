<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Announcement::with('admin');

        if ($user->hasRole('karyawan')) {
            $query->active()->where(function ($q) {
                $q->where('target_role', 'all')->orWhere('target_role', 'karyawan');
            });
        } else {
            if ($request->status === 'active') $query->active();
            elseif ($request->status === 'inactive') $query->where('is_active', false);
        }

        $announcements = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'target_role' => 'required|in:all,owner,admin,karyawan',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $validated['admin_id'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['published_at'] = now();

        Announcement::create($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'target_role' => 'required|in:all,owner,admin,karyawan',
            'expires_at' => 'nullable|date',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);
        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
