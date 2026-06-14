// resources/js/vendor/Back/services/categoryService.js
import { router } from "@inertiajs/vue3";

const categoryService = {
    save: (form, options = {}) => {
        form.post("/vendor/categories/save", {
            onSuccess: () => router.get("/vendor/categories"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },

    status: (id, options = {}) =>
        router.patch(`/vendor/categories/${id}/status`, {}, options),

    destroy: (id, options = {}) =>
        router.delete(`/vendor/categories/${id}`, options),

    restore: (id, options = {}) =>
        router.patch(`/vendor/categories/${id}/restore`, {}, options),

    forceDelete: (id, options = {}) =>
        router.delete(`/vendor/categories/${id}/force`, options),

    bulk: (action, ids, options = {}) =>
        router.post("/vendor/categories/bulk", { action, ids }, options),
};

export default categoryService;
