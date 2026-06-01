// Vendor/Layouts/VendorLayout.vue
<script setup>
// import Navbar from '../Components/Navbar.vue';
import { Message, Navbar } from '../Components';
</script>

<template>
    <div>
        <Navbar />

        <main class="p-6">
            <Message/>
            <slot />
        </main>
    </div>
</template>
