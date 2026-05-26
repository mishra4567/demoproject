<script setup>
import Navbar from '../Components/Navbar.vue';
</script>

<template>
    <div>
        <Navbar />

        <main class="p-6">
            <slot />
        </main>
    </div>
</template>
