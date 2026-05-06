/**
 *Search routing
 */
const searchInput = document.getElementById("adminSearch");
const resultBox = document.getElementById("searchResult");

searchInput.addEventListener("keyup", function () {
    let query = this.value.trim();

    if (query.length < 2) {
        resultBox.innerHTML = "";
        return;
    }

    fetch(`/admin/search-routes?q=${query}`)
        .then((res) => res.json())
        .then((data) => {
            let html = "";

            if (data.length === 0) {
                html = `<li class="list-group-item text-muted">No results found</li>`;
            } else {
                data.forEach((route) => {
                    html += `<li class="list-group-item p-0">
                                <a href="${route.uri}"
                                target="_blank"
                                class="search-item d-flex justify-content-between align-items-center px-3 py-2 text-decoration-none text-dark"
                                style="cursor:pointer">

                                    <div>
                                        <strong>${route.label ?? "Unknown"}</strong>
                                    </div>

                                    <i class="zmdi zmdi-arrow-right"></i>
                                </a>
                            </li>`;
                    {
                        /* <small class="text-muted">${route.uri}</small>; */
                    }
                });
            }

            resultBox.innerHTML = html;
        });
});
// Redirect on click
document.addEventListener("click", function (e) {
    if (!e.target.closest("#adminSearch")) {
        resultBox.innerHTML = "";
    }
});
/**
 *Search routing End
 */

/**
 * All Pages Bulk Action End
 */
// let i = 1;

function add_more() {
    // i++;
let i = document.querySelectorAll('[id^="product_attr_"]').length + 1;
    const row = document.querySelector("#product_attr_1").cloneNode(true);

    row.id = "product_attr_" + i;
    // reset paid id
    row.querySelector('input[name="paid[]"]').value = 0;

    // clear inputs
    row.querySelectorAll("input").forEach((el) => {
        if (el.type !== "hidden") el.value = "";
    });

    // reset select
    row.querySelectorAll("select").forEach((el) => {
        el.selectedIndex = 0;
    });

    // update media input
    const mediaInput = row.querySelector('input[name="media_id[]"]');
    mediaInput.id = "media_id_attr_" + i;
    mediaInput.value = "";

    // update preview
    const preview = row.querySelector('[id^="preview_attr_"]');
    preview.id = "preview_attr_" + i;
    preview.innerHTML = "";

    // // update button
    const btn = row.querySelector(".open-media-modal");
    btn.setAttribute("onclick", "addMedia('attr_" + i + "')");

    // change ADD button to REMOVE button
    const btnDiv = row.querySelector(".mt-4");
    btnDiv.innerHTML = `
        <button type="button" class="btn btn-sm btn-danger" onclick="remove_more(${i})">
            Remove Attribute
        </button>
    `;

    document.getElementById("product_attr_container").appendChild(row);
}

function remove_more(id) {
    let row = document.getElementById("product_attr_" + id);

    if (row) {
        row.remove();
    }
}
/**
 * For Add Product Attribute 2 End
 */
/**
 * For Add Product Attribute 3
 */
// let loop_count = 1;

// function add_more() {
//     loop_count++;
//     var size = document.getElementById("size_id").innerHTML;
//     var color = document.getElementById("color_id").innerHTML;
//     let html = `
//     <div class="mb-3" id="product_attr_${loop_count}">
//         <div class="row">

//             <div class="col-md-2">
//                 <input type="text" name="sku[]" class="form-control" placeholder="SKU">
//             </div>

//             <div class="col-md-2">
//                 <input type="text" name="mrp[]" class="form-control" placeholder="MRP">
//             </div>

//             <div class="col-md-2">
//                 <input type="text" name="price[]" class="form-control" placeholder="Price">
//             </div>
//             <div class="col-md-2">
//                 <label>Size</label>
//                 <select name="size_id[]" class="form-control">
//                     ${size}
//                 </select>
//             </div>
//             <div class="col-md-2">
//                 <label>Color</label>
//                 <select name="color_id[]" class="form-control">
//                     ${color}
//                 </select>
//             </div>

//             <div class="col-md-2">
//                 <input type="text" name="qty[]" class="form-control" placeholder="Qty">
//             </div>

//             <div class="col-md-4">

//                 <button type="button"
//                 class="btn btn-outline-primary"
//                 onclick="addMedia('attr_${loop_count}')">
//                 Select Image
//                 </button>

