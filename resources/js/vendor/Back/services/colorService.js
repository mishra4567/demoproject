// resources/js/vendor/Back/services/colorService.js
import { router } from "@inertiajs/vue3";

const colorService = {
    save: (form, options = {}) => {
        form.post("/vendor/colors/save", {
            onSuccess: () => router.get("/vendor/colors"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },
    status: (id, options = {}) =>
        router.patch(`/vendor/colors/${id}/status`, {}, options),
    destroy: (id, options = {}) =>
        router.delete(`/vendor/colors/${id}`, options),
    restore: (id, options = {}) =>
        router.patch(`/vendor/colors/${id}/restore`, {}, options),
    forceDelete: (id, options = {}) =>
        router.delete(`/vendor/colors/${id}/force`, options),
    bulk: (action, ids, options = {}) =>
        router.post("/vendor/colors/bulk", { action, ids }, options),
};

export default colorService;
