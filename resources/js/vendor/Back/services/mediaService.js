// resources/js/vendor/Back/services/mediaService.js

import { router } from "@inertiajs/vue3";

const mediaService = {
    index: (page = 1) =>
        router.get("/vendor/media", { page }, { preserveState: true }),

    create: () => router.get("/vendor/media/create"),

    save: (data, options = {}) =>
        router.post("/vendor/media/store", data, {
            forceFormData: true,
            ...options,
        }),

    destroy: (id, options = {}) =>
        router.delete(`/vendor/media/${id}`, options),
};

export default mediaService;