//                 <input type="hidden"
//                 name="media_id[]"
//                 id="media_id_attr_${loop_count}">

//                 <div id="preview_attr_${loop_count}" class="mt-2"></div>

//             </div>

//         </div>
//     </div>
//     `;

//     document
//         .getElementById("product_attr_container")
//         .insertAdjacentHTML("beforeend", html);
// }
/**
 * For Add Product Attribute 3 End
 */
/**
 * For Add Product Attribute
 */

// function remove_more(loop_count) {
//     document.getElementById("product_attr_" + loop_count).remove();
// }
/**
 * For Add Product Attribute
 */
/**
 *  For barcode download function
 */

//   For Product
function downloadBarcode(productId) {
    fetch('/admin/barcode/product/' + productId)
        .then(res => res.json())
        .then(({ svg, filename }) => {
            const a    = Object.assign(document.createElement('a'), {
                href:     URL.createObjectURL(new Blob([svg], { type: 'image/svg+xml' })),
                download: filename
            });
            a.click();
            URL.revokeObjectURL(a.href);
        });
}
/**
 *  For barcode download function
 */
/**
 *  Notification Start
 */

function showNotify(message, type = 'success_event') {
    const notifyArea = document.getElementById('notify-area');
    if (!notifyArea) return;

    notifyArea.innerHTML = `
        <div class="alert alert-${type}" role="alert">
            ${message}
        </div>
    `;

    setTimeout(() => {
        notifyArea.innerHTML = '';
    }, 3000);
}

/**
 *  Notification End
 */
// {{-- Info Popover JS — add once in layout --}}
document.addEventListener('DOMContentLoaded', function () {
    // Init all popovers
    var popoverList = [];
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (el) {
        var pop = new bootstrap.Popover(el, { trigger: 'manual', html: true });
        popoverList.push({ el: el, pop: pop });

        // Click on icon — toggle
        el.addEventListener('click', function (e) {
            e.stopPropagation(); // ✅ stop from bubbling to document
            popoverList.forEach(function (item) {
                if (item.el !== el) {
                    item.pop.hide(); // close others
                }
            });
            pop.toggle();
        });
    });

    // Click anywhere outside — close all
    document.addEventListener('click', function () {
        popoverList.forEach(function (item) {
            item.pop.hide();
        });
    });
});

// ─── Trash Toggle ──────────────────────────────────

let trashVisible = false;

function toggleTrash() {
    trashVisible = !trashVisible;

    const btn        = document.getElementById('trash_toggle_btn');
    if (!btn) return; // safe exit if button not on page
    const trashRows  = document.querySelectorAll('.trash-row');
    const activeRows = document.querySelectorAll('.active-row');
    // Switch bulk options
    const bulkType = document.getElementById('bulk_type')
    const bulkSelect = document.getElementById('bulk_action')
    const activeOptions = document.querySelectorAll('.active-option');
    const deletedOptions = document.querySelectorAll('.deleted-option');

    if (trashVisible) {
        // Show Deleted rows
        trashRows.forEach(r  => r.classList.remove('d-none'));
        activeRows.forEach(r => r.classList.add('d-none'));
        // Switsh bulk options to deleted
        if (bulkType) bulkType.value = 'deleted';
        if (bulkSelect) bulkSelect.value = '';
        activeOptions.forEach(o => o.classList.add('d-none'));
        deletedOptions.forEach(o => o.classList.remove('d-none'));
        btn.innerHTML = '<i class="fa fa-list me-1"></i> Show Active';
        btn.classList.replace('btn-secondary', 'btn-success');
    } else {
        // Show Active rows
        trashRows.forEach(r  => r.classList.add('d-none'));
        activeRows.forEach(r => r.classList.remove('d-none'));
        // Switsh bulk options to active
        if (bulkType) bulkType.value = 'active';
        if (bulkSelect) bulkSelect.value = '';
        activeOptions.forEach(o => o.classList.remove('d-none'));
        deletedOptions.forEach(o => o.classList.add('d-none'));
        btn.innerHTML = '<i class="fa fa-trash me-1"></i> Show Deleted';
        btn.classList.replace('btn-success', 'btn-secondary');
    }
}
// Select all checkboxes
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select_all');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            // Only select visible rows checkboxes
            document.querySelectorAll(
                trashVisible
                    ? '.trash-row .checkbox_ids'
                    : '.active-row .checkbox_ids'
            ).forEach(cb => cb.checked = this.checked);
        });
    }
});
