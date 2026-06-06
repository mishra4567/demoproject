<!-- resources/js/vendor/Components/Reusable/Message.vue -->
<script setup>
import { ref, watch }  from 'vue'
import { usePage }     from '@inertiajs/vue3'
import { Icons }       from '..'

const page = usePage()

const showSuccess = ref(!!page.props.flash?.success)
const showError   = ref(!!page.props.flash?.error)

watch(() => page.props.flash?.success, (val) => {
    if (val) {
        showSuccess.value = true
        setTimeout(() => showSuccess.value = false, 4000)
    }
}, { immediate: true })

watch(() => page.props.flash?.error, (val) => {
    if (val) {
        showError.value = true
        setTimeout(() => showError.value = false, 4000)
    }
}, { immediate: true })
</script>

<template>
    <div>
        <!-- Success -->
        <Transition name="fade">
            <div v-if="showSuccess"
                class="flex items-center justify-between
                  bg-green-100 border-l-4 border-green-500 text-green-800
                  px-4 py-3 text-sm rounded mb-4">
                <div class="flex items-center gap-2">
                    <Icons name="check" class="w-4 h-4 text-green-600" />
                    {{ page.props.flash.success }}
                </div>
                <button @click="showSuccess = false" class="ml-4 text-green-600 hover:text-green-900">
                    <Icons name="x" class="w-4 h-4" />
                </button>
            </div>
        </Transition>

        <!-- Error -->
        <Transition name="fade">
            <div v-if="showError"
                class="flex items-center justify-between
                  bg-red-100 border-l-4 border-red-500 text-red-800
                  px-4 py-3 text-sm rounded mb-4">
                <div class="flex items-center gap-2">
                    <Icons name="x" class="w-4 h-4 text-red-600" />
                    {{ page.props.flash.error }}
                </div>
                <button @click="showError = false" class="ml-4 text-red-600 hover:text-red-900">
                    <Icons name="x" class="w-4 h-4" />
                </button>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease, transform 0.4s ease; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; transform: translateY(-6px); }
</style>
