<?php

// app/Http/Controllers/Vendor/VendorCategoryController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorCategoryController extends Controller
{
    private function vendorId()
    {
        return Auth::guard('vendor')->id();
    }

    private function vendorName()
    {
        return Auth::guard('vendor')->user()->name;
    }

    private function authorise(Category $category): void
    {
        abort_if((int) $category->created_by !== (int) $this->vendorId(), 403);
    }

    public function index()
    {
        $data = Category::where('is_deleted', 0)
            ->where('created_by', $this->vendorId())
            ->latest()->get();

        $deletedData = Category::where('is_deleted', 1)
            ->where('created_by', $this->vendorId())
            ->latest()->get();

        $parents = Category::where('status', 1)
            ->where('is_deleted', 0)
            ->orderBy('category_name')
            ->get(['id', 'category_name']);

        return Inertia::render(
            'Pages/Categories/Index',
            compact('data', 'deletedData', 'parents')
        );
    }

    public function save(Request $request)
    {
        $id = $request->post('id');

        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_slug' => [
                'required',
                Rule::unique('categories', 'category_slug')->ignore($id),
            ],
        ], [
            'category_slug.unique' => 'This category already exists',
        ]);

        $model = $id ? Category::findOrFail($id) : new Category();

        $model->category_name = $request->category_name;
        $model->category_slug = $request->category_slug;
        $model->parent_id     = $request->parent_id ?? 0;
        $model->status        = 1;

        if ($id) {
            $model->who_edited = $this->vendorName();
            $model->edited_by  = $this->vendorId();
            $model->edited_at  = now();
        } else {
            $model->who_created = $this->vendorName();
            $model->created_by  = $this->vendorId();
            $model->created_at  = now();
        }

        $model->save();

        return back()->with(
            'success',
            $id ? 'Category updated successfully!' : 'Category created successfully!'
        );
    }

    public function status(Category $category)
    {
        $this->authorise($category);
        $newStatus = $category->status == 0 ? 1 : 0;
        $category->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->vendorId() : null,
            'statusupdate_at' => $newStatus == 0 ? now()             : null,
        ]);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Category $category)
    {
        $this->authorise($category);
        $category->update([
            'is_deleted' => 1,
            'who_delete' => $this->vendorName(),
            'deleted_at' => now(),
        ]);
        return back()->with('success', 'Category moved to trash!');
    }

    public function restore(Category $category)
    {
        $this->authorise($category);
        $category->update([
            'is_deleted' => 0,
            'deleted_at' => null,
            'who_delete' => null,
        ]);
        return back()->with('success', 'Category restored!');
    }

    public function permanentDelete(Category $category)
    {
        return back()->with('error', 'Category permanently delete not allowed!');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,trash,restore,permanent_delete',
            'ids'    => 'required|array',
        ]);

        $categories = Category::where('created_by', $this->vendorId())
            ->whereIn('id', $request->ids);

        $count   = $categories->count();
        $message = '';

        match ($request->action) {
            'activate'   => ($categories->update([
                'status' => 1,
                'statusupdate_by' => $this->vendorId(),
                'statusupdate_at' => now(),
            ])
                && $message = "{$count} category(s) activated!"),
            'deactivate' => ($categories->update([
                'status' => 0,
                'statusupdate_by' => null,
                'statusupdate_at' => null,
            ])
                && $message = "{$count} category(s) deactivated!"),
            'trash'      => ($categories->update([
                'is_deleted' => 1,
                'who_delete' => $this->vendorId(),
                'deleted_at' => now(),
            ])
                && $message = "{$count} category(s) moved to trash!"),
            'restore'    => ($categories->update([
                'is_deleted' => 0,
                'who_delete' => null,
                'deleted_at' => null,
            ])
                && $message = "{$count} category(s) restored!"),
            'permanent_delete' => $message = 'Category permanently delete not allowed!',
        };

        if ($request->action === 'permanent_delete') {
            return back()->with('error', $message);
        }

        return back()->with('success', $message);
    }
}
