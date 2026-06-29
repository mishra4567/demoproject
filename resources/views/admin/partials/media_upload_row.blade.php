{{-- ./views/admin/partials/media_upload_row.blade.php --}}
@php
    $rowIndex = $rowIndex ?? 1;
@endphp

<div class="media-row border rounded-3 p-3 position-relative bg-light">

    <span class="badge bg-dark position-absolute top-0 start-0 m-2" style="font-size:10px;">
        File {{ $rowIndex }}
    </span>

    <div class="row align-items-center g-3 mt-3">

        {{-- ── Drop zone / preview ── --}}
        <div class="col-md-3">
            <label for="media-input-{{ $rowIndex }}"
                class="file-drop-zone d-flex flex-column align-items-center justify-content-center
                       border border-2 border-dashed rounded-3 text-center p-2"
                style="height:110px; cursor:pointer; transition:.2s;">
                <i class="fa fa-cloud-upload fa-lg text-muted mb-1"></i>
                <span class="file-drop-label small text-muted px-1" style="word-break:break-all;">
                    Click to choose file & Drag and drop file 
                </span>
            </label>

            <input type="file" name="media[]" id="media-input-{{ $rowIndex }}" class="media-input d-none"
                accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm" required>

            <div class="file-info small text-muted mt-1 text-center"></div>

            {{-- Preview container --}}
            <div class="preview-container d-none mt-2 text-center">
                <img class="preview-img d-none rounded" style="max-height:80px; max-width:100%; object-fit:cover;">
                <video class="preview-video d-none rounded" style="max-height:80px; max-width:100%; object-fit:cover;"
                    muted controls preload="metadata">
                </video>
            </div>
        </div>

        {{-- ── Tag ── --}}
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">
                Tag
                @include('admin.partials.field_info', ['info' => $info['tags'] ?? ''])
            </label>
            <input type="text" name="tags[]" maxlength="10" class="form-control" placeholder="Max 10 chars">
        </div>

        {{-- ── Description ── --}}
        <div class="col-md-5">
            <label class="form-label small fw-semibold mb-1">
                Description
                @include('admin.partials.field_info', ['info' => $info['description'] ?? ''])
            </label>
            <textarea name="description[]" class="form-control" rows="2" maxlength="500" placeholder="Optional description"></textarea>
        </div>

        {{-- ── Remove (hidden on first row, shown via JS when rows > 1) ── --}}
        <div class="col-md-1 text-center remove-col {{ $rowIndex == 1 ? 'd-none' : '' }}">
            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeMediaForm(this)">
                <i class="fa fa-times"></i>
            </button>
        </div>

    </div>
</div>
