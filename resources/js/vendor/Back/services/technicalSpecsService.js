// resources/js/vendor/Back/services/technicalSpecsService.js
import { router } from "@inertiajs/vue3";

const technicalSpecsService = {
    save: (form, options = {}) => {
        form.post("/vendor/technical-specs/save", {
            onSuccess: () => router.get("/vendor/technical-specs"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },
    status: (id, options = {}) =>
        router.patch(`/vendor/technical-specs/${id}/status`, {}, options),
    destroy: (id, options = {}) =>
        router.delete(`/vendor/technical-specs/${id}`, options),
    restore: (id, options = {}) =>
        router.patch(`/vendor/technical-specs/${id}/restore`, {}, options),
    forceDelete: (id, options = {}) =>
        router.delete(`/vendor/technical-specs/${id}/force`, options),
    bulk: (action, ids, options = {}) =>
        router.post("/vendor/technical-specs/bulk", { action, ids }, options),
};

export default technicalSpecsService;
