<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SizeController extends Controller
{
    protected function adminId()
    {
        return session('ADMIN_ID');
    }

    protected function adminName()
    {
        return session('ADMIN_NAME');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Size::where(function ($q) {
            $q->where('is_deleted', 0)
                ->orWhereNull('is_deleted');
        })
            ->get()
            ->each(function ($size) {
                $size->locked = $size->is_vendor === 'VENDOR';
            });
        $deletedData = Size::where(function ($q) {
            $q->where('is_deleted', 1)
                ->orWhereNull('is_deleted');
        })
            ->get()
            ->each(function ($size) {
                $size->locked = $size->is_vendor === 'VENDOR';
            });
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
            $standardTypes = ['Clothing', 'Shoes', 'Other'];
            $isCustomType  = !in_array($size->type, $standardTypes);

            $result = [
                'size'   => $size->size,
                'type'        => $isCustomType ? 'Other' : $size->type,   // ← dropdown shows "Other"
                'custom_type' => $isCustomType ? $size->type : '',         // ← input shows the actual value
                'details' => $size->details,
                'status' => $size->status,
                'id'     => $size->id,
            ];
        } else {
            $result = [
                'size'   => '',
                'type'    => '',
                'custom_type' => '',
                'details' => '',
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
            ],
            'type' => 'nullable|string|max:50',
            'details' => 'nullable|string|max:255',
        ]);
        // type checking
        $type = $request->type;
        if ($type === 'Other' && !empty($request->custom_type)) {
            $type = $request->custom_type;
        }
        $model = $id ? Size::findOrFail($id) : new Size();
        $model->size = $request->size;
        $model->type = $type;
        $model->details = $request->details;
        $model->is_vendor = 'ADMIN';
        $model->status = 1;
        if ($id) {
            $model->who_edited = $this->adminName();
            $model->edited_by =  $this->adminId();
            $model->edited_at = now();
        } else {
            $model->who_create = $this->adminName();
            $model->created_by = $this->adminId();
            $model->created_at = now();
        }
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
        $size->update([
            'is_deleted' => 1,
            'who_delete' => $this->adminId(),
            'deleted_at' => now(),
        ]);
        return redirect('admin/size')->with('success', 'size Deleted Successfully...');
        // echo "size deleted" ;
        // echo "this is for size delete";
    }
    public function restore(Request $request, $id)
    {
        $size = Size::find($id);
        if (!$size) return redirect('admin/size')->with('error', 'size not found');
        $size->update([
            'is_deleted' => 0,
            'who_delete' => null,
            'deleted_at' => null,
        ]);
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
        if (!$size) {
            return back()->with('error', 'Size not found');
        }
        $newStatus = $size->status == 0 ? 1 : 0;
        $size->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->adminId() : null,
            'statusupdate_at' => $newStatus == 0 ? now() : null,
        ]);
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
                Size::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => $this->adminId(),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                Size::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ]);
                break;
            case 'trash':
                // Category::whereIn('id', $ids)->delete();
                Size::whereIn('id', $ids)
                    ->where(function ($q) {
                        $q->where('is_vendor', '!=', 'VENDOR')
                            ->orWhereNull('is_vendor');
                    })
                    ->update([
                        'is_deleted' => 1,
                        'deleted_at' => now(),
                        'who_delete' => $this->adminId(),
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
