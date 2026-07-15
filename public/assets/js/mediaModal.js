/**
 * Media Modal JS
 */

// ============================================================
// Globals — declared ONCE here, nowhere else
// ============================================================
let mediaTarget = "";
let searchTimer;
const MAX_FILES = 10;
const VIDEO_TYPES_MIME = [
    "video/mp4",
    "video/quicktime",
    "video/x-msvideo",
    "video/webm",
];
const IMAGE_TYPES_MIME = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
const VIDEO_TYPES_EXT = ["mp4", "mov", "avi", "webm"];

// ============================================================
// Upload Form — Add / Remove rows
// ============================================================

// function addMediaForm() {
//     const rows = document.querySelectorAll(".media-row");

//     if (rows.length >= MAX_FILES) {
//         alert(`Maximum ${MAX_FILES} files allowed per upload.`);
//         return;
//     }

//     const wrapper = document.getElementById("media-wrapper");
//     const firstRow = document.querySelector(".media-row");
//     const newRow = firstRow.cloneNode(true);
//     const newIndex = rows.length + 1;

//     // Update row number badge
//     const badge = newRow.querySelector(".badge");
//     if (badge) badge.textContent = `File ${newIndex}`;

//     // Reset inputs
//     newRow.querySelector(".media-input").value = "";
//     newRow.querySelector('input[name="tags[]"]').value = "";
//     newRow.querySelector('textarea[name="description[]"]').value = "";

//     // Reset drop zone
//     const dropLabel = newRow.querySelector(".file-drop-label");
//     const dropZone = newRow.querySelector(".file-drop-zone");
//     if (dropLabel) dropLabel.textContent = "Click to choose file";
//     if (dropZone) {
//         dropZone.classList.remove(
//             "border-success",
//             "bg-success",
//             "border-primary",
//             "bg-primary",
//             "bg-opacity-10",
//         );
//     }

//     // Reset preview
//     const previewContainer = newRow.querySelector(".preview-container");
//     const previewImg = newRow.querySelector(".preview-img");
//     const previewVideo = newRow.querySelector(".preview-video");
//     const fileInfo = newRow.querySelector(".file-info");

//     previewImg.src = "";
//     previewVideo.src = "";
//     previewImg.classList.add("d-none");
//     previewVideo.classList.add("d-none");
//     previewContainer.classList.add("d-none");
//     if (fileInfo) fileInfo.textContent = "";

//     wrapper.appendChild(newRow);
//     updateCounter();
// }

// function removeMediaForm(button) {
//     const rows = document.querySelectorAll(".media-row");
//     if (rows.length > 1) {
//         const video = button
//             .closest(".media-row")
//             .querySelector(".preview-video");
//         if (video) {
//             video.pause();
//             video.src = "";
//         }
//         button.closest(".media-row").remove();
//         updateCounter();
//     } else {
//         alert("At least one media row is required.");
//     }
// }

// ============================================================
// Add to your existing media-modal.js
// Replace your current addMediaForm() and removeMediaForm() with these
// ============================================================

