@extends('admin.layout.layout')
@section('page_title', 'Brands Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage brands</h3>
            <a href="{{ route('brands') }}" class="btn btn-success">
                Back to brands
            </a>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('brands.manage_brands_process') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="brands" class="control-label mb-1">Brands Name</label>
                                        <input id="brands" name="name" value="{{ $name }}" type="text"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        {{-- Media Modal Start --}}
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="addMedia('product')">
                                            Select Image
                                        </button>
                                        <input type="hidden" name="media_ids" id="media_id_product"
                                            value="{{ $media_ids ?? '' }}">
                                        <div id="preview_product">
                                            @if (!empty($image))
                                                <img src="{{ asset('storage/media/' . $image) }}" width="120"
                                                    class="img-thumbnail mt-2">
                                            @endif
                                        </div>
                                        {{-- Media Modal End --}}
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
        </div>
    </div>
@endsection
