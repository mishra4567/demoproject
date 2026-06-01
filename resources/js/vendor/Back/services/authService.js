// resources/js/vendor/Back/services/authService.js
import { router } from "@inertiajs/vue3";

const authService = {
    login: (form, options = {}) => form.post("/vendor/login", options),
    register: (form, options = {}) => form.post("/vendor/register", options),
    logout: (options = {}) => router.post("/vendor/logout", {}, options),
};

export default authService;