function addMediaForm() {
    const rows = document.querySelectorAll(".media-row");

    if (rows.length >= MAX_FILES) {
        alert(`Maximum ${MAX_FILES} files allowed per upload.`);
        return;
    }

    const wrapper = document.getElementById("media-wrapper");
    const firstRow = document.querySelector(".media-row");
    const newRow = firstRow.cloneNode(true);
    const newIndex = rows.length + 1;

    // ✅ Update row number badge
    const badge = newRow.querySelector(".badge");
    if (badge) badge.textContent = `File ${newIndex}`;

    // ✅ Always show remove button
    const removeCol = newRow.querySelector(".remove-col");
    if (removeCol) removeCol.classList.remove("d-none");

    // ✅ Update tab onclick attributes with new index
    const fileTabBtn = newRow.querySelector(".tab-file-btn");
    const urlTabBtn = newRow.querySelector(".tab-url-btn");
    if (fileTabBtn)
        fileTabBtn.setAttribute(
            "onclick",
            `switchTab(this, 'file', ${newIndex}); return false;`,
        );
    if (urlTabBtn)
        urlTabBtn.setAttribute(
            "onclick",
            `switchTab(this, 'url',  ${newIndex}); return false;`,
        );

    // ✅ Rename tab content divs to new index
    const fileContent = newRow.querySelector(`[class*="tab-file-content-"]`);
    const urlContent = newRow.querySelector(`[class*="tab-url-content-"]`);
    if (fileContent)
        fileContent.className = fileContent.className.replace(
            /tab-file-content-\d+/,
            `tab-file-content-${newIndex}`,
        );
    if (urlContent)
        urlContent.className = urlContent.className.replace(
            /tab-url-content-\d+/,
            `tab-url-content-${newIndex}`,
        );

    // ✅ Reset to file tab (in case URL was active on cloned row)
    if (fileContent) fileContent.classList.remove("d-none");
    if (urlContent) urlContent.classList.add("d-none");
    if (fileTabBtn) fileTabBtn.classList.add("active");
    if (urlTabBtn) urlTabBtn.classList.remove("active");

    // ✅ Update file input ID + label
    const fileInput = newRow.querySelector(".media-input");
    const fileLabel = newRow.querySelector(".file-drop-zone");
    if (fileInput) {
        fileInput.id = `media-input-${newIndex}`;
        fileInput.value = "";
        fileInput.required = true;
    }
    if (fileLabel) fileLabel.setAttribute("for", `media-input-${newIndex}`);

    // ✅ Update URL input ID + oninput + reset value
    const urlInput = newRow.querySelector(".url-input");
    if (urlInput) {
        urlInput.id = `url-input-${newIndex}`;
        urlInput.value = "";
        urlInput.setAttribute("oninput", `previewUrl(this, ${newIndex})`);
    }

    // ✅ Reset tag + description
    newRow.querySelector('input[name="tags[]"]').value = "";
    newRow.querySelector('textarea[name="description[]"]').value = "";

    // ✅ Reset drop zone label + styles
    const dropLabel = newRow.querySelector(".file-drop-label");
    const dropZone = newRow.querySelector(".file-drop-zone");
    if (dropLabel) dropLabel.textContent = "Click or drag & drop";
    if (dropZone) {
        dropZone.classList.remove(
            "border-success",
            "bg-success",
            "border-primary",
            "bg-primary",
            "bg-opacity-10",
        );
    }

    // ✅ Reset preview
    const previewContainer = newRow.querySelector(".preview-container");
    const previewImg = newRow.querySelector(".preview-img");
    const previewVideo = newRow.querySelector(".preview-video");
    const fileInfo = newRow.querySelector(".file-info");

    if (previewImg) {
        previewImg.src = "";
        previewImg.classList.add("d-none");
    }
    if (previewVideo) {
        previewVideo.src = "";
        previewVideo.classList.add("d-none");
    }
    if (previewContainer) previewContainer.classList.add("d-none");
    if (fileInfo) fileInfo.textContent = "";

    wrapper.appendChild(newRow);
    updateCounter();
}

function removeMediaForm(button) {
    const rows = document.querySelectorAll(".media-row");
    if (rows.length > 1) {
        const video = button
            .closest(".media-row")
            .querySelector(".preview-video");
        if (video) {
            video.pause();
            video.src = "";
        }
        button.closest(".media-row").remove();
        updateCounter();

        // ✅ If only one row left, hide its remove button
        const remaining = document.querySelectorAll(".media-row");
        if (remaining.length === 1) {
            const lastRemoveCol = remaining[0].querySelector(".remove-col");
            if (lastRemoveCol) lastRemoveCol.classList.add("d-none");
        }
    } else {
        alert("At least one media row is required.");
    }
}

