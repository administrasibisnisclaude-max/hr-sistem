<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('employee', 'uploader');

        if ($request->employee_id) $query->where('employee_id', $request->employee_id);
        if ($request->document_type) $query->where('document_type', $request->document_type);

        $documents = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $employees = Employee::active()->orderBy('name')->get();

        return view('documents.index', compact('documents', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();
        return view('documents.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'document_type' => 'required|in:ktp,npwp,ijazah,cv,foto,kontrak,lainnya',
            'file' => 'required|file|max:10240',
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'employee_id' => $validated['employee_id'],
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil diunggah.');
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
