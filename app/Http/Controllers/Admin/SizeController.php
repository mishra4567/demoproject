<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Size::where('is_deleted', 0)->get();
        $deletedData = Size::where('is_deleted', 1)->get();
        $info = config('field_info.size');
        return view('admin.size.size', compact('data', 'deletedData', 'info'));
        // echo "This is for size" ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manageSize(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {

            $size = Size::where('id', $id)->first();

            if (!$size) {
                abort(404);
            }
            $result = [
                'size'   => $size->size,
                'status' => $size->status,
                'id'     => $size->id,
            ];
        } else {
            $result = [
                'size'   => '',
                'status' => '',
                'id'     => 0,
            ];
        }
        $result['info'] = config('field_info.size');
        return view('admin.size.manage_size', $result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function managesizeprocess(Request $request)
    {
        // Code frome chatgpt
        $id = $request->post('id');
        $request->validate([
            'size' => [
                'required',
                Rule::unique('sizes', 'size')->ignore($id),
            ],
        ], [
            'size.unique' => 'This size already exists',
        ]);
        $model = $id ? Size::findOrFail($id) : new Size();
        $model->size = $request->size;
        $model->who_create = session('ADMIN_ID');
        $model->created_at = now();
        $model->status = 1;
        $model->save();

        return redirect('admin/size')
            ->with('success', $id ? 'Size Updated Successfully' : 'Size Inserted Successfully');
        // return $request->post();

        // echo "this is for size manage";
    }

    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        // // it is get methode to performe delete
        // // we have post methode to delete
        // // size delete
        $size = Size::find($id);
        if (!$size) return redirect('admin/size')->with('error', 'size not found');
        $size->is_deleted = 1;
        $size->deleted_at = now();
        $size->who_delete = session('ADMIN_ID');
        $size->save();
        return redirect('admin/size')->with('success', 'size Deleted Successfully...');
        // echo "size deleted" ;
        // echo "this is for size delete";
    }
    public function restore(Request $request, $id)
    {
        $size = Size::find($id);
        if (!$size) return redirect('admin/size')->with('error', 'size not found');
        $size->is_deleted = 0;
        $size->deleted_at = null;
        $size->who_delete = null;
        $size->save();
        return redirect('admin/size')->with('success', 'size Restored Successfully...');
    }
    public function permanentDelete(Request $request, $id)
    {
        // $size = Size::find($id);
        // if (!$size) return redirect('admin/size')->with('error', 'size not found');
        // $size->delete();
        // return redirect('admin/size')->with('success', 'size Permanently Deleted Successfully...');
        return back()->with('error', 'Delete action is not allowed ❌');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $size = Size::find($id);

        // toggle between 1 and 0
        $size->status = ($size->status == 1) ? 0 : 1;
        $size->save();

        return redirect()->back()->with('success', 'Status Updated');
        // echo "this is for size status";
    }

    /**
     * Bulk Action
     */
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
                Size::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                Size::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'trash':
                // Category::whereIn('id', $ids)->delete();
                Size::whereIn('id', $ids)->update([
                    'is_deleted' => 1,
                    'deleted_at' => now(),
                    'who_delete' => session('ADMIN_ID'),
                ]);
                // return back()->with('error', 'Delete action is not allowed ❌');
                break;
            case 'restore':
                Size::whereIn('id', $ids)->update([
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