function updateCounter() {
    const counter = document.getElementById("file-counter");
    if (!counter) return;
    const total = document.querySelectorAll(".media-row").length;
    const filled = [...document.querySelectorAll(".media-input")].filter(
        (i) => i.files.length > 0,
    ).length;
    counter.textContent = `${filled} / ${total} selected`;
}

// ============================================================
// Upload Form — file change listener
// ============================================================

document.addEventListener("change", function (e) {
    if (!e.target.matches(".media-input")) return;

    const fileInput = e.target;
    const row = fileInput.closest(".media-row");
    const previewContainer = row.querySelector(".preview-container");
    const previewImg = row.querySelector(".preview-img");
    const previewVideo = row.querySelector(".preview-video");
    const fileInfo = row.querySelector(".file-info");
    const dropLabel = row.querySelector(".file-drop-label");
    const dropZone = row.querySelector(".file-drop-zone");
    const file = fileInput.files[0];

    // ── Reset everything ──
    previewImg.src = "";
    previewVideo.src = "";
    previewImg.classList.add("d-none");
    previewVideo.classList.add("d-none");
    previewContainer.classList.add("d-none");
    if (fileInfo) fileInfo.textContent = "";
    if (dropLabel) dropLabel.textContent = "Click to choose file";
    if (dropZone) {
        dropZone.classList.remove(
            "border-success",
            "bg-success",
            "border-primary",
            "bg-primary",
            "bg-opacity-10",
        );
    }

    if (!file) {
        updateCounter();
        return;
    }

    // ── Validate type ──
    if (![...VIDEO_TYPES_MIME, ...IMAGE_TYPES_MIME].includes(file.type)) {
        alert("Unsupported file type: " + file.type);
        fileInput.value = "";
        updateCounter();
        return;
    }

    // ── Update drop zone label + color ──
    if (dropLabel) dropLabel.textContent = file.name;
    if (dropZone) {
        dropZone.classList.add("border-success", "bg-success", "bg-opacity-10");
    }

    // ── Show file size info ──
    if (fileInfo) {
        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
        fileInfo.textContent = `${sizeMB} MB`;
    }

    const isVideo = VIDEO_TYPES_MIME.includes(file.type);
    const reader = new FileReader();

    reader.onload = function (event) {
        if (isVideo) {
            previewVideo.src = event.target.result;
            previewVideo.classList.remove("d-none");
            previewImg.classList.add("d-none");
        } else {
            previewImg.src = event.target.result;
            previewImg.classList.remove("d-none");
            previewVideo.classList.add("d-none");
        }
        previewContainer.classList.remove("d-none");
        updateCounter();
    };

    reader.readAsDataURL(file);
});
// ============================================================
// Drag & drop support for upload rows
// Add this to your existing media-modal.js file
// ============================================================

document.addEventListener("dragover", function (e) {
    const zone = e.target.closest(".file-drop-zone");
    if (zone) {
        e.preventDefault();
        zone.classList.add("border-primary", "bg-primary", "bg-opacity-10");
    }
});

document.addEventListener("dragleave", function (e) {
    const zone = e.target.closest(".file-drop-zone");
    if (zone) {
        zone.classList.remove("border-primary", "bg-primary", "bg-opacity-10");
    }
});

document.addEventListener("drop", function (e) {
    const zone = e.target.closest(".file-drop-zone");
    if (!zone) return;

    e.preventDefault();
    zone.classList.remove("border-primary", "bg-primary", "bg-opacity-10");

    const row = zone.closest(".media-row");
    const input = row.querySelector(".media-input");
    const file = e.dataTransfer.files[0];

    if (file && input) {
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;

        // trigger the existing change listener (handles preview, validation, etc.)
        input.dispatchEvent(new Event("change", { bubbles: true }));
    }
});
// ============================================================
// Upload Row — Tab switch (File | URL)
// ============================================================

