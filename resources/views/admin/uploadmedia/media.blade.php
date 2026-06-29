{{-- ./views/uploadmedia/media.blade.php --}}
@extends('admin.layout.layout')
@section('page_title', 'Media')
@section('media_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="title-5 m-b-0">Media</h3>
                <a href="{{ route('media.managemedia') }}">
                    <button type="button" class="btn btn-success">
                        <i class="fa fa-plus me-1"></i> Add Media
                    </button>
                </a>
            </div>

            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-secondary" id="trash_toggle_btn" onclick="toggleTrash()">
                    <i class="fa fa-trash me-1"></i> Show Deleted
                    @if ($deletedMedia->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $deletedMedia->count() }}</span>
                    @endif
                </button>
            </div>

            <div class="row">
                <form action="{{ route('media.bulkAction') }}" method="POST">
                    @csrf
                    <input type="hidden" name="bulk_type" id="bulk_type" value="active">

                    {{-- Bulk Action --}}
                    <div class="mb-3 d-flex gap-2">
                        <select name="action" id="bulk_action" class="form-control w-auto">
                            <option value="">Bulk Action</option>
                            <option value="activate" class="active-option">Activate</option>
                            <option value="deactivate" class="active-option">Deactivate</option>
                            <option value="trash" class="active-option">Move to Trash</option>
                            <option value="restore" class="deleted-option d-none">Restore</option>
                            <option value="permanent_delete" class="deleted-option d-none">Delete</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>

                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"></th>
                                    <th>ID</th>
                                    <th>Added By</th>
                                    <th>Media</th>
                                    <th>Media Type</th>
                                    <th>Tag</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- Active Rows --}}
                                @forelse ($media as $list)
                                    <tr class="active-row">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>
                                            <p class="small">By:- {{ $list->is_vendor }}</p>
                                            <p class="small">Created By:- {{ $list->who_create }}</p>
                                            <p class="small">Edited By:- {{ $list->who_edited }}</p>
                                        </td>
                                        <td>
                                            {{-- ✅ Use stream route + partial --}}
                                            @include('admin.partials.media_preview', [
                                                'media' => $list,
                                                'size' => 'sm',
                                            ])
                                        </td>
                                        <td>
                                            {{ strtoupper($list->media_type) }}
                                            {{-- ✅ Video badge --}}
                                            @if (in_array(strtolower($list->media_type), ['mp4', 'mov', 'avi', 'webm']))
                                                <span class="badge bg-secondary ms-1" style="font-size:9px;">VIDEO</span>
                                            @endif
                                        </td>
                                        <td>{{ $list->tags }}</td>
                                        <td>{{ $list->description }}</td>
                                        <td>
                                            {{-- ✅  Media preview button (opens modal player) --}}
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="openMediaPreview(
                                                '{{ in_array(strtolower($list->media_type), ['mp4', 'mov', 'avi', 'webm'])
                                                    ? route('media.stream', $list->file_name)
                                                    : route('media.serve', $list->file_name) }}',
                                                '{{ in_array(strtolower($list->media_type), ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image' }}'
                                            )">
                                                <i class="fa fa-eye"></i> Preview
                                            </button>
                                            <a href="{{ route('media.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm mb-1">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            @if ($list->locked)
                                                <button type="button" class="btn btn-outline-secondary btn-sm mb-1"
                                                    disabled>
                                                    <i class="fa fa-lock"></i> Vendor Media
                                                </button>
                                            @else
                                                <a href="{{ route('media.delete', $list->id) }}"
                                                    class="btn btn-outline-danger btn-sm mb-1">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="active-row">
                                        <td colspan="8">
                                            @include('admin.partials.not_found', [
                                                'type' => 'media',
                                                'btnText' => 'Add Media',
                                                'btnUrl' => route('media.managemedia'),
                                            ])
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- Deleted Rows --}}
                                @foreach ($deletedMedia as $list)
                                    <tr class="trash-row d-none" style="background:#fff3f3;">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>
                                            <p class="small">By:- {{ $list->is_vendor }}</p>
                                            <p class="small">Created By:- {{ $list->who_create }}</p>
                                            <p class="small">Edited By:- {{ $list->who_edited }}</p>
                                        </td>
                                        <td>
                                            {{-- ✅ Stream route for deleted rows too --}}
                                            @include('admin.partials.media_preview', [
                                                'media' => $list,
                                                'size' => 'sm',
                                            ])
                                        </td>
                                        <td>
                                            {{ strtoupper($list->media_type) }}
                                            @if (in_array(strtolower($list->media_type), ['mp4', 'mov', 'avi', 'webm']))
                                                <span class="badge bg-secondary ms-1" style="font-size:9px;">VIDEO</span>
                                            @endif
                                        </td>
                                        <td>{{ $list->tags }}</td>
                                        <td class="text-muted text-decoration-line-through">
                                            {{ $list->description }}
                                        </td>
                                        <td>
                                            {{-- ✅  Media preview button (opens modal player) --}}
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-url="{{ in_array(strtolower($list->media_type), ['mp4', 'mov', 'avi', 'webm'])
                                                    ? route('media.stream', $list->file_name)
                                                    : route('media.serve', $list->file_name) }}"
                                                data-type="{{ in_array(strtolower($list->media_type), ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image' }}"
                                                onclick="openMediaPreview(this.dataset.url, this.dataset.type)">
                                                <i class="fa fa-eye"></i> Preview
                                            </button>

                                            <a href="{{ route('media.restore', $list->id) }}"
                                                onclick="return confirm('Restore {{ $list->file_name }}?')">
                                                <button type="button" class="btn btn-success btn-sm mb-1">
                                                    <i class="fa fa-undo me-1"></i> Restore
                                                </button>
                                            </a>
                                            <a href="{{ route('media.permanent_delete', $list->id) }}"
                                                onclick="return confirm('Permanently delete? Cannot be undone.')">
                                                <button type="button" class="btn btn-danger btn-sm mb-1">
                                                    <i class="fa fa-times me-1"></i> Remove
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ✅ Video Preview Modal --}}
    <div class="modal fade" id="videoPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0 pb-0">
                    <span id="videoPreviewTitle" class="text-white small"></span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        onclick="stopVideo()">
                    </button>
                </div>
                <div class="modal-body p-2">
                    <video id="videoPreviewPlayer" controls autoplay preload="metadata"
                        style="width:100%; max-height:480px; border-radius:6px; background:#000;">
                        Your browser does not support video playback.
                    </video>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ✅ Open video preview modal
        function previewVideo(streamUrl, filename) {
            const player = document.getElementById('videoPreviewPlayer');
            const title = document.getElementById('videoPreviewTitle');

            player.src = streamUrl;
            title.textContent = filename;

            new bootstrap.Modal(document.getElementById('videoPreviewModal')).show();
        }

        // ✅ Stop video on modal close (prevents audio playing in background)
        function stopVideo() {
            const player = document.getElementById('videoPreviewPlayer');
            player.pause();
            player.src = '';
        }

        // Also stop if modal is closed via backdrop click or ESC
        document.getElementById('videoPreviewModal').addEventListener('hidden.bs.modal', stopVideo);
    </script>
@endpush
