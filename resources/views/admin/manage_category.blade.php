@extends('admin.layout.layout')
@section('page_title', 'Manage Category')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Category</h3>
            <a href="{{ route('category') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Category</button>
            </a>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('category.manage_category_process') }}" method="post">
                        @csrf
                        <div class="row g-3">

                            <!-- Category Name -->
                            <div class="col-md-6">
                                <label class="form-label">Category Name
                                    @include('admin.partials.field_info', [
                                        'info' => $info['category_name'] ?? '',
                                    ])
                                </label>
                                <input type="text" name="category_name" value="{{ $category_name ?? '' }}"
                                    class="form-control" required>
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <label class="form-label">Category Slug
                                    @include('admin.partials.field_info',[
                                        'info'=>$info['slug']
                                    ])
                                </label>
                                <input type="text" name="category_slug" value="{{ $category_slug ?? '' }}"
                                    class="form-control" required>
                            </div>

                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <label class="form-label">Parent Category
                                    @include('admin.partials.field_info',[
                                        'info'=>$info['parent_category']
                                    ])
                                </label>
                                <select name="parent_id" class="form-control">
                                    <option value="0">Main Category</option>

                                    @foreach ($parent_categories as $list)
                                        <option value="{{ $list->id }}"
                                            {{ ($parent_id ?? 0) == $list->id ? 'selected' : '' }}>
                                            {{ $list->category_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                        </div>

                        <div class="d-grid mt-4">
                            <input type="hidden" name="id" value="{{ $id ?? '' }}">
                            <button id="payment-button" type="submit" class="btn btn-lg btn-info">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
