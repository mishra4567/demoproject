<!-- Components/Nav/SidebarGroup.vue -->
<script setup>
import { ref }   from 'vue'
import { Link }  from '@inertiajs/vue3'
import { Icons } from '@/vendor/Components/index'

const props = defineProps({
    link:     { type: Object,   required: true },
    isActive: { type: Function, required: true },
})

const emit = defineEmits(['close', 'open-external'])

const open = ref(
    props.link.children?.some(c => props.isActive(c.href)) ?? false
)

// Sort children by order
function sortedChildren(children) {
    return [...children].sort((a, b) => (a.order ?? 99) - (b.order ?? 99))
}
</script>

<template>
    <!-- Group button -->
    <button @click="open = !open"
        class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-left"
        :style="link.children?.some(c => isActive(c.href))
            ? 'color:white;' : 'color:#9e9890;'"
        onmouseover="this.style.color='white'"
        onmouseout="this.style.color='#9e9890'">
        <div class="flex items-center gap-3">
            <Icons :name="link.icon" class="w-4 h-4" />
            {{ link.label }}
        </div>
        <svg class="w-3 h-3 transition-transform"
            :style="open ? 'transform:rotate(180deg)' : ''"
            fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Children -->
    <Transition name="slide">
        <div v-if="open"
            style="background:#0d0d0c;border-left:0.5px solid #2e2e2b;margin-left:16px;">
            <template v-for="child in sortedChildren(link.children)" :key="child.href">

                <!-- External child -->
                <button v-if="child.target === '_blank'"
                    @click="emit('open-external', child)"
                    class="w-full flex items-center gap-3 pl-4 pr-4 py-2 text-xs text-left"
                    style="color:#9e9890;background:transparent;border:none;cursor:pointer;"
                    onmouseover="this.style.color='white'"
                    onmouseout="this.style.color='#9e9890'">
                    <Icons :name="child.icon" class="w-3.5 h-3.5" />
                    {{ child.label }}
                    <svg class="w-3 h-3 ml-auto" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" style="color:#6b6660;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002
                               2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </button>

                <!-- Normal child -->
                <Link v-else
                    :href="child.href"
                    @click="emit('close')"
                    class="flex items-center gap-3 pl-4 pr-4 py-2 text-xs"
                    :style="isActive(child.href)
                        ? 'color:white;background:#1c1c1a;'
                        : 'color:#9e9890;'"
                    onmouseover="if(!this.style.background.includes('1c1c1a')){this.style.color='white'}"
                    onmouseout="if(!this.style.background.includes('1c1c1a')){this.style.color='#9e9890'}"
                >
                    <Icons :name="child.icon" class="w-3.5 h-3.5" />
                    {{ child.label }}
                </Link>

            </template>
        </div>
    </Transition>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all 0.2s ease; overflow: hidden; }
.slide-enter-from,   .slide-leave-to     { max-height: 0; opacity: 0; }
.slide-enter-to,     .slide-leave-from   { max-height: 300px; opacity: 1; }
</style>
