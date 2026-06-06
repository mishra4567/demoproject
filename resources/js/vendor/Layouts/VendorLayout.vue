<!-- resources/js/vendor/Layouts/VendorLayout.vue -->
<script setup>
import { Link, usePage }               from '@inertiajs/vue3'
import { Navbar, Message, DesktopSidebar, Icons } from '@/vendor/Components'
import { navLinks } from '../Back'
import { useActiveLink } from '../assets';

const {isActive}= useActiveLink()

</script>

<template>
    <div class="min-h-screen" style="background:#0d0d0c;">
        <Navbar />
        <div class="flex">
            <DesktopSidebar />
            <!-- Right side -->
            <div class="flex-1 min-w-0">
                <!-- Secondary navbar — right of sidebar -->
                <div style="background:#111110; border-bottom:0.5px solid #2e2e2b;">
                    <div class="px-4">
                        <div class="flex items-center gap-1 h-10">
                            <Link
                                v-for="link in navLinks" :key="link.href"
                                :href="link.href"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                    text-xs font-medium transition-colors"
                                :style="isActive(link.href)
                                    ? 'background:#1c1c1a;color:white;'
                                    : 'color:#9e9890;'"
                            >
                                <Icons :name="link.icon" class="w-3.5 h-3.5" />
                                {{ link.label }}
                            </Link>
                        </div>
                    </div>
                </div>
                <!-- Main content -->
                <main class="p-6">
                    <Message />
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
