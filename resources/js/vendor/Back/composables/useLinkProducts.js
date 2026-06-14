// resources/js/vendor/Back/composables/useLinkProducts.js
import { computed, ref } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import linkProductService from "../services/linkProductService";

export default function useLinkProducts() {
    const page = usePage();

    const data = computed(() => page.props.data ?? []);
    const deletedData = computed(() => page.props.deletedData ?? []);
    const products = computed(() => page.props.products ?? []);
    const sizes = computed(() => page.props.sizes ?? []);
    const colors = computed(() => page.props.colors ?? []);

    const showDeleted = ref(false);
    const showForm = ref(false);
    const editTarget = ref(null);
    const selected = ref([]);
    const bulkAction = ref("");

    const displayed = computed(() =>
        showDeleted.value ? deletedData.value : data.value,
    );
    // Single Edit Form
    const form = useForm({
        id: null,
        product_id: "",
        sku: "",
        mrp: "",
        price: "",
        qty: "",
        size_id: "",
        color_id: "",
        media_id: null,
        previewFile: null,
    });
    // Single Edit Form
    const makeRow = () => ({
        product_id: "",
        sku: "",
        mrp: "",
        price: "",
        qty: "",
        size_id: "",
        color_id: "",
        media_id: null,
        previewFile: null,
    });

    const rows = ref([makeRow()]);

    const addRow = () => {
        rows.value.push(makeRow());
    };
    const removeRow = (index) => {
        if (rows.value.length > 1) {
            rows.value.splice(index, 1);
        }
    };

    const openCreate = () => {
        editTarget.value = null;
        rows.value = [makeRow()];
        form.reset();
        showForm.value = true;
    };

    const openEdit = (item) => {
        editTarget.value = item;
        form.id = item.id;
        form.product_id = item.product_id;
        form.sku = item.sku;
        form.mrp = item.mrp;
        form.price = item.price;
        form.qty = item.qty;
        form.size_id = item.size_id;
        form.color_id = item.color_id;
        form.media_id = item.media_id ?? null;
        form.previewFile = item.file_name ?? null;
        showForm.value = true;
    };

    const closeForm = () => {
        showForm.value = false;
        editTarget.value = null;
        form.reset();
        rows.value = [makeRow()];
    };

    const saveLinkProduct = (options = {}) => {
        // EDIT
        if (editTarget.value) {
            linkProductService.save(form, {
                onSuccess: () => closeForm(),
                ...options,
            });
            return;
        }
        // MULTIPLE CREATE
        router.post(
            "/vendor/link-products/bulk-save",
            {
                rows: rows.value,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    closeForm();
                },
                onError: (errors) => {
                    console.log(errors);
                },
                ...options,
            },
        );
    };

    const toggleStatus = (id) => linkProductService.status(id);

    const deleteLinkProduct = (id) => {
        if (!confirm("Move this item to trash?")) return;
        linkProductService.destroy(id);
    };

    const restoreLinkProduct = (id) => linkProductService.restore(id);

    const forceDeleteLinkProduct = (id) => {
        if (!confirm("Permanently delete? Cannot be undone.")) return;
        linkProductService.forceDelete(id);
    };

    const toggleAll = (checked) => {
        selected.value = checked ? displayed.value.map((i) => i.id) : [];
    };

    const applyBulk = (options = {}) => {
        if (!bulkAction.value || !selected.value.length) return;
        linkProductService.bulk(bulkAction.value, selected.value, {
            onSuccess: () => {
                selected.value = [];
                bulkAction.value = "";
            },
            ...options,
        });
    };

    const colorHex = (id) =>
        colors.value.find((c) => c.id == id)?.hex_id ?? "#888";
    return {
        data,
        deletedData,
        displayed,

        products,
        sizes,
        colors,

        showDeleted,
        showForm,
        editTarget,

        selected,
        bulkAction,

        form,

        rows,
        addRow,
        removeRow,

        openCreate,
        openEdit,
        closeForm,

        saveLinkProduct,

        toggleStatus,

        deleteLinkProduct,
        restoreLinkProduct,
        forceDeleteLinkProduct,

        toggleAll,
        applyBulk,

        colorHex,
    };
}
