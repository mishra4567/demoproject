// resources/js/vendor/Back/composables/useCategories.js
import { computed, ref } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import categoryService from "../services/categoryService";

export default function useCategories() {
    const page = usePage();

    const data = computed(() => page.props.data ?? []);
    const deletedData = computed(() => page.props.deletedData ?? []);
    const parents = computed(() => page.props.parents ?? []);

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
        category_name: "",
        category_slug: "",
        parent_id: 0,
    });

    const generateSlug = () => {
        form.category_slug = form.category_name
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, "")
            .replace(/\s+/g, "-");
    };

    const openCreate = () => {
        editTarget.value = null;
        form.reset();
        showForm.value = true;
    };

    const openEdit = (category) => {
        editTarget.value = category;
        form.id = category.id;
        form.category_name = category.category_name;
        form.category_slug = category.category_slug;
        form.parent_id = category.parent_id ?? 0;
        showForm.value = true;
    };

    const closeForm = () => {
        showForm.value = false;
        editTarget.value = null;
        form.reset();
    };

    const saveCategory = (options = {}) =>
        categoryService.save(form, {
            onSuccess: () => closeForm(),
            ...options,
        });

    const toggleStatus = (id) => categoryService.status(id);

    const deleteCategory = (id) => {
        if (!confirm("Move this category to trash?")) return;
        categoryService.destroy(id);
    };

    const restoreCategory = (id) => categoryService.restore(id);

    const forceDeleteCategory = (id) => {
        if (!confirm("Permanently delete? Cannot be undone.")) return;
        categoryService.forceDelete(id);
    };

    const toggleAll = (checked) => {
        selected.value = checked ? displayed.value.map((c) => c.id) : [];
    };

    const applyBulk = (options = {}) => {
        if (!bulkAction.value || !selected.value.length) return;
        categoryService.bulk(bulkAction.value, selected.value, {
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
        parents,
        displayed,
        showDeleted,
        showForm,
        editTarget,
        selected,
        bulkAction,
        form,
        generateSlug,
        openCreate,
        openEdit,
        closeForm,
        saveCategory,
        toggleStatus,
        deleteCategory,
        restoreCategory,
        forceDeleteCategory,
        toggleAll,
        applyBulk,
    };
}
