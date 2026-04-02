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
 * All Pages Bulk Action
 */
document.getElementById("select_all").addEventListener("click", function () {
    let checkboxes = document.querySelectorAll(".checkbox_ids");

    checkboxes.forEach((cb) => {
        cb.checked = this.checked;
    });
});

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
 *  Notification Start
 */

function showNotify(message, type = 'success') {
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
