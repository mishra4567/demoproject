@extends('admin.layout.layout')
@section('page_title', 'Category')
@section('category_select', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Category</h3>
            <a href="{{ route('category.add_category') }}">
                <button type="button" class="btn btn-success ">Add Category</button>
            </a>
            <div class="row">
                <form action="{{ route('media.bulkAction') }}" method="POST">
                    @csrf
                    <!-- Bulk Action Dropdown -->
                    <div class="mb-3 d-flex">
                        <select name="action" class="form-control w-auto mr-2" required>
                            <option value="">Bulk Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>

                        <button type="submit" class="btn btn-primary">
                            Apply
                        </button>
                    </div>
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    {{-- <th>date</th> --}}
                                    <th>
                                        <input type="checkbox" id="select_all">
                                    </th>
                                    <th>order ID</th>
                                    <th>Category name</th>
                                    <th>Category slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $list)
                                    <tr>
                                        {{-- <td>2025-01-15 14:32</td> --}}
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->category_name }}</td>
                                        <td>{{ $list->category_slug }}</td>
                                        <td>
                                            {{-- @if ($list->status == 1)
                                            <a href="{{ route('category.status', $list->id) }}">
                                                <button type="button" class="btn btn-outline-info btn-sm">Active </button>
                                            </a>
                                        @else
                                            <a href="{{ route('category.status', $list->id) }}">
                                                <button type="button" class="btn btn-outline-warning btn-sm">Deactive
                                                </button>
                                            </a>
                                        @endif --}}
                                            <a href="{{ route('category.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            <a href="{{ route('category.edit_category', $list->id) }}">
                                                <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
                                            </a>
                                            <a href="{{ route('category.delete', $list->id) }}">
                                                <button type="button" class="btn btn-outline-danger btn-sm">Delete
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
            {{-- <div class="row">
                <div class="col-md-12">
                    <!-- DATA TABLE -->
                    <h3 class="title-5 m-b-35">Category</h3>
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <div class="select-wrapper">
                                <select class="form-select form-select-lg" name="property">
                                    <option selected="selected">All Properties</option>
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                </select>

                            </div>
                            <div class="select-wrapper">
                                <select class="form-select form-select-lg" name="time">
                                    <option selected="selected">Today</option>
                                    <option value="">3 Days</option>
                                    <option value="">1 Week</option>
                                </select>

                            </div>
                            <button class="au-btn-filter">
                                <i class="zmdi zmdi-filter-list"></i>filters</button>
                        </div>
                        <div class="table-data__tool-right">
                            <button class="au-btn au-btn-icon au-btn--green au-btn--small">
                                <i class="zmdi zmdi-plus"></i>add item</button>
                            <div class="select-wrapper">
                                <select class="form-select form-select-lg" name="type">
                                    <option selected="selected">Export</option>
                                    <option value="option1">Option 1</option>
                                    <option value="option2">Option 2</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-responsive-data2">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>name</th>
                                    <th>email</th>
                                    <th>description</th>
                                    <th>date</th>
                                    <th>status</th>
                                    <th>price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-shadow">
                                    <td>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>Lori Lynch</td>
                                    <td>
                                        <span class="block-email">lori@example.com</span>
                                    </td>
                                    <td class="desc">Samsung Galaxy S25 Ultra</td>
                                    <td>2025-01-15 14:32</td>
                                    <td>
                                        <span class="status--process">Processed</span>
                                    </td>
                                    <td>$679.00</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Send">
                                                <i class="zmdi zmdi-mail-send"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="More">
                                                <i class="zmdi zmdi-more"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                                <tr class="tr-shadow">
                                    <td>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>Lori Lynch</td>
                                    <td>
                                        <span class="block-email">john@example.com</span>
                                    </td>
                                    <td class="desc">iPhone 17 128GB Titanium</td>
                                    <td>2025-01-15 14:32</td>
                                    <td>
                                        <span class="status--process">Processed</span>
                                    </td>
                                    <td>$999.00</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Send">
                                                <i class="zmdi zmdi-mail-send"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="More">
                                                <i class="zmdi zmdi-more"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                                <tr class="tr-shadow">
                                    <td>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>Lori Lynch</td>
                                    <td>
                                        <span class="block-email">lyn@example.com</span>
                                    </td>
                                    <td class="desc">iPhone 17 Pro Max 1TB Space Black</td>
                                    <td>2025-01-15 14:32</td>
                                    <td>
                                        <span class="status--denied">Denied</span>
                                    </td>
                                    <td>$1199.00</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Send">
                                                <i class="zmdi zmdi-mail-send"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="More">
                                                <i class="zmdi zmdi-more"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                                <tr class="tr-shadow">
                                    <td>
                                        <label class="au-checkbox">
                                            <input type="checkbox">
                                            <span class="au-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>Lori Lynch</td>
                                    <td>
                                        <span class="block-email">doe@example.com</span>
                                    </td>
                                    <td class="desc">Camera C430W 4k</td>
                                    <td>2025-01-15 14:32</td>
                                    <td>
                                        <span class="status--process">Processed</span>
                                    </td>
                                    <td>$699.00</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Send">
                                                <i class="zmdi zmdi-mail-send"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Edit">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Delete">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                            <button class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="More">
                                                <i class="zmdi zmdi-more"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- END DATA TABLE -->
                </div>
            </div> --}}
        </div>
    </div>
@endsection
