<!-- Components/Message.vue -->
<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Icons } from '..';

const page = usePage();

const showSuccess = ref(!!page.props.flash?.success);
const showError   = ref(!!page.props.flash?.error);

// Auto-hide after 4 seconds
watch(() => page.props.flash?.success, (val) => {
    if (val) {
        showSuccess.value = true;
        setTimeout(() => showSuccess.value = false, 4000);
    }
}, { immediate: true });

watch(() => page.props.flash?.error, (val) => {
    if (val) {
        showError.value = true;
        setTimeout(() => showError.value = false, 4000);
    }
}, { immediate: true });
</script>

<template>
    <div>
        <!-- Success -->
        <Transition name="fade">
            <div v-if="showSuccess"
                 class="flex items-center justify-between bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 text-sm rounded mb-4">
                <div class="flex items-center gap-2">
                    <Icons name="check" class="w-4 h-4 text-green-600" />
                    {{ page.props.flash.success }}
                </div>
                <button @click="showSuccess = false">
                    <Icons name="x" class="w-4 h-4" />
                </button>
            </div>
        </Transition>

        <!-- Error -->
        <Transition name="fade">
            <div v-if="showError"
                 class="flex items-center justify-between bg-red-100 border-l-4 border-red-500 text-red-800 px-4 py-3 text-sm rounded mb-4">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ page.props.flash.error }}
                </div>
                <button @click="showError = false"
                        class="text-red-600 hover:text-red-900 ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease, transform 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}
</style>
