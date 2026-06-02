// Vendor/Pages/Dashboard.vue
<script setup>
import VendorLayout from './Layouts/VendorLayout.vue';

defineOptions({
    layout: VendorLayout,
});
</script>

<template>
    <div>
        <h1 class="text-3xl font-bold mb-4">Vendor Dashboard</h1>

        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white shadow p-4 rounded">
                Total Products
            </div>

            <div class="bg-white shadow p-4 rounded">
                Orders
            </div>

            <div class="bg-white shadow p-4 rounded">
                Revenue
            </div>
        </div>
    </div>
</template>
