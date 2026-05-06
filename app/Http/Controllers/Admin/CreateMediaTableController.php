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
        $media = CreateMediaTable::Where('is_deleted', 0)->get();
        $deletedMedia = CreateMediaTable::Where('is_deleted', 1)->get();
        return view('admin.uploadmedia.media', compact('media', 'deletedMedia'));
    }

    public function manageMedia(Request $request,)
    {
        $info = config('field_info.media');
        return view('admin.uploadmedia.uploadmedia', compact('info'));
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
                    'who_create'  => session('ADMIN_ID'),
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
        $mediaTable = CreateMediaTable::find($id);
        if (!$mediaTable) return redirect()->back()->with('error', 'Media not found');
        $mediaTable->is_deleted = 1;
        $mediaTable->deleted_at = now();
        $mediaTable->who_delete = session('ADMIN_ID');
        $mediaTable->save();
        return redirect()->back()->with('success', 'Media Deleted Successfully...');
    }
    public function restore(Request $request, $id)
    {
        $mediaTable = CreateMediaTable::find($id);
        if (!$mediaTable) return redirect()->back()->with('error', 'Media not found');
        $mediaTable->is_deleted = 0;
        $mediaTable->who_delete = null;
        $mediaTable->save();
        return redirect()->back()->with('success', 'Media Restored Successfully...');
    }
    public function permanentDelete($id)
    {
        // CreateMediaTable::findOrFail($id)->delete();
        // return redirect()->back()->with('success', 'Media permanently deleted.');
        return back()->with('error', 'Delete action is not allowed ❌');
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
            case 'trash':
                CreateMediaTable::whereIn('id', $ids)->update([
                    'is_deleted' => 1,
                    'deleted_at' => now(),
                    'who_delete' => session('ADMIN_ID')
                ]);
                // return back()->with('error', 'Delete action is not allowed ❌');
                break;
            case 'restore':
                CreateMediaTable::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
                // ::whereIn('id', $ids)->delete();
                return back()->with('error', 'Permanent delete is not allowed ❌');
                break;
        }
        return back()->with([
            'bulk-success' => $request->action,
            'ids' => is_array($ids) ? $ids : [$ids], // ✅ FIX
        ]);
        // return back()->with('success', 'Bulk action applied');
    }
}
