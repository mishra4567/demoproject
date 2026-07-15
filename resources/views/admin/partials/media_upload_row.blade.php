@php $rowIndex = $rowIndex ?? 1; @endphp

<div class="media-row border rounded-3 p-3 position-relative bg-light">

    <span class="badge bg-dark position-absolute top-0 start-0 m-2" style="font-size:10px;">
        File {{ $rowIndex }}
    </span>

    <div class="row align-items-start g-3 mt-3">

        {{-- ── Drop zone / URL input ── --}}
        <div class="col-md-3">

            {{-- ✅ Tabs: File | URL --}}
            <ul class="nav nav-tabs nav-tabs-sm mb-2" style="font-size:12px;">
                <li class="nav-item">
                    <a class="nav-link active py-1 px-2 tab-file-btn" href="#"
                        onclick="switchTab(this, 'file', {{ $rowIndex }}); return false;">
                        <i class="fa fa-upload me-1"></i> File
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1 px-2 tab-url-btn" href="#"
                        onclick="switchTab(this, 'url', {{ $rowIndex }}); return false;">
                        <i class="fa fa-link me-1"></i> URL
                    </a>
                </li>
            </ul>

            {{-- ✅ File tab --}}
            <div class="tab-file-content-{{ $rowIndex }}">
                <label for="media-input-{{ $rowIndex }}"
                    class="file-drop-zone d-flex flex-column align-items-center
                           justify-content-center border border-2 border-dashed
                           rounded-3 text-center p-2"
                    style="height:90px; cursor:pointer; transition:.2s;">
                    <i class="fa fa-cloud-upload fa-lg text-muted mb-1"></i>
                    <span class="file-drop-label small text-muted px-1" style="word-break:break-all;">
                        Click or drag & drop
                    </span>
                </label>
                <input type="file" name="media[]" id="media-input-{{ $rowIndex }}" class="media-input d-none"
                    accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm">
            </div>

            {{-- ✅ URL tab --}}
            <div class="tab-url-content-{{ $rowIndex }} d-none">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-link text-muted"></i>
                    </span>
                    <input type="url" name="media_url[]" id="url-input-{{ $rowIndex }}"
                        class="form-control url-input" placeholder="https://example.com/image.jpg"
                        oninput="previewUrl(this, {{ $rowIndex }})">
                </div>
                <div class="form-text">JPG, PNG, WEBP, MP4 supported</div>
            </div>

            {{-- File info --}}
            <div class="file-info small text-muted mt-1 text-center"></div>

            {{-- Preview --}}
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

        {{-- ── Remove ── --}}
        <div class="col-md-1 text-center remove-col {{ $rowIndex == 1 ? 'd-none' : '' }}">
            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeMediaForm(this)">
                <i class="fa fa-times"></i>
            </button>
        </div>

    </div>
</div>