function switchTab(btn, type, rowIndex) {
    const row = btn.closest(".media-row");
    const fileTab = row.querySelector(`.tab-file-content-${rowIndex}`);
    const urlTab = row.querySelector(`.tab-url-content-${rowIndex}`);
    const fileBtn = row.querySelector(".tab-file-btn");
    const urlBtn = row.querySelector(".tab-url-btn");
    const fileInput = row.querySelector(".media-input");
    const urlInput = row.querySelector(".url-input");

    if (type === "file") {
        fileTab.classList.remove("d-none");
        urlTab.classList.add("d-none");
        fileBtn.classList.add("active");
        urlBtn.classList.remove("active");

        // ✅ Clear URL input so it doesn't submit
        if (urlInput) urlInput.value = "";

        // ✅ Make file input required again
        if (fileInput) fileInput.required = true;
    } else {
        urlTab.classList.remove("d-none");
        fileTab.classList.add("d-none");
        urlBtn.classList.add("active");
        fileBtn.classList.remove("active");

        // ✅ Remove required from file input
        if (fileInput) {
            fileInput.required = false;
            fileInput.value = "";
        }

        // Reset preview
        resetPreview(row);
    }
}

// ============================================================
// URL Preview — show image/video from URL
// ============================================================

let urlPreviewTimers = {};

function previewUrl(input, rowIndex) {
    const url = input.value.trim();
    const row = input.closest(".media-row");

    // Debounce 600ms
    clearTimeout(urlPreviewTimers[rowIndex]);
    urlPreviewTimers[rowIndex] = setTimeout(() => {
        if (!url) {
            resetPreview(row);
            return;
        }

        const ext = url.split(".").pop().split("?")[0].toLowerCase();
        const imgExts = ["jpg", "jpeg", "png", "webp", "gif"];
        const vidExts = ["mp4", "mov", "avi", "webm"];

        const previewContainer = row.querySelector(".preview-container");
        const previewImg = row.querySelector(".preview-img");
        const previewVideo = row.querySelector(".preview-video");
        const fileInfo = row.querySelector(".file-info");

        // Reset
        previewImg.classList.add("d-none");
        previewVideo.classList.add("d-none");
        previewImg.src = "";
        previewVideo.src = "";

        if (imgExts.includes(ext)) {
            // ✅ Image URL preview
            previewImg.src = url;
            previewImg.onload = () => {
                previewImg.classList.remove("d-none");
                previewContainer.classList.remove("d-none");
                if (fileInfo) fileInfo.textContent = `Image from URL`;
            };
            previewImg.onerror = () => {
                if (fileInfo)
                    fileInfo.textContent = "⚠ Cannot preview this URL";
                previewContainer.classList.add("d-none");
            };
        } else if (vidExts.includes(ext)) {
            // ✅ Video URL preview
            previewVideo.src = url;
            previewVideo.classList.remove("d-none");
            previewContainer.classList.remove("d-none");
            if (fileInfo) fileInfo.textContent = `Video from URL`;
        } else {
            // ✅ Unknown — try as image
            previewImg.src = url;
            previewImg.onload = () => {
                previewImg.classList.remove("d-none");
                previewContainer.classList.remove("d-none");
                if (fileInfo) fileInfo.textContent = `Media from URL`;
            };
            previewImg.onerror = () => {
                if (fileInfo)
                    fileInfo.textContent = "⚠ Unsupported or unreachable URL";
            };
        }

        updateCounter();
    }, 600);
}

// ============================================================
// Helper — reset preview in a row
// ============================================================

function resetPreview(row) {
    const previewContainer = row.querySelector(".preview-container");
    const previewImg = row.querySelector(".preview-img");
    const previewVideo = row.querySelector(".preview-video");
    const fileInfo = row.querySelector(".file-info");

    if (previewContainer) previewContainer.classList.add("d-none");
    if (previewImg) {
        previewImg.classList.add("d-none");
        previewImg.src = "";
    }
    if (previewVideo) {
        previewVideo.classList.add("d-none");
        previewVideo.src = "";
    }
    if (fileInfo) fileInfo.textContent = "";
}

