// resources/js/vendor/Back/composables/useAuth.js
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import authService from "../services/authService";

export default function useAuth() {
    const page = usePage();
    const vendor = computed(() => page.props.auth?.vendor ?? null);
    const initial = computed(
        () => vendor.value?.name?.charAt(0).toUpperCase() ?? "V",
    );
    const isAuth = computed(() => !!vendor.value);

    const login = (form, options = {}) => authService.login(form, options);
    const register = (form, options = {}) =>
        authService.register(form, options);
    const logout = (options = {}) => authService.logout(options);

    return { vendor, initial, isAuth, login, register, logout };
}
