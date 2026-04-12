<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class CategoryController extends Controller
{
    /**
     * Index Page for Category
     */
    public function index()
    {
        $result['data'] = Category::all();
        return view('admin.category', $result);
    }

    /**
     * Index Pages for Manage Category
     */
    public function managecategory(Request $request, $id = null)
    {
        $parentQuery = Category::where('status', 1);
        if (!empty($id) && is_numeric($id)) {

            $category = Category::findOrFail($id);

            if (!$category) {
                abort(404);
            }
            $parent_categories = $parentQuery->where('id', '!=', $id)->get();

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
        $model->status = 1;
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
        // it is get methode to performe delete
        // we have post methode to delete
        // category delete
        $category = Category::find($id);
        if (!$category) return redirect('admin/category')->with('error', 'Category not found');

        $category->delete();

        return redirect('admin/category')->with('success', 'Category Deleted Successfully...');

        // echo "category deleted" ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function status($id)
    {
        $model = Category::find($id);

        // toggle between 1 and 0
        $model->status = ($model->status == 1) ? 0 : 1;
        $model->save();

        return redirect()->back()->with('success', 'Status Updated');
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
                Category::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'deactivate':
                Category::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'delete':
                // Category::whereIn('id', $ids)->delete();
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
