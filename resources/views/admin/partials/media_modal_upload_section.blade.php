{{-- ./views/admin/partials/media_modal_upload_section.blade.php --}}
<div id="modalUploadWrap" class="d-none px-4 pt-3 pb-2 border-bottom bg-light">
    <form id="modalUploadForm" action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div id="modalDropZone" class="border border-2 border-dashed rounded-3 text-center p-4"
            style="cursor:pointer; transition:.2s;"
            ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary','bg-opacity-10');"
            ondragleave="this.classList.remove('border-primary','bg-primary','bg-opacity-10');"
            ondrop="handleModalDrop(event)" onclick="document.getElementById('modalFileInput').click()">
            <i class="fa fa-cloud-upload fa-2x text-muted mb-2"></i>
            <p class="mb-1 small text-muted">Drag &amp; drop files here</p>
            <p class="mb-0 small text-muted">or click to browse — JPG, PNG, WEBP, MP4, MOV up to 20MB</p>
        </div>

        <input type="file" id="modalFileInput" name="media[]" multiple class="d-none"
            accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm">

        <div id="modalUploadList" class="d-flex flex-column gap-2 mt-3"></div>

        <div class="d-flex justify-content-end gap-2 mt-3" id="modalUploadActions" style="display:none;">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearModalUpload()">
                Clear
            </button>
            <button type="submit" class="btn btn-sm btn-primary" id="modalUploadSubmit">
                <i class="fa fa-upload me-1"></i> Upload <span id="modalUploadCount"></span>
            </button>
        </div>
    </form>
</div>
