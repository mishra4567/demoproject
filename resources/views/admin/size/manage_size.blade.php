@extends('admin.layout.layout')
@section('page_title', 'Size Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Size</h3>
            <a href="{{ route('size') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Size</button>
            </a>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('size.manage_size_process') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="size" class="control-label mb-1">Size
                                            @include('admin.partials.field_info', [
                                                'info' => $info['size'] ?? '',
                                            ])
                                        </label>
                                        <input id="size" name="size" value="{{ $size }}" type="text"
                                            class="form-control" required>
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
