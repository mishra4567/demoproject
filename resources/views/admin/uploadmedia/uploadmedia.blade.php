@extends('admin.layout.layout')
@section('page_title', 'Manage Media')
@section('container')
    {{-- @php
        $required = $id > 0 ? '' : 'required';
        $AllRequired = 'required';
    @endphp --}}
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h3 class="title-5 m-b-35">Manage Media</h3>
            <a href="{{ route('media') }}">
                <button type="button" class="btn btn-success " disabled="">Back to Media</button>
            </a>
            <div class="row">
                <div class="col-md-12">

                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card shadow-sm">

                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Add Media</h5>
                            </div>

                            <div class="card-body">

                                <div id="media-wrapper">

                                    <!-- Media Row -->
                                    <div class="row align-items-center mb-3 media-row">

                                        <!-- Preview -->
                                        {{-- <div class="col-md-2">
                                            <div class="border rounded bg-light d-flex
                                            align-items-center justify-content-center"
                                                style="height:80px;">
                                                <img class="preview-img img-fluid d-none" style="max-height:80px;">
                                            </div>
                                        </div> --}}
                                        <div class="col-md-2 preview-container d-none">
                                            <div class="border rounded bg-light d-flex align-items-center justify-content-center"
                                                style="height:80px;">
                                                <img class="preview-img img-fluid" style="max-height:80px;">
                                            </div>
                                        </div>

                                        <!-- File Input -->
                                        <div class="col-md-3">
                                            <input type="file" name="media[]" class="form-control media-input" required>
                                        </div>

                                        <!-- Tag -->
                                        <div class="col-md-3">
                                            <input type="text" name="tags[]" maxlength="10" class="form-control"
                                                placeholder="Add tag (max 10 char)">
                                        </div>

                                        <!-- Remove -->
                                        <div class="col-md-2 text-center">
                                            <button type="button" class="btn btn-outline-danger remove-btn" onclick=" removeMediaForm(this) ">
                                                Remove
                                            </button>
                                        </div>

                                    </div>

                                </div>

                                <!-- Add Media Button -->
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-secondary" id="add-media" onclick=" addMediaForm() ">
                                        + Add More Media
                                    </button>
                                </div>

                            </div>

                            <!-- Upload Button -->
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    Upload Media
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