// ============================================================
// Media Modal — open / close
// ============================================================

function addMedia(target, title = "Select Media") {
    mediaTarget = target;

    document.getElementById("mediaModalTitle").textContent = title;
    document.getElementById("mediaSearch").value = "";

    // Show all Blade-rendered grid items, remove leftover AJAX results
    document
        .querySelectorAll(".media-grid-item.ajax-result")
        .forEach((el) => el.remove());
    document
        .querySelectorAll(".media-grid-item")
        .forEach((item) => item.classList.remove("d-none"));
    document.getElementById("mediaNoResult")?.classList.add("d-none");

    // Reset filter tabs
    document.querySelectorAll(".filter-btn").forEach((b) => {
        b.classList.remove("btn-dark", "active");
        b.classList.add("btn-outline-secondary");
    });
    const allBtn = document.querySelector('.filter-btn[onclick*="all"]');
    if (allBtn) {
        allBtn.classList.remove("btn-outline-secondary");
        allBtn.classList.add("btn-dark", "active");
    }

    // Clear selection + highlights
    const checked = document.querySelector(
        'input[name="selected_media"]:checked',
    );
    if (checked) checked.checked = false;
    document.querySelectorAll(".media-card").forEach((c) => {
        c.classList.remove("border-primary", "shadow-sm");
        c.style.borderColor = "";
    });

    const info = document.getElementById("selectedFileInfo");
    if (info) info.textContent = "No file selected";

    closeInlinePlayer();

    new bootstrap.Modal(document.getElementById("mediaModal")).show();
}

function addMediaButton() {
    const selected = document.querySelector(
        'input[name="selected_media"]:checked',
    );
    if (!selected) {
        alert("Please select a media item.");
        return;
    }

    const mediaId = selected.value;
    const file = selected.dataset.file;
    const mediaType = selected.dataset.type?.toLowerCase() ?? "";
    const streamUrl = selected.dataset.stream ?? `/storage/media/${file}`;
    const isVideo = VIDEO_TYPES_EXT.includes(mediaType);

    const previewHtml = isVideo
        ? `<video src="${streamUrl}" width="120" height="80" class="rounded"
               controls muted preload="metadata" style="object-fit:cover;"></video>`
        : `<img src="${streamUrl}" width="120" height="80" class="rounded"
               style="object-fit:cover;">`;

    if (mediaTarget === "product") {
        document.getElementById("media_id_product").value = mediaId;
        document.getElementById("preview_product").innerHTML = previewHtml;
    } else if (mediaTarget.startsWith("attr_")) {
        document.getElementById("media_id_" + mediaTarget).value = mediaId;
        document.getElementById("preview_" + mediaTarget).innerHTML =
            previewHtml;
    } else if (mediaTarget === "gallery") {
        document.getElementById("gallery_preview").insertAdjacentHTML(
            "beforeend",
            `<div class="gallery-item position-relative m-2">
                <input type="hidden" name="gallery_media_id[]" value="${mediaId}">
                ${previewHtml}
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                    onclick="removeGalleryImage(this)">✕</button>
            </div>`,
        );
    }

    stopModalVideo();
    bootstrap.Modal.getInstance(document.getElementById("mediaModal")).hide();
}

function removeGalleryImage(btn) {
    btn.closest(".gallery-item").remove();
}

// ============================================================
// Modal — Inline player
// ============================================================

function openInlinePlayer(streamUrl, filename) {
    const wrap = document.getElementById("inlinePlayerWrap");
    const player = document.getElementById("inlinePlayer");
    const title = document.getElementById("inlinePlayerTitle");
    if (!wrap || !player) return;

    player.src = streamUrl;
    title.textContent = filename;
    // Disable some browser controls
    player.setAttribute(
        "controlsList",
        "nodownload noplaybackrate nofullscreen",
    );

    player.disablePictureInPicture = true;

    player.oncontextmenu = function (e) {
        e.preventDefault();
        return false;
    };
    wrap.classList.remove("d-none");
    player.load();
    player.play();
    wrap.scrollIntoView({ behavior: "smooth", block: "nearest" });
}

