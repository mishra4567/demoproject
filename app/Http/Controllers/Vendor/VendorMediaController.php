<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CreateMediaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VendorMediaController extends BaseVendorController
{
    private function authorise(CreateMediaTable $media): void
    {
        abort_if(
            (int) $media->created_by !== (int) $this->vendorId()
                || $media->is_vendor !== $this->vendor(),
            403
        );
    }
    public function mediaIndex()
    {
        $media = CreateMediaTable::where('created_by', $this->vendorId())
            ->where('is_vendor', $this->vendor())
            ->where(function ($q) {
                $q->where('is_deleted', 0)
                    ->orWhereNull('is_deleted');
            })
            ->latest()
            ->paginate(20);

        return Inertia::render('Pages/Media/ViewMedia', [
            'media' => $media,
        ]);
    }
    public function create()
    {
        return Inertia::render('Pages/Media/UploadMedia');
    }
    public function store(Request $request)
    {
        $request->validate([
            'media.*'       => 'required|mimes:jpg,jpeg,png,webp,mp4,mov,avi',
            'tags.*'        => 'nullable|max:10',
            'description.*' => 'nullable|max:500',
        ]);

        if ($request->hasFile('media')) {

            foreach ($request->file('media') as $key => $file) {

                $extension = strtolower($file->getClientOriginalExtension());

                $fileName = time() . '_' . uniqid() . '.' . $extension;

                $file->move(public_path('storage/media'), $fileName);

                DB::table('create_media_tables')->insert([
                    'file_name'   => $fileName,
                    'media_type'  => $extension,
                    'tags'        => $request->tags[$key] ?? null,
                    'description' => $request->description[$key] ?? null,
                    'created_by'   => $this->vendorId(),
                    'status'      => 1,
                    'is_vendor'   => $this->vendor(),
                    'who_create'  => $this->vendorName(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        return back()->with('success', 'Media uploaded successfully');
    }
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        return CreateMediaTable::query()
            ->where('status', 1)
            ->where('is_vendor', $this->vendor())
            ->where('created_by', $this->vendorId())
            ->where(function ($q) {
                $q->where('is_deleted', 0)
                    ->orWhereNull('is_deleted');
            })
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('tags', 'like', "%{$query}%")
                        ->orWhere('file_name', 'like', "%{$query}%");
                });
            })
            ->select('id', 'file_name', 'tags')
            ->latest('id')
            ->get();
    }
}
