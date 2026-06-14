// resources/js/vendor/Back/services/linkProductService.js
import { router } from "@inertiajs/vue3";

const linkProductService = {
    save: (form, options = {}) => {
        form.post("/vendor/link-products/save", {
            onSuccess: () => router.get("/vendor/link-products"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },
    status: (id, options = {}) =>
        router.patch(`/vendor/link-products/${id}/status`, {}, options),
    destroy: (id, options = {}) =>
        router.delete(`/vendor/link-products/${id}`, options),
    restore: (id, options = {}) =>
        router.patch(`/vendor/link-products/${id}/restore`, {}, options),
    forceDelete: (id, options = {}) =>
        router.delete(`/vendor/link-products/${id}/force`, options),
    bulk: (action, ids, options = {}) =>
        router.post("/vendor/link-products/bulk", { action, ids }, options),
};

export default linkProductService;
