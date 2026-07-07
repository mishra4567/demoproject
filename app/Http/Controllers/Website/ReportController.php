<?php
// controller/website/ReportController.php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class ReportController extends Controller
{
    // ── Show form ─────────────────────────────────────────────────
    public function index()
    {
        return view('website.report.report');
    }
    public function createReport()
    {
        return view('website.report.report');
    }
    // public function show(Request $request)
    // {
    //     $report = Report::latest()->get();

    //     return view('website.report.view', compact('report'));
    // }
    public function show(Request $request)
    {
        $report = collect();

        if ($request->filled('search')) {

            $search = trim($request->search);

            $report = Report::where(function ($q) use ($search) {

                $q->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('user_id', 'ILIKE', "%{$search}%")
                    ->orWhere('auth_email', 'ILIKE', "%{$search}%")
                    ->orWhere('user_name', 'ILIKE', "%{$search}%");
            })->latest()->get();
        }

        // if ($request->filled('user_type')) {
        //     $query->whereRaw('LOWER(user_type) = LOWER(?)', [$request->user_type]);
        // }

        // if ($request->filled('rating')) {
        //     $query->where('rating', $request->rating);
        // }

        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // }

        // if ($request->filled('date')) {
        //     $query->whereDate('created_at', $request->date);
        // }

        // $report = $query
        //     ->latest()
        //     ->paginate(20)
        //     ->withQueryString();

        return view('website.report.view', compact('report'));
    }

    // ── Store ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'user_type'     => 'required|in:admin,vendor,customer',
            'user_id'       => 'required|string|min:1',
            'auth_email'    => 'required|email|max:255',
            'auth_password' => 'required|string|min:4',
            'rating'        => 'required|integer|min:1|max:5',
            'title'         => 'nullable|string|max:255',
            'fields'        => 'required|array|min:1',
            'fields.*.field_type'   => 'required|in:text,number,file,email,url,date,textarea',
            'fields.*.description'  => 'required|string|max:1000',
            'fields.*.field_value'  => 'nullable|string',
        ]);

        // Build sections
        $sections = [];
        foreach ($request->fields as $idx => $field) {
            $section = [
                'field_type'  => $field['field_type'],
                'description' => $field['description'],
                'field_value' => null,
                'file_path'   => null,
            ];

            if (
                $field['field_type'] === 'file' &&
                $request->hasFile("fields.{$idx}.file")
            ) {

                $file = $request->file("fields.{$idx}.file");

                $extension = strtolower($file->getClientOriginalExtension());

                $fileName = time() . '_' . uniqid() . '.' . $extension;

                $file->move(
                    public_path('storage/reports'),
                    $fileName
                );

                $section['file_path'] = 'reports/' . $fileName;
            } else {
                $section['field_value'] = $field['field_value'] ?? null;
            }

            $sections[] = $section;
        }

        // Store everything including credentials as-is
        Report::create([
            'user_type'     => $request->user_type,
            'user_id'       => $request->user_id,
            'auth_email'    => $request->auth_email,
            'auth_password' => bcrypt($request->auth_password), // hash before saving
            'user_name'     => $request->user_name,
            'rating'        => $request->rating,
            'title'         => $request->title,
            'report_data'   => [
                'sections'     => $sections,
                'submitted_at' => now(),
                'ip'           => $request->ip(),
                'user_agent'   => $request->userAgent(),
            ],
            'status'        => 1,
        ]);
        // dd([
        //     'model' => get_class(new Report()),
        //     'connection' => (new Report())->getConnectionName(),
        //     'drivers' => PDO::getAvailableDrivers(),
        //     'data' => [
        //         'user_type'     => $request->user_type,
        //         'user_id'       => $request->user_id,
        //         'auth_email'    => $request->auth_email,
        //         'auth_password' => bcrypt($request->auth_password), // hash before saving
        //         'user_name'     => $request->user_name,
        //         'rating'        => $request->rating,
        //         'title'         => $request->title,
        //         'report_data'   => [
        //             'sections'     => $sections,
        //             'submitted_at' => now(),
        //             'ip'           => $request->ip(),
        //             'user_agent'   => $request->userAgent(),
        //         ],
        //         'status'        => 1,
        //     ]
        // ]);


        return redirect()->route('report.create')
            ->with('success', 'Report submitted successfully!');
    }
}
