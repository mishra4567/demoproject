<!-- resources/js/vendor/Components/MediaSelector.vue -->
<script setup>
import { ref, computed, watch } from 'vue'
import { Icons } from '..'

const props = defineProps({
    modelValue:  { type: Number, default: null },
    previewFile: { type: String, default: null },
    label:       { type: String, default: null },
})

const emit = defineEmits(['update:modelValue', 'update:previewFile'])

const showModal   = ref(false)
const mediaList   = ref([])
const mediaSearch = ref('')
const loading     = ref(false)

// Load media from VENDOR endpoint
async function loadMedia(query = '') {
    loading.value = true
    try {
        const res       = await fetch(`/vendor/media/search?query=${encodeURIComponent(query)}`)
        mediaList.value = await res.json()
    } catch (e) {
        console.error('Failed to load media', e)
    } finally {
        loading.value = false
    }
}

// Debounce search
let searchTimer
watch(mediaSearch, (val) => {
    clearTimeout(searchTimer)
    searchTimer = setTimeout(() => loadMedia(val.trim()), 300)
})

function open() {
    mediaSearch.value = ''
    loadMedia('')          // fresh load on open
    showModal.value   = true
}

function selectMedia(item) {
    emit('update:modelValue',  item.id)
    emit('update:previewFile', item.file_name)
    emit('select', item)
    showModal.value   = false
    mediaSearch.value = ''
}

function clearMedia() {
    emit('update:modelValue',  null)
    emit('update:previewFile', null)
}
</script>

<template>
    <div>
        <!-- Preview -->
        <div v-if="modelValue && previewFile"
            class="relative w-20 h-20 mb-2 rounded-lg overflow-hidden"
            style="border:0.5px solid #2e2e2b;">
            <img :src="`/storage/media/${previewFile}`"
                class="w-full h-full object-cover" />
            <button type="button" @click="clearMedia"
                class="absolute top-1 right-1 w-5 h-5 rounded-full
                       flex items-center justify-center"
                style="background:#ef4444;">
                <Icons name="x" class="w-3 h-3" style="color:white;" />
            </button>
        </div>

        <!-- Trigger -->
        <button type="button" @click="open"
            class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs"
            style="background:#111110;color:#9e9890;border:0.5px solid #2e2e2b;"
            onmouseover="this.style.color='white'"
            onmouseout="this.style.color='#9e9890'">
            <Icons name="product" class="w-3.5 h-3.5" />
            {{ label ?? (modelValue ? 'Change Image' : 'Select Image') }}
        </button>

        <!-- Modal -->
        <Transition name="fade">
            <div v-if="showModal"
                class="fixed inset-0 z-[60] flex items-center justify-center px-4"
                style="background:rgba(0,0,0,0.85);"
                @click.self="showModal = false">

                <div class="rounded-xl w-full max-w-2xl"
                    style="background:#1c1c1a;border:0.5px solid #2e2e2b;
                           max-height:80vh;display:flex;flex-direction:column;">

                    <!-- Header -->
                    <div class="flex items-center justify-between px-5 py-4 shrink-0"
                        style="border-bottom:0.5px solid #2e2e2b;">
                        <h2 class="text-sm font-medium" style="color:white;">
                            Select Image
                        </h2>
                        <button @click="showModal = false" style="color:#6b6660;">
                            <Icons name="x" class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="px-5 py-3 shrink-0"
                        style="border-bottom:0.5px solid #2e2e2b;">
                        <div class="flex items-center gap-2 rounded-lg px-3"
                            style="background:#111110;border:0.5px solid #2e2e2b;height:34px;">
                            <Icons name="search" class="w-3.5 h-3.5 shrink-0"
                                style="color:#6b6660;" />
                            <input type="text" v-model="mediaSearch"
                                placeholder="Search by tag or filename..."
                                class="bg-transparent text-xs outline-none w-full"
                                style="color:white;" />
                            <button v-if="mediaSearch"
                                @click="mediaSearch = ''; loadMedia('')"
                                style="color:#6b6660;background:transparent;border:none;cursor:pointer;">
                                <Icons name="x" class="w-3.5 h-3.5" />
                            </button>
                        </div>
                    </div>

                    <!-- Grid -->
                    <div class="overflow-y-auto flex-1 p-4">

                        <!-- Loading -->
                        <div v-if="loading"
                            class="text-center py-10 text-sm"
                            style="color:#6b6660;">
                            Loading...
                        </div>

                        <!-- Media grid -->
                        <div v-else-if="mediaList.length"
                            class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            <div v-for="item in mediaList" :key="item.id"
                                @click="selectMedia(item)"
                                class="relative rounded-lg overflow-hidden cursor-pointer"
                                style="border:0.5px solid #2e2e2b;aspect-ratio:1;"
                                :style="modelValue === item.id
                                    ? 'border-color:#d97706;'
                                    : ''">
                                <img :src="`/storage/media/${item.file_name}`"
                                    class="w-full h-full object-cover"
                                    :style="modelValue === item.id
                                        ? 'opacity:0.8;'
                                        : ''" />

                                <!-- Selected tick -->
                                <div v-if="modelValue === item.id"
                                    class="absolute inset-0 flex items-center justify-center"
                                    style="background:rgba(217,119,6,0.25);">
                                    <Icons name="check" class="w-6 h-6"
                                        style="color:#d97706;" />
                                </div>

                                <!-- Tag -->
                                <div v-if="item.tags"
                                    class="absolute bottom-0 left-0 right-0 px-2 py-1
                                           text-xs truncate"
                                    style="background:rgba(0,0,0,0.65);color:#9e9890;">
                                    {{ item.tags }}
                                </div>
                            </div>
                        </div>

                        <!-- Empty -->
                        <div v-else class="text-center py-10 text-sm"
                            style="color:#6b6660;">
                            No media found.
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-5 py-3 flex justify-end shrink-0"
                        style="border-top:0.5px solid #2e2e2b;">
                        <button @click="showModal = false"
                            class="px-4 py-2 rounded-lg text-xs"
                            style="color:#9e9890;border:0.5px solid #2e2e2b;
                                   background:transparent;"
                            onmouseover="this.style.color='white'"
                            onmouseout="this.style.color='#9e9890'">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; }
</style>
