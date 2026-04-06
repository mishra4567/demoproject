/**
 * Media Modal JS
 *  Add Image
 */

function addMediaForm() {
    let wrapper = document.getElementById("media-wrapper");
    let firstRow = document.querySelector(".media-row");
    let newRow = firstRow.cloneNode(true);

    // reset file input
    newRow.querySelector(".media-input").value = "";

    // reset tag input
    newRow.querySelector('input[name="tags[]"]').value = "";

    // reset preview
    let previewContainer = newRow.querySelector(".preview-container");
    let previewImg = newRow.querySelector(".preview-img");

    previewImg.src = "";
    previewContainer.classList.add("d-none");

    wrapper.appendChild(newRow);
}

// Remove row
function removeMediaForm(button) {
    let rows = document.querySelectorAll(".media-row");

    if (rows.length > 1) {
        button.closest(".media-row").remove();
    } else {
        alert("At least one media row is required");
    }
}

// Image preview
document.addEventListener("change", function (e) {
    if (e.target.classList.contains("media-input")) {
        let file = e.target.files[0];
        let preview = e.target
            .closest(".media-row")
            .querySelector(".preview-img");

        if (file) {
            let reader = new FileReader();
            reader.onload = function () {
                preview.src = reader.result;
                preview.classList.remove("d-none");
            };
            reader.readAsDataURL(file);
        }
    }
});
// Remove row

document.addEventListener("change", function (e) {
    if (e.target.matches(".media-input")) {
        const fileInput = e.target;
        const row = fileInput.closest(".media-row");
        const previewContainer = row.querySelector(".preview-container");
        const previewImg = row.querySelector(".preview-img");

        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (event) {
                previewImg.src = event.target.result;
                previewContainer.classList.remove("d-none");
            };

            reader.readAsDataURL(file);
        } else {
            previewImg.src = "";
            previewContainer.classList.add("d-none");
        }
    }
});

/**
 *  Media Model
 */

// let selectedMedia = null;

// function addMedia() {
//     let modal = new bootstrap.Modal(document.getElementById("mediaModal"));
//     modal.show();
// }

// // Click on media image
// // document.addEventListener("click", function (e) {
// //     if (e.target.closest(".media-card")) {
// //         let card = e.target.closest(".media-card");

// //         let mediaId = card.getAttribute("data-id");
// //         let fileName = card.getAttribute("data-file");

// //         selectedMedia = {
// //             id: mediaId,
// //             file: fileName,
// //         };

// //         // highlight selected
// //         document.querySelectorAll(".media-card").forEach((el) => {
// //             el.classList.remove("border-primary");
// //         });

// //         card.classList.add("border-primary");
// //     }
// // });

// // Click ADD button
// function addMediaButton() {
//     let selected = document.querySelector(
//         'input[name="selected_media"]:checked',
//     );

//     if (!selected) {
//         alert("Please select an image");
//         return;
//     }

//     let mediaId = selected.value;
//     let fileName = selected.getAttribute("data-file");

//     // save media id to hidden input
//     document.getElementById("product_media_id").value = mediaId;

//     // show preview
//     document.getElementById("product_media_preview").innerHTML =
//         `<img src="/storage/media/${fileName}"
//         class="img-fluid border rounded"
//         style="max-height:120px;">`;

//     // close modal
//     let modal = bootstrap.Modal.getInstance(
//         document.getElementById("mediaModal"),
//     );
//     modal.hide();
// }


let mediaTarget = "";

function addMedia(target) {
    mediaTarget = target;
        // Clear search & reload all on open
    document.getElementById('mediaSearch').value = '';
    loadMedia('');

    // Clear previous selection
    const checked = document.querySelector('input[name="selected_media"]:checked');
    if (checked) checked.checked = false;

    new bootstrap.Modal(document.getElementById('mediaModal')).show();
}

function addMediaButton() {
    const selected = document.querySelector('input[name="selected_media"]:checked');

    if (!selected) {
        alert('Please select an image');
        return;
    }

    const mediaId = selected.value;
    const file    = selected.dataset.file;

    // PRODUCT IMAGE
    if (mediaTarget === 'product') {
        document.getElementById('media_id_product').value = mediaId;
        document.getElementById('preview_product').innerHTML =
            `<img src="/storage/media/${file}" width="120" class="rounded">`;
    }

    // ATTRIBUTE IMAGE
    else if (mediaTarget.startsWith('attr_')) {
        document.getElementById('media_id_' + mediaTarget).value = mediaId;
        document.getElementById('preview_' + mediaTarget).innerHTML =
            `<img src="/storage/media/${file}" width="120" class="rounded">`;
    }

    // GALLERY IMAGE
    else if (mediaTarget === 'gallery') {
        document.getElementById('gallery_preview').insertAdjacentHTML(
            'beforeend',
            `<div class="gallery-item position-relative m-2">
                <input type="hidden" name="gallery_media_id[]" value="${mediaId}">
                <img src="/storage/media/${file}" width="120" class="rounded">
                <button type="button"
                    class="btn btn-danger btn-sm position-absolute top-0 end-0"
                    onclick="removeGalleryImage(this)">✕</button>
            </div>`
        );
    }

    bootstrap.Modal.getInstance(document.getElementById('mediaModal')).hide();
}
/**
 * This function for remove gallery image from preview and also remove the hidden input with media id
 */
function removeGalleryImage(btn) {
    btn.closest('.gallery-item').remove();
}


// 🔹 Render media grid
function renderMedia(items) {
    const grid     = document.getElementById('mediaGrid');
    const noResult = document.getElementById('mediaNoResult');

    if (items.length === 0) {
        grid.innerHTML = '';
        noResult.classList.remove('d-none');
        return;
    }

    noResult.classList.add('d-none');
    grid.innerHTML = items.map(item => `
        <div class="col-md-3 mb-4 text-center">
            <div class="media-card border p-2 rounded" style="cursor:pointer;">
                <input type="radio" name="selected_media"
                    value="${item.id}"
                    data-file="${item.file_name}"
                    class="form-check-input mb-2">
                <img src="/storage/media/${item.file_name}"
                    class="img-fluid"
                    style="height:120px; object-fit:cover;">
                <div class="mt-2 small">${item.tags ?? ''}</div>
            </div>
        </div>
    `).join('');
}

// 🔹 Load media via Ajax
function loadMedia(query) {
    fetch(`/admin/media/search?query=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => renderMedia(data))
        .catch(() => alert('Failed to load media'));
}

// 🔹 Search on type — debounced
let searchTimer;
document.getElementById('mediaSearch').addEventListener('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => loadMedia(this.value.trim()), 300);
});

// 🔹 Click card to select radio
document.addEventListener('click', function (e) {
    const card = e.target.closest('.media-card');
    if (card) {
        const radio = card.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    }
});
