// resources/js/vendor/Back/composables/useTechnicalSpecs.js
import { computed, ref } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import technicalSpecsService from "../services/technicalSpecsService";

export default function useTechnicalSpecs() {
    const page = usePage();

    const data = computed(() => page.props.data ?? []);
    const deletedData = computed(() => page.props.deletedData ?? []);
    const products = computed(() => page.props.products ?? []);

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
        product_id: "",
        title: "",
        lead_time_from: "",
        lead_time_to: "",
        tax: 0,
        tax_type: "none",
        is_promo: false,
        is_featured: false,
        is_discounted: false,
        is_trending: false,
    });

    const openCreate = () => {
        editTarget.value = null;
        form.reset();
        showForm.value = true;
    };

    const openEdit = (spec) => {
        editTarget.value = spec;
        form.id = spec.id;
        form.product_id = spec.product_id;
        form.title = spec.title;
        form.lead_time_from = spec.lead_time_from ?? "";
        form.lead_time_to = spec.lead_time_to ?? "";
        form.tax = spec.tax ?? 0;
        form.tax_type = spec.tax_type ?? "none";
        form.is_promo = !!spec.is_promo;
        form.is_featured = !!spec.is_featured;
        form.is_discounted = !!spec.is_discounted;
        form.is_trending = !!spec.is_trending;
        showForm.value = true;
    };

    const closeForm = () => {
        showForm.value = false;
        editTarget.value = null;
        form.reset();
    };

    const saveTechnicalSpec = (options = {}) =>
        technicalSpecsService.save(form, {
            onSuccess: () => closeForm(),
            ...options,
        });

    const toggleStatus = (id) => technicalSpecsService.status(id);

    const deleteTechnicalSpec = (id) => {
        if (!confirm("Move this spec to trash?")) return;
        technicalSpecsService.destroy(id);
    };

    const restoreTechnicalSpec = (id) => technicalSpecsService.restore(id);

    const forceDeleteTechnicalSpec = (id) => {
        if (!confirm("Permanently delete? Cannot be undone.")) return;
        technicalSpecsService.forceDelete(id);
    };

    const toggleAll = (checked) => {
        selected.value = checked ? displayed.value.map((s) => s.id) : [];
    };

    const applyBulk = (options = {}) => {
        if (!bulkAction.value || !selected.value.length) return;
        technicalSpecsService.bulk(bulkAction.value, selected.value, {
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
        products,
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
        saveTechnicalSpec,
        toggleStatus,
        deleteTechnicalSpec,
        restoreTechnicalSpec,
        forceDeleteTechnicalSpec,
        toggleAll,
        applyBulk,
    };
}
