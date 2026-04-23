@extends('admin.layout.layout')
@section('page_title', 'Color Manager')
@section('container')
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Color</h3>
            <a href="{{ route('color') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Color</button>
            </a>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('color.manage_color_process') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <div class="mb-3">
                                        {{-- <label for="color" class="control-label mb-1">Color</label> --}}
                                        <label for="color" class="control-label mb-1">
                                            Color
                                            @include('admin.partials.field_info', [
                                                'info' => $info['color'] ?? '',
                                            ])
                                        </label>
                                        <input id="color" name="color" value="{{ $color }}" type="text"
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
