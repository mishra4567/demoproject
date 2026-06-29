{{-- ./views/upladmedia/uploadmedia.blade.php --}}
@extends('admin.layout.layout')
@section('page_title', 'Manage Media')
@section('container')

    <div class="section__content section__content--p30">
        <div class="container-fluid">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color:#1a1a2e;">Upload Media</h3>
                    <p class="text-muted small mb-0">Add images or videos to your media library</p>
                </div>
                <a href="{{ route('media') }}">
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Back to Library
                    </button>
                </a>
            </div>

            <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card border-0 shadow-sm rounded-3">

                    {{-- Header --}}
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top-3">
                        <h6 class="mb-0 fw-semibold text-white">
                            <i class="fa fa-cloud-upload me-2 "></i> Add Media Files
                        </h6>
                        <span class="badge bg-secondary rounded-pill px-3" id="file-counter">
                            0 / 1 selected
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="card-body p-4">
                        <div id="media-wrapper" class="d-flex flex-column gap-3">
                            @include('admin.partials.media_upload_row', [
                                'info'     => $info,
                                'rowIndex' => 1,
                            ])
                        </div>

                        <button type="button"
                            class="btn btn-outline-primary mt-3"
                            onclick="addMediaForm()">
                            <i class="fa fa-plus me-1"></i> Add Another File
                        </button>
                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-light border-top px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-white border text-muted fw-normal px-3 py-2">
                                <i class="fa fa-image me-1 text-primary"></i> JPG, PNG, WEBP
                            </span>
                            <span class="badge bg-white border text-muted fw-normal px-3 py-2">
                                <i class="fa fa-film me-1 text-danger"></i> MP4, MOV, AVI
                            </span>
                            <span class="badge bg-white border text-muted fw-normal px-3 py-2">
                                <i class="fa fa-hdd-o me-1 text-warning"></i> Max 20MB each
                            </span>
                            <span class="badge bg-white border text-muted fw-normal px-3 py-2">
                                <i class="fa fa-files-o me-1 text-success"></i> Up to 10 files
                            </span>
                        </div>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-upload me-2"></i> Upload Media
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

@endsection
