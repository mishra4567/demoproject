// resources/js/vendor/Back/services/productService.js
import { router } from "@inertiajs/vue3";

const productService = {
    // ── Navigate to pages (Inertia visits) ──────────────────────
    index: (page = 1) =>
        router.get("/vendor/products", { page }, { preserveState: true }),

    // ── Manage Product Page (Create / Edit Same Page) ─────────
    manage: (id = null) =>
        id
            ? router.get(`/vendor/products/manageproduct/${id}`)
            : router.get("/vendor/products/manageproduct"),

    // ── Form submission (Inertia POST via useForm) ───────────────
    save: (form, options = {}) => {
        form.post("/vendor/products/manageproductprocess", {
            onSuccess: () => router.get("/vendor/products"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },

    // ── Soft delete (move to trash) ──────────────────────────────
    destroy: (id, options = {}) =>
        router.delete(`/vendor/products/${id}`, options),

    // ── Restore from trash ────────────────────────────────────────
    restore: (id, options = {}) =>
        router.patch(`/vendor/products/${id}/restore`, {}, options),

    // ── Permanently delete (blocked server-side, shows error message) ──
    permanentDelete: (id, options = {}) =>
        router.delete(`/vendor/products/${id}/force`, options),

    // ── Toggle active/inactive status ────────────────────────────
    status: (id, options = {}) =>
        router.patch(`/vendor/products/${id}/status`, {}, options),

    // ── Bulk action (activate/deactivate/trash/restore/permanent_delete) ──
    bulkAction: (data, options = {}) =>
        router.post("/vendor/products/bulk", data, options),
};

export default productService;
