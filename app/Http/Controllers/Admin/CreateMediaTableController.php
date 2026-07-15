<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreateMediaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CreateMediaTableController extends Controller
{
    protected function adminId()
    {
        return session('ADMIN_ID');
    }

    protected function adminName()
    {
        return session('ADMIN_NAME');
    }
    // Index of Media
    public function index()
    {
        // $result['data'] = Product::all();
        $media = CreateMediaTable::Where('is_deleted', 0)
            ->get()
            ->each(function ($color) {
                $color->locked = $color->is_vendor === 'VENDOR';
            });
        $deletedMedia = CreateMediaTable::Where('is_deleted', 1)
            ->get()
            ->each(function ($color) {
                $color->locked = $color->is_vendor === 'VENDOR';
            });
        return view('admin.uploadmedia.media', compact('media', 'deletedMedia'));
    }

    public function manageMedia(Request $request,)
    {
        $info = config('field_info.media');
        return view('admin.uploadmedia.uploadmedia', compact('info'));
    }
    // public function mediasearch(Request $request)
    // {
    //     $query = $request->get('query', '');

    //     $media = CreateMediaTable::where('status', 1)
    //         ->where('is_deleted', 0)
    //         ->when($query, function ($q) use ($query) {
    //             $q->where('tags', 'LIKE', '%' . $query . '%');
    //         })
    //         ->where('is_vendor', 'ADMIN')
    //         ->get();
    //     return response()->json($media);
    // }

    public function mediasearch(Request $request)
    {
        $query = $request->get('query', '');

        $media = CreateMediaTable::where('is_deleted', 0)
            ->where('is_vendor', 'ADMIN')
            ->when($query, function ($q) use ($query) {
                $q->where('tags', 'LIKE', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($media);
    }

    public function store(Request $request)
    {
        // ✅ Make media nullable — URL rows won't have a file
        $request->validate([
            'media.*'       => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:20480',
            'media_url.*'   => 'nullable|url|max:2048',
            'tags.*'        => 'nullable|max:10',
            'description.*' => 'nullable|max:500',
        ]);

        $uploaded = 0;
        $errors   = [];

        // ✅ Handle file uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $key => $file) {
                if (!$file) continue;

                $extension = strtolower($file->getClientOriginalExtension());
                $fileName  = time() . '_' . uniqid() . '.' . $extension;
                $file->move(public_path('storage/media'), $fileName);

                CreateMediaTable::create([
                    'file_name'   => $fileName,
                    'media_type'  => $extension,
                    'tags'        => $request->tags[$key] ?? null,
                    'description' => $request->description[$key] ?? null,
                    'is_vendor'   => 'ADMIN',
                    'created_by'  => $this->adminId(),
                    'who_create'  => $this->adminName(),
                    'status'      => 1,
                    'created_at'  => now(),
                ]);
                $uploaded++;
            }
        }

        // ✅ Handle URL imports
        if ($request->filled('media_url')) {
            foreach ($request->media_url as $key => $url) {
                if (empty(trim($url))) continue;

                try {
                    // ✅ Download file from URL
                    $context  = stream_context_create([
                        'http' => [
                            'timeout'       => 15,
                            'user_agent'    => 'Mozilla/5.0',
                            'ignore_errors' => true,
                        ],
                        'ssl' => [
                            'verify_peer'      => false,
                            'verify_peer_name' => false,
                        ],
                    ]);

                    $contents = file_get_contents($url, false, $context);

                    if (!$contents) {
                        $errors[] = "Could not download: {$url}";
                        continue;
                    }

                    // ✅ Detect extension from URL path
                    $urlPath   = parse_url($url, PHP_URL_PATH);
                    $extension = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));

                    // ✅ Clean extension (remove query strings like ?v=1)
                    $extension = explode('?', $extension)[0];

                    // ✅ Fallback: detect from file content
                    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'mp4', 'mov', 'avi', 'webm'])) {
                        $finfo     = new \finfo(FILEINFO_MIME_TYPE);
                        $mime      = $finfo->buffer($contents);
                        $extension = match ($mime) {
                            'image/jpeg'     => 'jpg',
                            'image/png'      => 'png',
                            'image/webp'     => 'webp',
                            'image/gif'      => 'gif',
                            'video/mp4'      => 'mp4',
                            'video/quicktime' => 'mov',
                            'video/x-msvideo' => 'avi',
                            'video/webm'     => 'webm',
                            default          => 'jpg',
                        };
                    }

                    // ✅ Normalize jpeg → jpg
                    if ($extension === 'jpeg') $extension = 'jpg';

                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    file_put_contents(public_path('storage/media/' . $fileName), $contents);

                    CreateMediaTable::create([
                        'file_name'   => $fileName,
                        'media_type'  => $extension,
                        'tags'        => $request->tags[$key] ?? null,
                        'description' => $request->description[$key] ?? null,
                        'is_vendor'   => 'ADMIN',
                        'created_by'  => $this->adminId(),
                        'who_create'  => $this->adminName(),
                        'status'      => 1,
                        'created_at'  => now(),
                    ]);
                    $uploaded++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to import {$url}: " . $e->getMessage();
                    continue;
                }
            }
        }

        // ✅ Nothing was uploaded
        if ($uploaded === 0 && empty($errors)) {
            $msg = 'No files or URLs provided.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        // ✅ Build response message
        $message = "{$uploaded} file(s) uploaded successfully.";
        if (!empty($errors)) {
            $message .= ' ' . count($errors) . ' failed: ' . implode(', ', $errors);
        }

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'uploaded' => $uploaded,
                'errors'   => $errors,
                'message'  => $message,
            ]);
        }

        return redirect()->route('media')->with(
            empty($errors) ? 'success' : 'warning',
            $message
        );
    }

    public function status($id)
    {
        $model = CreateMediaTable::find($id);
        if (!$model) {
            return back()->with('error', 'Media not found');
        }
        $newStatus = $model->status == 0 ? 1 : 0;
        $model->update([
            'status'          => $newStatus,
            'statusupdate_by' => $newStatus == 0 ? $this->adminId() : null,
            'statusupdate_at' => $newStatus == 0 ? now() : null,
        ]);
        return redirect()->back()->with('success', 'Status Updated');
    }

    public function delete(Request $request, $id)
    {
        $mediaTable = CreateMediaTable::find($id);
        if (!$mediaTable) return redirect()->back()->with('error', 'Media not found');
        if ($mediaTable->is_vendor === 'VENDOR') {
            return back()->with(
                'error',
                'Vendor media cannot be deleted.'
            );
        }
        $mediaTable->update([
            'is_deleted' => 1,
            'who_delete' => $this->adminId(),
            'deleted_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Media Deleted Successfully...');
    }

    public function restore(Request $request, $id)
    {
        $mediaTable = CreateMediaTable::find($id);
        if (!$mediaTable) return redirect()->back()->with('error', 'Media not found');
        $mediaTable->update([
            'is_deleted' => 0,
            'who_delete' => null,
            'deleted_at' => null,
        ]);
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
                CreateMediaTable::whereIn('id', $ids)->update([
                    'status' => 1,
                    'statusupdate_by' => $this->adminId(),
                    'statusupdate_at' => now(),
                ]);
                break;
            case 'deactivate':
                CreateMediaTable::whereIn('id', $ids)->update([
                    'status' => 0,
                    'statusupdate_by' => null,
                    'statusupdate_at' => null,
                ]);
                break;
            case 'trash':
                CreateMediaTable::whereIn('id', $ids)
                    ->where(function ($q) {
                        $q->where('is_vendor', '!=', 'VENDOR')
                            ->orWhereNull('is_vendor');
                    })
                    ->update([
                        'is_deleted' => 1,
                        'deleted_at' => now(),
                        'who_delete' => $this->adminId()
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

    /**
     * Simple serve — small files, direct response
     */
    public function serveMedia(string $filename)
    {
        $path = public_path('/storage/media/' . $filename);
        if (!File::exists($path)) {
            abort(404, 'Media not found');
        }
        // Security: only allow files owned by this admin OR vendor
        $media = CreateMediaTable::where('file_name', $filename)
            ->first();
        if (!$media) {
            abort(403, 'Access denied');
        }
        $mimeType = match (strtolower(File::extension($path))) {
            'mp4'       => 'video/mp4',
            'mov'       => 'video/quicktime',
            'avi'       => 'video/x-msvideo',
            'webm'      => 'video/webm',
            'jpg', 'jpeg' => 'image/jpeg',
            'png'       => 'image/png',
            'webp'      => 'image/webp',
            default     => 'application/octet-stream',
        };
        return response()->file($path, [
            'Content-Type'  => $mimeType,
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    /**
     * Streamed serve — large videos with HTTP Range (seek support)
     */
    public function streamMedia(string $filename): StreamedResponse
    {
        $path = public_path('/storage/media/' . $filename);
        if (!File::exists($path)) {
            abort(404, 'Media not found');
        }
        // Security check
        $media = CreateMediaTable::where('file_name', $filename)
            ->first();
        if (!$media) {
            abort(403, 'Access denied');
        }
        $mimeType = match (strtolower(File::extension($path))) {
            'mp4'  => 'video/mp4',
            'mov'  => 'video/quicktime',
            'avi'  => 'video/x-msvideo',
            'webm' => 'video/webm',
            default => 'video/mp4',
        };
        $fileSize = File::size($path);
        $start    = 0;
        $end      = $fileSize - 1;
        $status   = 200;
        $headers  = [
            'Content-Type'   => $mimeType,
            'Accept-Ranges'  => 'bytes',
            'Cache-Control'  => 'private, max-age=3600',
            'Content-Length' => $fileSize,
        ];
        // Handle Range request (browser seeking)
        if (request()->hasHeader('Range')) {
            $range = request()->header('Range');
            preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
            $start = (int) $matches[1];
            $end   = isset($matches[2]) && $matches[2] !== ''
                ? (int) $matches[2]
                : $fileSize - 1;
            $length = $end - $start + 1;
            $status = 206; // Partial Content
            $headers['Content-Range']  = "bytes {$start}-{$end}/{$fileSize}";
            $headers['Content-Length'] = $length;
        }
        $chunkSize = 1024 * 512; // 512KB chunks
        return response()->stream(function () use ($path, $start, $end, $chunkSize) {
            $handle = fopen($path, 'rb');
            fseek($handle, $start);
            $remaining = $end - $start + 1;
            while (!feof($handle) && $remaining > 0) {
                $read   = min($chunkSize, $remaining);
                $buffer = fread($handle, $read);
                echo $buffer;
                flush();
                $remaining -= $read;
            }
            fclose($handle);
        }, $status, $headers);
    }
}
