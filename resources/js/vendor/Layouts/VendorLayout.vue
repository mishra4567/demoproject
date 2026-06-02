// Vendor/Layouts/VendorLayout.vue
<script setup>
// import Navbar from '../Components/Navbar.vue';
import { DesktopSidebar, Message, Navbar } from '../Components';
</script>

<template>
    <div>
        <Navbar />
            <!-- <aside class="hidden md:flex flex-col shrink-0"
                style="width:220px; min-height:calc(100vh - 96px);
                       background:#111110; border-right:0.5px solid #2e2e2b;">
                <DesktopSidebar />
            </aside> -->
        <main class="md:ml-[220px] p-6">
            <Message/>
            <slot />
        </main>
    </div>
</template>
