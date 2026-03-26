@extends('admin.layout.layout')
@section('page_title', 'Media')
@section('media_select', 'active')
@section('container')
    <div class="section__content section__content--p30">
        <br class="container-fluid">
            <h3 class="title-5 m-b-35">Media</h3>
            <a href="{{ route('media.managemedia') }}">
                <button type="button" class="btn btn-success ">Add Media</button>
            </a>
            <br><br>
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
                                    <th>
                                        <input type="checkbox" id="select_all">
                                    </th>
                                    <th>ID</th>
                                    {{-- <th>Date</th> --}}
                                    <th>Media</th>
                                    <th>Media Type</th>
                                    <th>Tag</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($media as $list)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $list->id }}"
                                                class="checkbox_ids">
                                        </td>
                                        <td>{{ $list->id }}</td>
                                        {{-- <td>2025-01-15 14:32</td> --}}
                                        <td><img src="{{ asset('storage/media/' . $list->file_name) }}" alt=""></td>
                                        <td>{{ $list->media_type }}</td>
                                        <td>{{ $list->description }}</td>
                                        <td>{{ $list->tags }}</td>
                                        <td>
                                            <a href="{{ route('media.status', $list->id) }}"
                                                class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
                                                {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                            </a>
                                            {{-- <a href="{{ route('product.edit_product', $list->id) }}">
                                            <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
                                        </a> --}}
                                            <a href="{{ route('media.delete', $list->id) }}">
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
        </div>
    </div>
@endsection
