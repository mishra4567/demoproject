<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreateMediaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateMediaTableController extends Controller
{
    // Index of Media
    public function index()
    {
        // $result['data'] = Product::all();
        $media['media'] = CreateMediaTable::all();
        return view('admin.uploadmedia.media', $media);
    }

    public function manageMedia(Request $request,)
    {
        return view('admin.uploadmedia.uploadmedia');
    }
    public function mediasearch(Request $request)
    {
        $query = $request->get('query', '');

        $media = CreateMediaTable::where('status', 1)
            ->when($query, function ($q) use ($query) {
                $q->where('tags', 'LIKE', '%' . $query . '%');
            })
            ->get();
        return response()->json($media);
    }
    public function store(Request $request)
    {
        $request->validate([
            'media.*' => 'required|mimes:jpg,jpeg,png,webp,mp4,mov,avi',
            'tags.*' => 'nullable|max:10',
            'description.*' => 'nullable|max:500',
        ]);


        if ($request->hasFile('media')) {

            foreach ($request->file('media') as $key => $file) {

                $extension = strtolower($file->getClientOriginalExtension());
                $fileName = time() . '_' . uniqid() . '.' . $extension;

                $file->move(public_path('/storage/media'), $fileName);

                $pAData = [
                    'file_name'   => $fileName,
                    'media_type'  => $extension, // saving only extension (as you want)
                    'tags'        => $request->tags[$key] ?? null,
                    'description' => $request->description ?? null,
                    'vendor_id'   => 1,
                    'status'      => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];

                DB::table('create_media_tables')->insert($pAData);
            }
        }

        return back()->with('success', 'Media Uploaded Successfully');
    }
    public function status($id)
    {
        $model = CreateMediaTable::find($id);

        // toggle between 1 and 0
        $model->status = ($model->status == 1) ? 0 : 1;
        $model->save();

        return redirect()->back()->with('success', 'Status Updated');
    }
    public function delete(Request $request, $id)
    {
        $coupon = CreateMediaTable::find($id);
        if (!$coupon) return redirect()->back()->with('error', 'Media not found');
        $coupon->delete();
        return redirect()->back()->with('success', 'Media Deleted Successfully...');
    }
    public function bulkAction(Request $request)
    {
        // $ids = (array) $request->ids;
        $ids = $request->ids ?? [];
        $action = $request->action;
        if (!$ids || !$action) {
            return back()->with('error', 'Select items and action');
        }
        switch ($action) {
            case 'activate':
                CreateMediaTable::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                CreateMediaTable::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'delete':
                // CreateMediaTable::whereIn('id', $ids)->delete();
                return back()->with('error', 'Delete action is not allowed ❌');
                break;
        }
        return back()->with([
            'bulk-success' => $request->action,
            'ids' => is_array($ids) ? $ids : [$ids], // ✅ FIX
        ]);
        // return back()->with('success', 'Bulk action applied');
    }
}
