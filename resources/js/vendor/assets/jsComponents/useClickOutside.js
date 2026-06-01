// resources/js/vendor/Back/jsComponents/useClickOutside.js
import { onMounted, onUnmounted } from "vue";

export function useClickOutside(elementRef, callback) {
    function handle(event) {
        if (elementRef.value && !elementRef.value.contains(event.target)) {
            callback();
        }
    }

    onMounted(() => document.addEventListener("mousedown", handle));
    onUnmounted(() => document.removeEventListener("mousedown", handle));
}
