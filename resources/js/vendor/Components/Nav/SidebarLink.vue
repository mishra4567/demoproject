<!-- Components/Nav/SidebarLink.vue -->
<script setup>
import { Icons } from '@/vendor/Components/index'
import { Link }  from '@inertiajs/vue3'

defineProps({
    link:     { type: Object, required: true },
    isActive: { type: Function, required: true },
})

const emit = defineEmits(['close', 'open-external'])
</script>

<template>
    <!-- External -->
    <button v-if="link.target === '_blank'"
        @click="emit('open-external', link)"
        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left"
        style="color:#9e9890;background:transparent;border:none;cursor:pointer;"
        onmouseover="this.style.color='white'"
        onmouseout="this.style.color='#9e9890'">
        <Icons :name="link.icon" class="w-4 h-4" />
        {{ link.label }}
        <svg class="w-3 h-3 ml-auto" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2" style="color:#6b6660;">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002
                   2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
        </svg>
    </button>

    <!-- Normal -->
    <Link v-else
        :href="link.href"
        @click="emit('close')"
        class="flex items-center gap-3 px-4 py-2.5 text-sm"
        :style="isActive(link.href)
            ? 'background:#1c1c1a;color:white;'
            : 'color:#9e9890;'"
        onmouseover="if(!this.style.background.includes('1c1c1a')){this.style.color='white'}"
        onmouseout="if(!this.style.background.includes('1c1c1a')){this.style.color='#9e9890'}"
    >
        <Icons :name="link.icon" class="w-4 h-4" />
        {{ link.label }}
    </Link>
</template>
