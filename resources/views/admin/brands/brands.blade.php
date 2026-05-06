@extends('admin.layout.layout')
@section('page_title', 'Brands')
@section('brands_select', 'active')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="title-5 m-b-0">Brands</h3>
                <a href="{{ route('brands.add_brands') }}">
                    <button type="button" class="btn btn-success">
                        <i class="fa fa-plus me-1"></i> Add Brand
                    </button>
                </a>
            </div>
            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-secondary" id="trash_toggle_btn" onclick="toggleTrash()">
                    <i class="fa fa-trash me-1"></i> Show Deleted
                    @if ($deleteData->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $deleteData->count() }}</span>
                    @endif
                </button>
            </div>

            <div class="row">
                <form action="{{ route('brands.bulkAction') }}" method="POST">
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
                                    <th>Brand ID</th>
                                    <th>Brand Name</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- ✅ Active Rows --}}
                                @forelse ($data as $list)
                                    <tr class="active-row">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td>
                                            @if ($list->file_name)
                                                <img src="{{ asset('storage/media/' . $list->file_name) }}" width="45"
                                                    height="45" class="rounded" style="object-fit:cover;">
                                            @else
                                                <span class="text-muted small">No image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('brands.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            <a href="{{ route('brands.add_brands', $list->id) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">
                                                    Edit
                                                </button>
                                            </a>
                                            <a href="{{ route('brands.delete', $list->id) }}"
                                                onclick="return confirm('Move {{ $list->name }} to trash?')">
                                                <button type="button" class="btn btn-outline-danger btn-sm">
                                                    Delete
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="active-row">
                                        <td colspan="5">
                                            @include('admin.partials.not_found', [
                                                'type' => 'empty',
                                                'btnText' => 'Add Brand',
                                                'btnUrl' => route('brands.add_brands'),
                                            ])
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- ✅ Deleted Rows --}}
                                @foreach ($deleteData as $list)
                                    <tr class="trash-row d-none" style="background:#fff3f3;">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td class="text-muted text-decoration-line-through">
                                            {{ $list->name }}
                                        </td>
                                        <td>
                                            @if ($list->file_name)
                                                <img src="{{ asset('storage/media/' . $list->file_name) }}" width="45"
                                                    height="45" class="rounded"
                                                    style="object-fit:cover; filter:grayscale(100%);">
                                            @else
                                                <span class="text-muted small">No image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('brands.restore', $list->id) }}"
                                                onclick="return confirm('Restore {{ $list->name }}?')">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fa fa-undo me-1"></i> Restore
                                                </button>
                                            </a>
                                            <a href="{{ route('brands.permanent_delete', $list->id) }}"
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
