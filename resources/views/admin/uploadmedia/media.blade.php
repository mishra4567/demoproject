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
                            {{-- Active Section --}}
                            <option value="activate" class="active-option">Activate</option>
                            <option value="deactivate" class="active-option">Deactivate</option>
                            <option value="trash" class="active-option">Move to Trash</option>
                            {{-- Deleted Section --}}
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
                                    <th>Media</th>
                                    <th>Media Type</th>
                                    <th>Tag</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- ✅ Active Rows --}}
                                @forelse ($media as $list)
                                    <tr class="active-row">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>
                                            <img src="{{ asset('storage/media/' . $list->file_name) }}" width="50"
                                                height="50" class="rounded" style="object-fit:cover;">
                                        </td>
                                        <td>{{ $list->media_type }}</td>
                                        <td>{{ $list->tags }}</td>
                                        <td>{{ $list->description }}</td>
                                        <td>
                                            <a href="{{ route('media.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            {{-- <a href="{{ route('media.managemedia', $list->id) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">
                                                    Edit
                                                </button>
                                            </a> --}}
                                            <a href="{{ route('media.delete', $list->id) }}"
                                                onclick="return confirm('Move to trash?')">
                                                <button type="button" class="btn btn-outline-danger btn-sm">
                                                    Delete
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="active-row">
                                        <td colspan="7">
                                            @include('admin.partials.not_found', [
                                                'type' => 'media',
                                                'btnText' => 'Add Media',
                                                'btnUrl' => route('media.managemedia'),
                                            ])
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- ✅ Deleted Rows --}}
                                @foreach ($deletedMedia as $list)
                                    <tr class="trash-row d-none" style="background:#fff3f3;">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>
                                            <img src="{{ asset('storage/media/' . $list->file_name) }}" width="50"
                                                height="50" class="rounded"
                                                style="object-fit:cover; filter:grayscale(100%);">
                                        </td>
                                        <td>{{ $list->media_type }}</td>
                                        <td>{{ $list->tags }}</td>
                                        <td class="text-muted text-decoration-line-through">
                                            {{ $list->description }}
                                        </td>
                                        <td>
                                            <a href="{{ route('media.restore', $list->id) }}"
                                                onclick="return confirm('Restore {{ $list->file_name }}?')">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fa fa-undo me-1"></i> Restore
                                                </button>
                                            </a>
                                            <a href="{{ route('media.permanent_delete', $list->id) }}"
                                                onclick="return confirm('Permanently delete? Cannot be undone.')">
                                                <button type="button" class="btn btn-danger btn-sm">
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

@endsection
