// Vendor/Components/Navbar.vue
<script setup>
import { router} from '@inertiajs/vue3';
function logout(){
    router.post('/vendor/logout');
}
</script>
<template>
     <nav class="bg-black text-white p-4 flex justify-between">
        <h1 class="text-xl font-bold">Vendor Panel</h1>
        <div class="space-x-4">
            <a href="/vendor">Dashboard</a>
            <a href="/vendor/products">Products</a>
            <button @click="logout" class="cursor-pointer">Logout</button>
        </div>
    </nav>
</template>
