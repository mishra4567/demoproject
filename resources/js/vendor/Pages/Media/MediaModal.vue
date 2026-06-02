<!-- resources/js/Components/Vendor/Media/MediaModal.vue -->
<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  show:  { type: Boolean, default: false },
  media: { type: Array,   default: () => [] },
  mode:  { type: String,  default: 'single' }, // 'single' | 'gallery'
})

const emit = defineEmits(['close', 'select'])

const search       = ref('')
const selected     = ref(null)  // single mode
const selectedIds  = ref([])    // gallery mode (not used here, parent handles it)

// Filter media by search
const filtered = computed(() =>
  props.media.filter(item =>
    !search.value ||
    (item.file_name ?? '').toLowerCase().includes(search.value.toLowerCase()) ||
    (item.tags      ?? '').toLowerCase().includes(search.value.toLowerCase())
  )
)

// Reset on open
watch(() => props.show, (val) => {
  if (val) {
    search.value   = ''
    selected.value = null
  }
})

const select = (item) => {
  selected.value = item
}

const confirm = () => {
  if (!selected.value) return alert('Please select an image')
  emit('select', selected.value)
  emit('close')
}
</script>

<template>
  <Teleport to="body">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">

      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/50" @click="$emit('close')" />

      <!-- Modal -->
      <div class="relative bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[85vh] flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b shrink-0">
          <h3 class="font-semibold text-lg">Media Library</h3>
          <button @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600 text-2xl leading-none w-8 h-8 flex items-center justify-center">
            ✕
          </button>
        </div>

        <!-- Search -->
        <div class="px-6 py-3 border-b shrink-0">
          <input
            v-model="search"
            type="text"
            placeholder="Search by filename or tag..."
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
          />
        </div>

        <!-- Grid -->
        <div class="overflow-y-auto flex-1 p-6">
          <div v-if="filtered.length"
            class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
            <div
              v-for="item in filtered"
              :key="item.id"
              @click="select(item)"
              :class="[
                'cursor-pointer rounded-lg overflow-hidden border-2 transition aspect-square relative',
                selected?.id === item.id
                  ? 'border-black ring-2 ring-black'
                  : 'border-transparent hover:border-gray-300',
              ]"
            >
              <img
                :src="`/storage/media/${item.file_name}`"
                :alt="item.tags ?? item.file_name"
                class="w-full h-full object-cover"
              />
              <!-- Selected checkmark -->
              <div v-if="selected?.id === item.id"
                class="absolute top-1 right-1 w-5 h-5 bg-black text-white rounded-full
                  flex items-center justify-center text-xs font-bold">
                ✓
              </div>
            </div>
          </div>

          <div v-else class="text-center py-16 text-gray-400">
            No media found.
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t shrink-0 flex items-center justify-between">
          <p class="text-sm text-gray-500">
            {{ selected ? `Selected: ${selected.file_name}` : 'No image selected' }}
          </p>
          <div class="flex gap-2">
            <button @click="$emit('close')"
              class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-50 transition">
              Cancel
            </button>
            <button @click="confirm"
              :disabled="!selected"
              class="px-4 py-2 bg-black text-white rounded-lg text-sm
                hover:bg-gray-800 transition disabled:opacity-40 disabled:cursor-not-allowed">
              Select Image
            </button>
          </div>
        </div>

      </div>
    </div>
  </Teleport>
</template>