function closeInlinePlayer() {
    const player = document.getElementById("inlinePlayer");
    if (!player) return;
    player.pause();
    player.src = "";

    const wrap = document.getElementById("inlinePlayerWrap");
    if (wrap) wrap.classList.add("d-none");

    const title = document.getElementById("inlinePlayerTitle");
    if (title) title.textContent = "";
}

function stopModalVideo() {
    closeInlinePlayer();
    document.querySelectorAll("#mediaGrid video").forEach((v) => {
        v.pause();
        v.currentTime = 0;
    });
}

document
    .getElementById("mediaModal")
    ?.addEventListener("hidden.bs.modal", stopModalVideo);

// ============================================================
// Modal — Card selection
// ============================================================

function selectMediaCard(card) {
    document.querySelectorAll(".media-card").forEach((c) => {
        c.classList.remove("border-primary", "shadow-sm");
        c.style.borderColor = "";
    });

    card.classList.add("border-primary", "shadow-sm");
    card.style.borderColor = "#0d6efd";

    const radio = card.querySelector(".selected-radio");
    if (radio) radio.checked = true;

    const file = radio?.dataset.file ?? "";
    const type = radio?.dataset.type?.toUpperCase() ?? "";
    const kind = radio?.dataset.kind ?? "";

    const info = document.getElementById("selectedFileInfo");
    if (info) {
        info.innerHTML = `<i class="fa fa-${kind === "video" ? "play-circle" : "image"} me-1"></i>
             <strong>${file}</strong>
             <span class="badge bg-secondary ms-1">${type}</span>`;
    }
}

// ============================================================
// Modal — Filter tabs
// ============================================================

function filterMedia(kind, btn) {
    document.querySelectorAll(".filter-btn").forEach((b) => {
        b.classList.remove("btn-dark", "active");
        b.classList.add("btn-outline-secondary");
    });
    btn.classList.remove("btn-outline-secondary");
    btn.classList.add("btn-dark", "active");

    document.querySelectorAll(".media-grid-item").forEach((item) => {
        const show = kind === "all" || item.dataset.kind === kind;
        item.classList.toggle("d-none", !show);
    });

    const visible = document.querySelectorAll(
        ".media-grid-item:not(.d-none)",
    ).length;
    document
        .getElementById("mediaNoResult")
        ?.classList.toggle("d-none", visible > 0);
}

// ============================================================
// Modal — AJAX search
// ============================================================

