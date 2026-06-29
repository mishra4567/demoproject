<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class CategoryController extends Controller
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
     * Index Page for Category
     */
    public function index()
    {
        // $data = Category::where('is_deleted', 0)->get();
        $data = Category::where(function ($q) {
            $q->where('is_deleted', 0)
                ->orWhereNull('is_deleted');
        })
            ->latest()
            ->get()
            ->each(function ($data) {
                $data->locked = $data->is_vendor === 'VENDOR';
            });
        $deletedData = Category::where('is_deleted', 1)
            ->where('is_vendor', 'ADMIN')
            ->get()
            ->each(function ($data) {
                $data->locked = $data->is_vendor === 'VENDOR';
            });
        $info = config('field_info.category');
        return view('admin.category', compact('data', 'deletedData', 'info'));
    }

    /**
     * Index Pages for Manage Category
     */
    public function managecategory(Request $request, $id = null)
    {
        $parentQuery = Category::where('status', 1)
            ->where('parent_id', 0)
            ->where(function ($q) {
                $q->where('is_deleted', 0)
                    ->orWhereNull('is_deleted');
            })
            ->where('is_vendor', 'ADMIN')
            ->where('created_by', $this->adminId());
        if (!empty($id) && is_numeric($id)) {
            $category = Category::findOrFail($id);
            if (!$category) {
                abort(404);
            }
            $parent_categories = $parentQuery->where('id', '!=', $id)
                ->orderBy('category_name')
                ->get(['id', 'category_name']);
            $result = [
                'category_name' => $category->category_name,
                'category_slug' => $category->category_slug,
                'parent_id'     => $category->parent_id ?? 0,
                'id'            => $category->id,
                'parent_categories' => $parent_categories,
            ];
        } else {

            $result = [
                'category_name' => '',
                'category_slug' => '',
                'parent_id'     => 0,
                'id'            => 0,
                'parent_categories' => $parentQuery
                    ->orderBy('category_name')
                    ->get(),
            ];
        }
        $result['info'] = config('field_info.category');

        return view('admin.manage_category', $result);
    }

    /**
     * Validate and Insert Category Data
     *
     * Later i want to add activate or deactivate
     */
    public function managecategoryprocess(Request $request)
    {
        // Code frome chatgpt
        $id = $request->post('id');

        $request->validate([
            'category_name' => 'required',
            'category_slug' => [
                'required',
                Rule::unique('categories', 'category_slug')->ignore($id),
            ],
        ], [
            'category_slug.unique' => 'This Category already exists',
        ]);

        $model = $id ? Category::findOrFail($id) : new Category();

        $model->category_name = $request->category_name;
        $model->category_slug = $request->category_slug;
        $model->parent_id = $request->parent_id;
        // $model->who_created = session('ADMIN_ID');
        // $model->created_at = now();
        $model->status = 1;
        $model->is_vendor = 'ADMIN';
        if ($id) {
            $model->who_edited = session('ADMIN_NAME');
            $model->edited_by =  session('ADMIN_ID');
            $model->edited_at = now();
        } else {
            $model->who_create = session('ADMIN_NAME');
            $model->created_by = session('ADMIN_ID');
            $model->created_at = now();
        }
        $model->save();

        return redirect('admin/category')
            ->with('success', $id ? 'Category Updated Successfully' : 'Category Inserted Successfully');


        // return $request->post();
    }

    /**
     * Display the specified resource.
     *
     * Later i want add delete to show or not show
     */
    public function delete(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'is_deleted' => 1,
            'who_delete' => session('ADMIN_ID'),
            'deleted_at' => now(),
        ]);

        return redirect('admin/category')
            ->with('success', 'Category moved to trash!');
    }

    public function restore(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);
        return redirect('admin/category')
            ->with('success', 'Category Restored Successfully...');
    }

    public function permanentDelete($id)
    {
        return back()->with('error', 'Delete action is not allowed ❌');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $model = Category::findOrFail($id);
        $newStatus = $model->status == 1 ? 0 : 1;
        $model->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? session('ADMIN_ID') : null,
            'statusupdate_at' => $newStatus == 0 ? now() : null,
        ]);
        return back()->with('success', 'Status Updated');
    }

    /**
     * Bulk Action
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->ids ?? [];
        $action = $request->action;
        if (!$ids || !$action) {
            return back()->with('error', 'Select items and action');
        }
        switch ($action) {
            case 'activate':
                Category::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => session('ADMIN_ID'),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                Category::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => session('ADMIN_ID'),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'trash':
                Category::whereIn('id', $ids)
                    ->where(function ($q) {
                        $q->where('is_vendor', '!=', 'VENDOR')
                            ->orWhereNull('is_vendor');
                    })
                    ->update([
                        'is_deleted' => 1,
                        'deleted_at' => now(),
                        'who_delete' => session('ADMIN_ID'),
                    ]);
                break;
            case 'restore':
                Category::whereIn('id', $ids)->update([
                    'is_deleted' => 0,
                    'deleted_at' => null,
                    'who_delete' => null,
                ]);
                break;
            case 'permanent_delete':
                return back()->with('error', 'Permanent delete is not allowed ❌');
        }
        return back()->with([
            'bulk-success' => $action,
            'ids' => (array) $ids,
        ]);
    }
}
