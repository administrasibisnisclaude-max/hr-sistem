<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')->orderByDesc('created_at');

        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        if ($request->causer_id) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(30)->withQueryString();
        $users = \App\Models\User::orderBy('name')->get();

        return view('audit-logs.index', compact('logs', 'users'));
    }
}
