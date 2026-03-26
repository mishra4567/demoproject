let currentRow = null;

function addMedia(row) {
    currentRow = row;
    let modal = new bootstrap.Modal(document.getElementById("mediaModal"));
    modal.show();
}

function addMediaButton() {
    let selected = document.querySelector(
        'input[name="selected_media"]:checked',
    );

    if (!selected) {
        alert("Please select an image");
        return;
    }

    let mediaId = selected.value;
    let fileName = selected.dataset.file;

    document.getElementById("product_media_id_" + currentRow).value = mediaId;

    document.getElementById("product_media_preview_" + currentRow).innerHTML =
        `<img src="/storage/media/${fileName}"
        class="img-fluid border rounded"
        style="max-height:120px;">`;

    bootstrap.Modal.getInstance(document.getElementById("mediaModal")).hide();
}
