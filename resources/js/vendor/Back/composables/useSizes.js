import { computed, ref } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import sizeService from "../services/sizeService";

export default function useSizes() {
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
        size: "",
        type: "",
        custom_type: "",
        details: "",
    });

    const openCreate = () => {
        editTarget.value = null;
        form.reset();
        showForm.value = true;
    };

    const openEdit = (item) => {
        editTarget.value = item;
        form.id = item.id;
        form.size = item.size;
        form.type = size.type;
        form.custom_type = ""; 
        form.details = size.details;
        showForm.value = true;
    };

    const closeForm = () => {
        editTarget.value = null;
        showForm.value = false;
        form.reset();
    };

    const saveSize = (options = {}) =>
        sizeService.save(form, {
            onSuccess: () => closeForm(),
            ...options,
        });

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
        saveSize,

        toggleStatus: sizeService.status,
        deleteSize: sizeService.destroy,
        restoreSize: sizeService.restore,
        forceDeleteSize: sizeService.forceDelete,

        toggleAll: (checked) => {
            selected.value = checked ? displayed.value.map((i) => i.id) : [];
        },

        applyBulk: () => {
            if (!bulkAction.value || !selected.value.length) return;

            sizeService.bulk(bulkAction.value, selected.value);
        },
    };
}
