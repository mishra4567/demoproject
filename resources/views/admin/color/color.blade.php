@extends('admin.layout.layout')
@section('page_title', 'Color')
@section('color_select','active')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Color</h3>
            <a href="{{ route('color.add_color') }}">
                <button type="button" class="btn btn-success ">Add Color</button>
            </a>
            <div class="row">
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <tr>
                                <th>date</th>
                                <th>Color ID</th>
                                <th>Color</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $list)
                                <tr>
                                    <td>2025-01-15 14:32</td>
                                    <td>{{ $list->id }}</td>
                                    <td>{{ $list->color }}</td>
                                    <td>
                                        <a href="{{ route('color.status', $list->id) }}"
                                            class="btn btn-outline-{{ $list->status == 1 ? 'info' : 'warning' }} btn-sm ">
                                            {{ $list->status == 1 ? 'Active' : 'Deactive' }}
                                        </a>
                                        <a href="{{ route('color.edit_color', $list->id) }}">
                                            <button type="button" class="btn btn-outline-success btn-sm">Edit </button>
                                        </a>
                                        <a href="{{ route('color.delete', $list->id) }}">
                                            <button type="button" class="btn btn-outline-danger btn-sm">Delete </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
