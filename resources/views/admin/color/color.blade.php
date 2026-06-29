@extends('admin.layout.layout')
@section('page_title', 'Color')
@section('color_select', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            {{-- Trash Toggle --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="title-5 m-b-0">Color</h3>
                <a href="{{ route('color.add_color') }}">
                    <button type="button" class="btn btn-success">
                        <i class="fa fa-plus me-1"></i> Add Color
                    </button>
                </a>
            </div>
            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-secondary" id="trash_toggle_btn" onclick="toggleTrash()">
                    <i class="fa fa-trash me-1"></i> Show Deleted
                    @if ($deletedData->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $deletedData->count() }}</span>
                    @endif
                </button>
            </div>
            {{-- Trash Toggle --}}
            <div class="row">
                <form action="{{ route('color.bulkAction') }}" method="POST">
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
                                    <th>Color ID</th>
                                    <th>Added By</th>
                                    <th>Color</th>
                                    <th>Preview</th>
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
                                        <td>
                                            <p class="small">By:- {{ $list->is_vendor }}</p>
                                            <p class="small">created By:- {{ $list->who_create }}</p>
                                            <p class="small">Edited By:- {{ $list->who_edited }}</p>
                                        </td>
                                        <td>{{ $list->color_name }}</td>
                                        <td>
                                            <div
                                                style="
                                            width:28px; height:28px;
                                            background:{{ $list->hex_id }};
                                            border-radius:50%;
                                            border:1px solid #ddd;">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('color.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            @if ($list->locked)
                                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                                    <i class="fa fa-lock"></i> Vendor Color
                                                </button>
                                            @else
                                                <a href="{{ route('color.edit_color', $list->id) }}"
                                                    class="btn btn-outline-success btn-sm">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>

                                                <a href="{{ route('color.delete', $list->id) }}"
                                                    class="btn btn-outline-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="active-row">
                                        <td colspan="6">
                                            @include('admin.partials.not_found', [
                                                'type' => 'color',
                                                'btnText' => 'Add Color',
                                                'btnUrl' => route('color.add_color'),
                                            ])
                                        </td>
                                    </tr>
                                @endforelse
                                {{-- ✅ Deleted Rows — hidden by    default --}}
                                @foreach ($deletedData as $list)
                                    <tr class="trash-row d-none" style="background:#fff3f3;">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>
                                            <p class="small">By:- {{ $list->is_vendor }}</p>
                                            <p class="small">created By:- {{ $list->who_create }}</p>
                                            <p class="small">Deleted By:- {{ $list->who_delete }}</p>
                                        </td>
                                        <td>
                                            <span class="text-muted text-decoration-line-through">
                                                {{ $list->color_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <div
                                                style="
                                            width:28px; height:28px;
                                            background:{{ $list->hex_id }};
                                            border-radius:50%;
                                            border:1px solid #ddd;
                                            filter:grayscale(100%);">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('color.restore', $list->id) }}"
                                                class="btn btn-success btn-sm"
                                                onclick="return confirm('Restore {{ $list->color_name }}?')">
                                                <i class="fa fa-undo me-1"></i> Restore
                                            </a>
                                            <a href="{{ route('color.permanent_delete', $list->id) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Permanently delete? Cannot be undone.')">
                                                <i class="fa fa-times me-1"></i> Remove
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
