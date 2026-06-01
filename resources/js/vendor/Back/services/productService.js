// resources/js/services/productService.js
import { router } from "@inertiajs/vue3";
// import { route } from "ziggy-js";

const productService = {
    // ── Navigate to pages (Inertia visits) ──────────────────────
    index: (page = 1) =>
        router.get("/vendor/products", { page }, { preserveState: true }),
    // ── Manage Product Page (Create / Edit Same Page) ─────────
    // create: () => router.get(route("vendor.products.create")),
    // edit: (id) => router.get(route("vendor.products.edit", id)),
    manage: (id = null) =>
        id
            ? router.get(`/vendor/products/manageproduct/${id}`)
            : router.get("/vendor/products/manageproduct"),

    // ── Form submissions (Inertia POST/PUT/DELETE) ───────────────
    // store: (data, options = {}) =>
    //     router.post(route("vendor.products.store"), data, options),
    // update: (id, data, options = {}) =>
    //     router.put(route("vendor.products.update", id), data, options),
    save: (form, options = {}) => {
        form.post("/vendor/products/manageproductprocess", {
            onSuccess: () => router.get("/vendor/products"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },
    destroy: (id, options = {}) =>
        router.delete(`/vendor/products/${id}`, options),
};

export default productService;
