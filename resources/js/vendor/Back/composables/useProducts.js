// resources/js/vendor/Back/composables/useProducts.js
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import productService from "../services/productService";

export default function useProducts() {
    const page = usePage();

    // ── Reactive data from Inertia props ─────────────────────────
    const products = computed(
        () => page.props.data ?? page.props.products?.data ?? [],
    );
    const deletedData = computed(() => page.props.deleteProducts ?? []);
    console.log("Products are:-", deletedData.value);

    const pagination = computed(() => ({
        currentPage: page.props.products?.current_page ?? 1,
        lastPage: page.props.products?.last_page ?? 1,
        total: page.props.products?.total ?? 0,
        perPage: page.props.products?.per_page ?? 12,
    }));

    // ── UI state ────────────────────────────────────────────────
    const showDeleted = ref(false);
    const selected = ref([]);
    const bulkAction = ref("");

    // ── Manage Product Page Data ──────────────────────────────────
    const product = computed(() => page.props.product ?? null);
    const categories = computed(() => page.props.categories ?? []);
    const brands = computed(() => page.props.brands ?? []);
    const coupons = computed(() => page.props.coupons ?? []);
    const media = computed(() => page.props.media ?? []);
    const isEdit = computed(() => page.props.isEdit ?? false);

    // ── Display Product Data ────────────────────────────────────────
    const displayed = computed(() =>
        showDeleted.value ? deletedData.value : products.value,
    );
    // ── Manage Product Save ────────────────────────────────────────
    const saveProduct = (form, options = {}) =>
        productService.save(form, options);

    // ── List actions ────────────────────────────────────────────────
    const goToPage = (pageNum) => productService.index(pageNum);
    const goToCreate = () => productService.manage();
    const editProduct = (id) => productService.manage(id);

    const deleteProduct = (id) => {
        if (!confirm("Move this product to trash?")) return;
        productService.destroy(id, {
            onSuccess: () => {},
            onError: (e) => console.error("Delete failed", e),
        });
    };

    const restoreProduct = (id) => {
        productService.restore(id, {
            onSuccess: () => {},
            onError: (e) => console.error("Restore failed", e),
        });
    };

    const forceDeleteProduct = (id) => {
        if (!confirm("Permanently delete? This cannot be undone.")) return;
        productService.permanentDelete(id, {
            onSuccess: () => {},
            onError: (e) => console.error("Permanent delete failed", e),
        });
    };

    const toggleStatus = (id) => {
        productService.status(id, {
            onSuccess: () => {},
            onError: (e) => console.error("Status update failed", e),
        });
    };

    // ── Bulk actions ────────────────────────────────────────────────
    const toggleAll = (checked) => {
        const list = showDeleted.value ? deletedData.value : products.value;
        selected.value = checked ? list.map((p) => p.id) : [];
    };

    const applyBulk = () => {
        if (!selected.value.length || !bulkAction.value) return;
        productService.bulkAction(
            { ids: selected.value, action: bulkAction.value },
            {
                onSuccess: () => {
                    selected.value = [];
                    bulkAction.value = "";
                },
                onError: (e) => console.error("Bulk action failed", e),
            },
        );
    };

    return {
        // list data
        products,
        displayed,
        deletedData,
        pagination,
        showDeleted,
        selected,
        bulkAction,
        // manage data
        product,
        categories,
        brands,
        coupons,
        media,
        isEdit,
        // actions
        saveProduct,
        goToPage,
        deleteProduct,
        restoreProduct,
        forceDeleteProduct,
        toggleStatus,
        editProduct,
        goToCreate,
        toggleAll,
        applyBulk,
    };
}
