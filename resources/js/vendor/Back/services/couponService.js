// resources/js/vendor/Back/services/couponService.js
import { router } from "@inertiajs/vue3";

const couponService = {
    index: () => router.get("/vendor/coupons", {}, { preserveState: true }),

    save: (form, options = {}) => {
        form.post("/vendor/coupons/save", {
            onSuccess: () => router.get("/vendor/coupons"),
            onError: (e) => console.error("Save failed", e),
            ...options,
        });
    },

    status: (id, options = {}) =>
        router.patch(`/vendor/coupons/${id}/status`, {}, options),

    destroy: (id, options = {}) =>
        router.delete(`/vendor/coupons/${id}`, options),

    restore: (id, options = {}) =>
        router.patch(`/vendor/coupons/${id}/restore`, {}, options),

    forceDelete: (id, options = {}) =>
        router.delete(`/vendor/coupons/${id}/force`, options),

    bulk: (action, ids, options = {}) =>
        router.post("/vendor/coupons/bulk", { action, ids }, options),
};

export default couponService;