function renderMedia(items) {
    const grid = document.getElementById("mediaGrid");
    const noResult = document.getElementById("mediaNoResult");
    if (!grid) return;

    document
        .querySelectorAll(".media-grid-item")
        .forEach((item) => item.classList.add("d-none"));
    document
        .querySelectorAll(".media-grid-item.ajax-result")
        .forEach((el) => el.remove());

    if (items.length === 0) {
        noResult?.classList.remove("d-none");
        return;
    }

    noResult?.classList.add("d-none");

    const serveBase = window.MEDIA_SERVE_BASE ?? "/admin/media/serve/";
    const streamBase = window.MEDIA_STREAM_BASE ?? "/admin/media/stream/";

    const html = items
        .map((item) => {
            const isVideo = VIDEO_TYPES_EXT.includes(
                item.media_type?.toLowerCase(),
            );
            const serveUrl = serveBase + item.file_name;
            const streamUrl = streamBase + item.file_name;
            const mediaUrl = isVideo ? streamUrl : serveUrl;

            const thumbnail = isVideo
                ? `<div class="position-relative">
                   <video src="${streamUrl}"
                       style="height:100px;width:100%;object-fit:cover;border-radius:4px;pointer-events:none;"
                       muted preload="metadata"></video>
                   <div class="position-absolute top-50 start-50 translate-middle" style="pointer-events:none;">
                       <span class="badge bg-dark bg-opacity-75"
                           style="font-size:16px;padding:4px 8px;border-radius:50%;">▶</span>
                   </div>
                   <span class="position-absolute top-0 end-0 badge bg-secondary m-1" style="font-size:9px;">
                       ${item.media_type?.toUpperCase()}
                   </span>
               </div>
               <button type="button" class="btn btn-outline-dark btn-sm w-100 mt-1" style="font-size:11px;"
                   onclick="event.stopPropagation(); openInlinePlayer('${streamUrl}', '${item.file_name}')">
                   <i class='fa fa-play me-1'></i> Preview
               </button>`
                : `<img src="${serveUrl}"
                   style="height:100px;width:100%;object-fit:cover;border-radius:4px;"
                   loading="lazy">`;

            return `
        <div class="col-6 col-md-3 media-grid-item ajax-result"
            data-kind="${isVideo ? "video" : "image"}">
            <div class="media-card border rounded p-1 text-center" style="cursor:pointer;"
                onclick="selectMediaCard(this)">
                <input type="radio" name="selected_media"
                    value="${item.id}"
                    data-file="${item.file_name}"
                    data-type="${item.media_type}"
                    data-stream="${mediaUrl}"
                    data-kind="${isVideo ? "video" : "image"}"
                    class="d-none selected-radio">
                ${thumbnail}
                <div class="mt-1 small text-truncate px-1">${item.tags ?? "—"}</div>
            </div>
        </div>`;
        })
        .join("");

    grid.insertAdjacentHTML("beforeend", html);
}

function loadMedia(query) {
    fetch(`/admin/media/search?query=${encodeURIComponent(query)}`)
        .then((res) => res.json())
        .then((data) => renderMedia(data))
        .catch(() => alert("Failed to load media."));
}

document.getElementById("mediaSearch")?.addEventListener("input", function () {
    const query = this.value.trim();
    clearTimeout(searchTimer);

    if (query === "") {
        document
            .querySelectorAll(".media-grid-item.ajax-result")
            .forEach((el) => el.remove());
        document
            .querySelectorAll(".media-grid-item")
            .forEach((item) => item.classList.remove("d-none"));
        document.getElementById("mediaNoResult")?.classList.add("d-none");
        return;
    }

    searchTimer = setTimeout(() => loadMedia(query), 300);
});

// ============================================================
// Add this to media-modal.js
// Toggles the upload drop zone inside the "Select Media" modal
// ============================================================

let modalSelectedFiles = [];

function toggleModalUpload() {
    const wrap = document.getElementById("modalUploadWrap");
    const isHidden = wrap.classList.contains("d-none");
    wrap.classList.toggle("d-none", !isHidden);

    if (!isHidden) {
        // closing — clear any staged files
        clearModalUpload();
    }
}

function handleModalDrop(e) {
    e.preventDefault();
    const zone = document.getElementById("modalDropZone");
    zone.classList.remove("border-primary", "bg-primary", "bg-opacity-10");

    const files = [...e.dataTransfer.files];
    addModalFiles(files);
}

document
    .getElementById("modalFileInput")
    ?.addEventListener("change", function () {
        addModalFiles([...this.files]);
    });

function addModalFiles(files) {
    const valid = files.filter((f) =>
        [...VIDEO_TYPES_MIME, ...IMAGE_TYPES_MIME].includes(f.type),
    );

    const invalid = files.length - valid.length;
    if (invalid > 0) {
        alert(`${invalid} file(s) skipped — unsupported type.`);
    }

    modalSelectedFiles = [...modalSelectedFiles, ...valid];
    renderModalFileList();
}

