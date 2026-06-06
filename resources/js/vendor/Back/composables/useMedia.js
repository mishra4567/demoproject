// resources/js/vendor/Back/composables/useMedia.js

import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import mediaService from "../services/mediaService";

export default function useMedia() {
    const page = usePage();

    const media = computed(() => page.props.media?.data ?? []);

    const pagination = computed(() => ({
        currentPage: page.props.media?.current_page ?? 1,
        lastPage: page.props.media?.last_page ?? 1,
        total: page.props.media?.total ?? 0,
        perPage: page.props.media?.per_page ?? 20,
    }));

    const goToPage = (pageNum) => mediaService.index(pageNum);

    const goToCreate = () => mediaService.create();

    const saveMedia = (form, options = {}) => mediaService.save(form, options);

    const deleteMedia = (id) => {
        if (!confirm("Delete this media?")) return;

        mediaService.destroy(id, {
            onError: (e) => console.error("Delete failed", e),
        });
    };

    return {
        media,
        pagination,
        goToPage,
        goToCreate,
        saveMedia,
        deleteMedia,
    };
}
