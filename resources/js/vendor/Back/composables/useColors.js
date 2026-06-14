// resources/js/vendor/Back/composables/useColors.js
import { computed, ref } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import colorService from "../services/colorService";

export default function useColors() {
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
        color_name: "",
        hex_id: "#000000",
    });

    const openCreate = () => {
        editTarget.value = null;
        form.reset();
        form.hex_id = "#000000";
        showForm.value = true;
    };

    const openEdit = (color) => {
        editTarget.value = color;
        form.id = color.id;
        form.color_name = color.color_name;
        form.hex_id = color.hex_id;
        showForm.value = true;
    };

    const closeForm = () => {
        showForm.value = false;
        editTarget.value = null;
        form.reset();
    };

    const saveColor = (options = {}) =>
        colorService.save(form, {
            onSuccess: () => closeForm(),
            ...options,
        });

    const toggleStatus = (id) => colorService.status(id);

    const deleteColor = (id) => {
        if (!confirm("Move this color to trash?")) return;
        colorService.destroy(id);
    };

    const restoreColor = (id) => colorService.restore(id);

    const forceDeleteColor = (id) => {
        if (!confirm("Permanently delete? Cannot be undone.")) return;
        colorService.forceDelete(id);
    };

    const toggleAll = (checked) => {
        selected.value = checked ? displayed.value.map((c) => c.id) : [];
    };

    const applyBulk = (options = {}) => {
        if (!bulkAction.value || !selected.value.length) return;
        colorService.bulk(bulkAction.value, selected.value, {
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
        saveColor,
        toggleStatus,
        deleteColor,
        restoreColor,
        forceDeleteColor,
        toggleAll,
        applyBulk,
    };
}
