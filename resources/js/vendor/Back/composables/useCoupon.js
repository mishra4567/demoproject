// resources/js/vendor/Back/composables/useCoupons.js
import { computed, ref } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import couponService from "../services/couponService";

export default function useCoupons() {
    const page = usePage();

    const data = computed(() => page.props.data ?? []);
    const deletedData = computed(() => page.props.deletedData ?? []);

    // UI state
    const showDeleted = ref(false);
    const showForm = ref(false);
    const editTarget = ref(null);
    const selected = ref([]);
    const bulkAction = ref("");

    const displayed = computed(() =>
        showDeleted.value ? deletedData.value : data.value,
    );
    // console.log("Page Props:", page.props);
    // console.log("Data:", page.props.data);
    // Form
    const form = useForm({
        id: null,
        title: "",
        code: "",
        value: "",
        type: "flat",
        min_order_amt: "",
        is_one_time: false,
        expiry: "",
    });

    const openCreate = () => {
        editTarget.value = null;
        form.reset();
        showForm.value = true;
    };

    const openEdit = (coupon) => {
        editTarget.value = coupon;
        form.id = coupon.id;
        form.title = coupon.title;
        form.code = coupon.code;
        form.value = coupon.value;
        form.type = coupon.type;
        form.min_order_amt = coupon.min_order_amt;
        form.is_one_time = coupon.is_one_time == 1;
        form.expiry = coupon.expiry
            ? coupon.expiry.replace(" ", "T").substring(0, 16)
            : "";
        showForm.value = true;
    };

    const closeForm = () => {
        showForm.value = false;
        editTarget.value = null;
        form.reset();
    };

    const saveCoupon = (options = {}) =>
        couponService.save(form, {
            onSuccess: () => closeForm(),
            ...options,
        });

    const toggleStatus = (id) => couponService.status(id);

    const deleteCoupon = (id) => {
        if (!confirm("Move this coupon to trash?")) return;
        couponService.destroy(id);
    };

    const restoreCoupon = (id) => couponService.restore(id);

    const forceDeleteCoupon = (id) => {
        if (!confirm("Permanently delete this coupon?")) return;
        couponService.forceDelete(id);
    };

    const toggleAll = (checked) => {
        selected.value = checked ? displayed.value.map((c) => c.id) : [];
    };

    const applyBulk = (options = {}) => {
        if (!bulkAction.value || !selected.value.length) return;
        couponService.bulk(bulkAction.value, selected.value, {
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
        saveCoupon,
        toggleStatus,
        deleteCoupon,
        restoreCoupon,
        forceDeleteCoupon,
        toggleAll,
        applyBulk,
    };
}