function renderModalFileList() {
    const list = document.getElementById("modalUploadList");
    const actions = document.getElementById("modalUploadActions");
    const countEl = document.getElementById("modalUploadCount");

    list.innerHTML = modalSelectedFiles
        .map((file, i) => {
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
            const isVideo = VIDEO_TYPES_MIME.includes(file.type);
            return `
            <div class="d-flex align-items-center justify-content-between border rounded-2 px-3 py-2 bg-white">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa fa-${isVideo ? "film text-danger" : "image text-primary"}"></i>
                    <span class="small">${file.name}</span>
                    <span class="badge bg-light text-muted">${sizeMB} MB</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="removeModalFile(${i})">
                    <i class="fa fa-times"></i>
                </button>
            </div>`;
        })
        .join("");

    actions.style.display = modalSelectedFiles.length ? "flex" : "none";
    if (countEl) {
        countEl.textContent = modalSelectedFiles.length
            ? `(${modalSelectedFiles.length})`
            : "";
    }

    syncModalFileInput();
}

function removeModalFile(index) {
    modalSelectedFiles.splice(index, 1);
    renderModalFileList();
}

function clearModalUpload() {
    modalSelectedFiles = [];
    document.getElementById("modalFileInput").value = "";
    renderModalFileList();
}

// Sync staged files array back into the actual <input type="file"> before submit
function syncModalFileInput() {
    const input = document.getElementById("modalFileInput");
    const dt = new DataTransfer();
    modalSelectedFiles.forEach((file) => dt.items.add(file));
    input.files = dt.files;
}

// After successful upload, refresh the media grid instead of leaving the modal
document
    .getElementById("modalUploadForm")
    ?.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = document.getElementById("modalUploadSubmit");
        const uploadCount = document.getElementById("modalUploadCount");
        if (uploadCount) {
            uploadCount.textContent = "0";
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<i class="fa fa-spinner fa-spin me-1"></i> Uploading...';

        fetch(this.action, {
            method: "POST",
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((res) => {
                if (!res.ok) throw new Error("Upload failed");
                return res.json();
            })
            .then((data) => {
                // console.log(data);
                // console.log("before clear");
                clearModalUpload();
                // console.log("before toggle");
                toggleModalUpload();
                // console.log("before load");
                loadMedia("");
                // console.log("done");
            })
            .catch((error) => {
                // console.error(error);
                // alert(error.message);
                alert("Upload failed. Please try again.");
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML =
                    '<i class="fa fa-upload me-1"></i> Upload <span id="modalUploadCount"></span>';
            });
    });

function openMediaPreview(url, type, filename = "") {
    const image = document.getElementById("previewImage");
    const video = document.getElementById("previewVideo");
    const source = document.getElementById("previewVideoSource");
    const nameEl = document.getElementById("mediaPreviewFilename");
    const download = document.getElementById("mediaPreviewDownload");
    const newTab = document.getElementById("mediaPreviewNewTab");

    // Reset
    image.classList.add("d-none");
    video.classList.add("d-none");
    image.src = "";
    source.src = "";

    // ✅ Set filename + links
    const name = filename || url.split("/").pop();
    if (nameEl) nameEl.textContent = name;
    if (download) {
        download.href = url;
        download.setAttribute("download", name);
    }
    if (newTab) newTab.href = url;

    if (type === "image") {
        image.src = url;
        image.classList.remove("d-none");
    } else {
        source.src = url;
        video.setAttribute(
            "controlsList",
            "nodownload noplaybackrate nofullscreen",
        );
        video.disablePictureInPicture = true;
        video.oncontextmenu = (e) => {
            e.preventDefault();
            return false;
        };
        video.load();
        video.classList.remove("d-none");
    }

    new bootstrap.Modal(document.getElementById("mediaPreviewModal")).show();
}

document
    .getElementById("mediaPreviewModal")
    ?.addEventListener("hidden.bs.modal", function () {
        const video = document.getElementById("previewVideo");
        const source = document.getElementById("previewVideoSource");
        const image = document.getElementById("previewImage");

        // ✅ Full cleanup
        video.pause();
        video.currentTime = 0;
        source.src = "";
        image.src = "";
    });
