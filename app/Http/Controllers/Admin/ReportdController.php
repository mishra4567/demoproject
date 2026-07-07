<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportdController extends Controller
{
    public function show(Request $request)
    {
        $query = Report::query();

        // Search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('title',     'ilike', "%{$search}%")
                    ->orWhere('user_id',    'like', "%{$search}%")
                    ->orWhere('auth_email', 'ilike', "%{$search}%")
                    ->orWhere('user_name',  'ilike', "%{$search}%");
            });
        }

        if ($request->user_type) $query->where('user_type', $request->user_type);
        if ($request->rating)    $query->where('rating',    $request->rating);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->date)      $query->whereDate('created_at', $request->date);

        $report = $query->latest()->paginate(20)->withQueryString();

        return view('admin.report.viewreport', compact('report'));
    }
}
