// resources/js/vendor/assets/jsComponents/useActiveLink.js
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function useActiveLink() {
    const page = usePage();

    const isActive = (path) => {
        // Strip query string before comparing
        const currentPath = page.url.split("?")[0];

        if (path === "/vendor") {
            return currentPath === "/vendor";
        }

        return currentPath === path || currentPath.startsWith(path + "/");
    };

    return { isActive };
}
