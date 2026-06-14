import { router } from "@inertiajs/vue3";

const sizeService = {
    save: (form, options = {}) => {
        form.post("/vendor/sizes/save", {
            onSuccess: () => router.get("/vendor/sizes"),
            ...options,
        });
    },

    status: (id, options = {}) =>
        router.patch(`/vendor/sizes/${id}/status`, {}, options),

    destroy: (id, options = {}) =>
        router.delete(`/vendor/sizes/${id}`, options),

    restore: (id, options = {}) =>
        router.patch(`/vendor/sizes/${id}/restore`, {}, options),

    forceDelete: (id, options = {}) =>
        router.delete(`/vendor/sizes/${id}/force`, options),

    bulk: (action, ids, options = {}) =>
        router.post("/vendor/sizes/bulk", { action, ids }, options),
};

export default sizeService;
