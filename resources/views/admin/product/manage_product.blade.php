@extends('admin.layout.layout')
@section('page_title', 'Manage Product')
@section('manage_product_select', 'active')
@section('container')
    @php
        $required = $id > 0 ? '' : 'required';
        $AllRequired = 'required';
    @endphp
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Product</h3>
            <a href="{{ route('product') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Product</button>
            </a>
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('product.manage_product_process') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="name" class="control-label mb-1">Product
                                            Name</label>
                                        <input id="name" name="name" value="{{ $name }}" type="text"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="slug" class="control-label mb-1">Product Slug
                                        </label>
                                        <input id="slug" name="slug" value="{{ $slug }}" type="text"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="category_id" class="control-label mb-1">Select Category
                                                </label>
                                                <select name="category_id" id="category_id" class="form-control">
                                                    <option value="">select categories</option>
                                                    @foreach ($category as $cat)
                                                        @if ($category_id == $cat->id)
                                                            <option selected value="{{ $cat->id }}">
                                                                {{ $cat->category_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $cat->id }}"> {{ $cat->category_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">

                                                    <label for="brand_id" class="control-label mb-1">Product brand
                                                    </label>
                                                    <select name="brand_id" id="brand_id" class="form-control">
                                                        <option value="">select Brand</option>
                                                        @foreach ($brands as $band)
                                                            @if ($brand == $band->id)
                                                                <option selected value="{{ $band->id }}">
                                                                    {{ $band->name }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $band->id }}"> {{ $band->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    {{-- <input id="brand" name="brand" value="{{ $brand }}"
                                                        type="text" class="form-control" required> --}}

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="model" class="control-label mb-1">Product model
                                                    </label>
                                                    <input id="model" name="model" value="{{ $model }}"
                                                        type="text" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        {{-- Media Modal Start --}}
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="addMedia('product')">
                                            Select Image
                                        </button>
                                        <input type="hidden" name="media_id" id="media_id_product"
                                            value="{{ $media_id ?? '' }}">
                                        <div id="preview_product">
                                            @if (!empty($image))
                                                <img src="{{ asset('storage/media/' . $image) }}" width="120">
                                            @endif
                                        </div>
                                        {{-- Media Modal End --}}
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_desc" class="control-label mb-1">Product short desc
                                        </label>
                                        <textarea name="short_desc" id="short_desc" cols="3" rows="3" class="form-control" {{ $required }}>{{ $short_desc }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="desc" class="control-label mb-1">Product desc
                                        </label>
                                        <textarea name="desc" id="desc" cols="3" rows="3" class="form-control" {{ $required }}>{{ $desc }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keywords" class="control-label mb-1">Product keywords
                                        </label>
                                        <textarea name="keywords" id="keywords" cols="3" rows="3" class="form-control" {{ $required }}>{{ $keywords }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="technical_specification" class="control-label mb-1">Product technical
                                            specification
                                        </label>
                                        <textarea name="technical_specification" id="technical_specification" cols="3" rows="3"
                                            class="form-control" {{ $required }}>{{ $technical_specification }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="uses" class="control-label mb-1">Product uses
                                        </label>
                                        <textarea name="uses" id="uses" cols="3" rows="3" class="form-control" {{ $required }}>{{ $uses }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="warranty" class="control-label mb-1">Product warranty
                                        </label>
                                        <textarea name="warranty" id="warranty" cols="3" rows="3" class="form-control" {{ $required }}>{{ $warranty }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Product Gallery Images</h5>

                                    <!-- Add Button -->
                                    <button type="button" class="btn btn-outline-primary mb-3"
                                        onclick="addMedia('gallery')">
                                        + Add Gallery Image
                                    </button>

                                    <!-- Gallery Preview Container -->
                                    <div id="gallery_preview" class="d-flex flex-wrap gap-2">
                                        @if (!empty($gallery_images))
                                            @foreach ($gallery_images as $gImg)
                                                <div class="gallery-item position-relative m-2">
                                                    <input type="hidden" name="gallery_media_id[]"
                                                        value="{{ $gImg->id }}">
                                                    <img src="{{ asset('storage/media/' . $gImg->file_name) }}"
                                                        width="120" class="rounded">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                        onclick="removeGalleryImage(this)">✕</button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
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
