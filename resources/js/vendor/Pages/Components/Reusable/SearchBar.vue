<!-- Components/SearchBar.vue -->
<script setup>
import { Icons } from '../index'

defineProps({
    placeholder: {
        type: String,
        default: 'Search products, orders...',
    },
})

const model = defineModel()     // ← v-model support
</script>

<template>
    <div class="flex items-center w-full rounded-lg px-3 gap-2"
        style="background:#1c1c1a;border:0.5px solid #2e2e2b;height:34px;">
        <Icons name="search" class="w-3.5 h-3.5 shrink-0" style="color:#6b6660;" />
        <input
            type="text"
            :placeholder="placeholder"
            class="bg-transparent text-xs outline-none w-full"
            style="color:white;"
            :value="model"
            @input="model = $event.target.value"
        />
        <button v-if="model" @click="model = ''"
            style="color:#6b6660;background:transparent;border:none;cursor:pointer;">
            <Icons name="x" class="w-3.5 h-3.5" />
        </button>
    </div>
</template>
