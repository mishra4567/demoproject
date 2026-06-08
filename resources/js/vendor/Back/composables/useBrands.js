// resources/js/vendor/Back/composables/useBrands.js
import { computed, ref } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import brandService from "../services/brandService";

export default function useBrands() {
    const page = usePage();

    const data = computed(() => page.props.data ?? []);
    const deletedData = computed(() => page.props.deletedData ?? []);

    const showDeleted = ref(false);
    const showForm = ref(false);
    const editTarget = ref(null);
    const selected = ref([]);
    const bulkAction = ref("");

    const displayed = computed(() =>
        showDeleted.value ? deletedData.value : data.value,
    );

    const form = useForm({
        id: null,
        name: "",
        media_ids: null,
        previewFile: null, // ← add, used for preview only
    });

    const openCreate = () => {
        editTarget.value = null;
        form.reset();
        showForm.value = true;
    };

    const openEdit = (brand) => {
        editTarget.value = brand;
        form.id = brand.id;
        form.name = brand.name;
        form.media_ids = brand.media_ids ?? null;
        form.previewFile = brand.file_name ?? null; // ← from DB join
        showForm.value = true;
    };

    const closeForm = () => {
        showForm.value = false;
        editTarget.value = null;
        form.reset();
    };

    const saveBrand = (options = {}) =>
        brandService.save(form, {
            onSuccess: () => closeForm(),
            ...options,
        });

    const toggleStatus = (id) => brandService.status(id);

    const deleteBrand = (id) => {
        if (!confirm("Move this brand to trash?")) return;
        brandService.destroy(id);
    };

    const restoreBrand = (id) => brandService.restore(id);

    const forceDeleteBrand = (id) => {
        if (!confirm("Permanently delete? Cannot be undone.")) return;
        brandService.forceDelete(id);
    };

    const toggleAll = (checked) => {
        selected.value = checked ? displayed.value.map((b) => b.id) : [];
    };

    const applyBulk = (options = {}) => {
        if (!bulkAction.value || !selected.value.length) return;
        brandService.bulk(bulkAction.value, selected.value, {
            onSuccess: () => {
                selected.value = [];
                bulkAction.value = "";
            },
            ...options,
        });
    };

    return {
        data,
        deletedData,
        displayed,
        showDeleted,
        showForm,
        editTarget,
        selected,
        bulkAction,
        form,
        openCreate,
        openEdit,
        closeForm,
        saveBrand,
        toggleStatus,
        deleteBrand,
        restoreBrand,
        forceDeleteBrand,
        toggleAll,
        applyBulk,
    };
}
