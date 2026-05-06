@extends('admin.layout.layout')
@section('page_title', 'Coupan')
@section('coupon_select', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            {{-- Trash Toggle --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="title-5 m-b-0">Coupon</h3>
                <a href="{{ route('coupons.add_coupons') }}">
                    <button type="button" class="btn btn-success">
                        <i class="fa fa-plus me-1"></i> Add Coupon
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
                <form action="{{ route('coupons.bulkAction') }}" method="POST">
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
                                    {{-- <th>date</th> --}}
                                    <th>
                                        <input type="checkbox" id="select_all">
                                    </th>
                                    <th>Coupon ID</th>
                                    <th>Coupon Title</th>
                                    <th>Coupon code</th>
                                    <th>Coupon value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $list)
                                    <tr class="active-row">
                                        {{-- <td>2025-01-15 14:32</td> --}}
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->title }}</td>
                                        <td>{{ $list->code }}</td>
                                        <td>{{ $list->value }}</td>
                                        <td>
                                            <a href="{{ route('coupons.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            <a href="{{ route('coupons.edit_coupons', $list->id) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
                                            </a>
                                            <a href="{{ route('coupons.delete', $list->id) }}">
                                                <button type="button" class="btn btn-outline-danger btn-sm">Delete
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="active-row">
                                        <td colspan="5">
                                            @include('admin.partials.not_found', [
                                                'type' => 'coupon',
                                                'btnText' => 'Add Coupon',
                                                'btnUrl' => route('coupons.add_coupons'),
                                            ])
                                        </td>
                                    </tr>
                                @endforelse
                                {{-- ✅ Deleted Rows — hidden by default --}}
                                @foreach ($deletedData as $list)
                                    <tr class="trash-row d-none" style="background:#fff3f3">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->title }}</td>
                                        <td>{{ $list->code }}</td>
                                        <td>{{ $list->value }}</td>
                                        <td>
                                            <a href="{{ route('coupons.restore', $list->id) }}"
                                                onclick="return confirm('Restore {{ $list->title }}?')">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fa fa-undo me-1"></i> Restore
                                                </button>
                                            </a>
                                            <a href="{{ route('coupons.permanent_delete', $list->id) }}"
                                                onclick="return confirm('Permanently delete this coupon? This action cannot be undone.')">
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
