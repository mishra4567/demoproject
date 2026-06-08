// resources/js/vendor/Back/services/brandService.js
import { router } from "@inertiajs/vue3";

const brandService = {
    save: (form, options = {}) => {
        form.post("/vendor/brands/save", {
            onSuccess: () => router.get("/vendor/brands"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },

    status: (id, options = {}) =>
        router.patch(`/vendor/brands/${id}/status`, {}, options),

    destroy: (id, options = {}) =>
        router.delete(`/vendor/brands/${id}`, options),

    restore: (id, options = {}) =>
        router.patch(`/vendor/brands/${id}/restore`, {}, options),

    forceDelete: (id, options = {}) =>
        router.delete(`/vendor/brands/${id}/force`, options),

    bulk: (action, ids, options = {}) =>
        router.post("/vendor/brands/bulk", { action, ids }, options),
};

export default brandService;
