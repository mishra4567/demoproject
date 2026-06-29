<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RoleHelper;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BrandsController  extends Controller
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
        $data = DB::table('brands')
            ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
            ->select('brands.*', 'create_media_tables.file_name')
            ->where('brands.is_deleted', 0)
            ->get()
            ->each(function ($brands) {
                $brands->locked = $brands->is_vendor === 'VENDOR';
            });

        $deleteData = DB::table('brands')
            ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
            ->select('brands.*', 'create_media_tables.file_name')
            ->where('brands.is_deleted', 1)
            ->get()
            ->each(function ($brands) {
                $brands->locked = $brands->is_vendor === 'VENDOR';
            });

        return view('admin.brands.brands', compact('data', 'deleteData'));
        // echo "This is for brands" ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function managebrands(Request $request, $id = null)
    {
        if (!empty($id) && is_numeric($id)) {
            // $brands = Brands::where('id', $id)->first();
            $brands = DB::table('brands')
                ->leftJoin('create_media_tables', 'brands.media_ids', '=', 'create_media_tables.id')
                ->select('brands.*', 'create_media_tables.file_name')
                ->where('brands.id', $id)
                ->first();
            if (!$brands) {
                abort(404);
            }
            $result['name']  = $brands->name;
            $result['image']  = $brands->file_name;
            $result['status'] = $brands->status;
            $result['id']     = $brands->id;
        } else {
            $result['name']  = '';
            $result['image']  = '';
            $result['status'] = '';
            $result['id']     = 0;
        }
        return view('admin.brands.manage_brands', $result);
        // echo "This is for manage brands";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function managebrandsprocess(Request $request)
    {
        // Role logic
        if (RoleHelper::cannot('brands', 'create')) {
            return redirect()->back()
                ->with('error', 'You do not have permission to add products.');
        }
        // Code frome chatgpt
        $id = $request->post('id');
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('brands', 'name')->ignore($id),
            ],
        ], [
            'name.required' => 'Brand name is required',
            'name.unique' => 'This brand already exists',
        ]);
        $model = $id ? Brands::findOrFail($id) : new Brands();
        $model->name = $request->name;
        $model->media_ids = $request->media_ids;
        $model->status = 1;
        $model->is_vendor = 'ADMIN';
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
        return redirect('admin/brands')
            ->with('success', $id ? 'brands Updated Successfully' : 'brands Inserted Successfully');
        // return $request->post();
        // echo "this is for brands manage";
    }

    /**
     * Display the specified resource.
     */
    public function delete(Request $request, $id)
    {
        // // it is get methode to performe delete
        // // we have post methode to delete
        // // brands delete
        $brands = Brands::find($id);
        if (!$brands) return redirect('admin/brands')->with('error', 'brands not found');
        $brands->update([
            'is_deleted' => 1,
            'who_delete' => $this->adminId(),
            'deleted_at' => now(),
        ]);
        return redirect('admin/brands')->with('success', 'brands Deleted Successfully...');
        // echo "brands deleted" ;
        // echo "this is for brands delete";
    }
    public function restore(Request $request, $id)
    {
        $brands = Brands::find($id);
        if (!$brands) return redirect('admin/brands')->with('error', 'brands not found');
        $brands->update([
            'is_deleted' => 0,
            'who_delete' => null,
            'deleted_at' => null,
        ]);
        return redirect()->route('brands')->with('success', 'brands Restored Successfully...');
    }

    public function permanentDelete(Request $request, $id)
    {
        if (RoleHelper::cannot('brands', 'delete')) {
            return redirect()->back()
                ->with('error', 'You do not have permission to delete products.');
        }
        // $brands = Brands::find($id);
        // if (!$brands) return redirect('admin/brands')->with('error', 'brands not found');
        // $brands->delete();
        // return redirect()->route('brands')->with('success', 'brands Permanently Deleted Successfully...');
        return back()->with('error', 'Delete action is not allowed ❌');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $brands = Brands::find($id);
        if (!$brands) {
            return back()->with('error', 'Brand not found');
        }
        $newStatus = $brands->status == 0 ? 1 : 0;
        $brands->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->adminId() : null,
            'statusupdate_at' => $newStatus == 0 ? now() : null,
        ]);
        return redirect()->back()->with('success', 'Status Updated');
        // echo "this is for brands status";
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
                Brands::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => $this->adminId(),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                Brands::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ]);
                break;
            case 'trash':
                Brands::whereIn('id', $ids)
                    ->where(function ($q) {
                        $q->where('is_vendor', '!=', 'VENDOR')
                            ->orWhereNull('is_vendor');
                    })
                    ->update([
                        'is_deleted' => 1,
                        'deleted_at' => now(),
                        'who_delete' => $this->adminId()
                    ]);
                // return back()->with('error', 'Delete  action is not allowed ❌');
                break;
            case 'restore':
                Brands::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null
                ]);
                break;
            case 'permanent_delete':
                // Brands::whereIn('id', $ids)->delete();
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
