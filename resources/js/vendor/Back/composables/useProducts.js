// resources/js/vendor/Back/composables/useProducts.js
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import productService from "../services/productService";

export default function useProducts() {
    const page = usePage();

    // ── Reactive data from Inertia props ─────────────────────────
    const products = computed(() => page.props.products?.data ?? []);
    const pagination = computed(() => ({
        currentPage: page.props.products?.current_page ?? 1,
        lastPage: page.props.products?.last_page ?? 1,
        total: page.props.products?.total ?? 0,
        perPage: page.props.products?.per_page ?? 12,
    }));

    // ── Actions ──────────────────────────────────────────────────

    const deleteProduct = (id) => {
        if (!confirm("Delete this product?")) return;
        productService.destroy(id, {
            onSuccess: () => {},
            onError: (e) => console.error("Delete failed", e),
        });
    };

    const goToPage = (pageNum) => productService.index(pageNum);
    const goToCreate = () => productService.manage();
    const editProduct = (id) => productService.manage(id);

    return {
        products,
        pagination,
        goToPage,
        deleteProduct,
        editProduct,
        goToCreate,
    };
}
