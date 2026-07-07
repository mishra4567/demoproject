<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Icons, SidebarGroup, SidebarLink }         from '@/vendor/Components/index'
import { sidebarLinks, useAuth } from '@/vendor/Back'
import { useActiveLink, useSidebar } from '@/vendor/assets'
import { computed } from 'vue'

const { vendor, initial, logout } = useAuth()
const { sidebarOpen, closeSidebar } = useSidebar()
const { isActive } = useActiveLink()

// Track which groups are open
const openGroups = ref({})


function isGroupActive(children) {
    return children?.some(c => isActive(c.href))
}

// Auto-open groups that have an active child
sidebarLinks.forEach(link => {
    if (link.children && isGroupActive(link.children)) {
        openGroups.value[link.label] = true
    }
})

// Sort top-level links by order
const sortedLinks = computed(() =>
    [...sidebarLinks].sort((a, b) => (a.order ?? 99) - (b.order ?? 99))
)

</script>

<template>
  <div>
    <!-- Mobile overlay -->
    <Transition name="overlay">
      <div v-if="sidebarOpen"
        class="fixed inset-0 z-40 md:hidden"
        style="background:rgba(0,0,0,0.6);"
        @click="closeSidebar" />
    </Transition>

    <!-- Sidebar -->
    <aside
      class="flex flex-col shrink-0 fixed md:static z-50 md:z-auto
             transition-transform md:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
      style="width:220px;background:#111110;border-right:0.5px solid #2e2e2b;
             top:0;left:0;height:100vh;">

      <!-- Mobile header -->
      <div class="flex md:hidden items-center justify-between px-4 h-14"
        style="border-bottom:0.5px solid #2e2e2b;">
        <div class="flex items-center gap-2.5">
          <div class="w-7 h-7 rounded-md flex items-center justify-center"
            style="background:#d97706;">
            <Icons name="store" class="w-4 h-4" style="color:white;" />
          </div>
          <p class="text-sm font-medium" style="color:white;">Vendor Panel</p>
        </div>
        <button @click="closeSidebar"
          style="color:#6b6660;background:transparent;border:none;cursor:pointer;">
          <Icons name="x" class="w-5 h-5" />
        </button>
      </div>

      <!-- Nav links -->
      <div class="flex flex-col py-3 flex-1 overflow-y-auto">
        <p class="px-4 pb-2 text-xs font-medium"
          style="color:#6b6660;letter-spacing:0.07em;">
          NAVIGATION
        </p>

        <template v-for="link in sortedLinks" :key="link.href ?? link.label">
          <SidebarGroup v-if="link.children"
            :link="link" :isActive="isActive"
            @close="closeSidebar"
            @open-external="openExternal" />
          <SidebarLink v-else
            :link="link" :isActive="isActive"
            @close="closeSidebar"
            @open-external="openExternal" />
          <!-- ── Group with children ── -->


          <!-- ── Single link ── -->


        </template>
      </div>

      <!-- Bottom: vendor info + logout -->
      <div style="border-top:0.5px solid #2e2e2b;">
        <div class="flex items-center gap-3 px-4 py-3"
          style="border-bottom:0.5px solid #2e2e2b;">
          <div class="w-8 h-8 rounded-full flex items-center justify-center
            text-xs font-semibold shrink-0"
            style="background:#d97706;color:#111110;">
            {{ initial }}
          </div>
          <div class="min-w-0">
            <p class="text-sm font-medium truncate" style="color:white;">
              {{ vendor?.name ?? 'Vendor' }}
            </p>
            <p class="text-xs truncate" style="color:#6b6660;">
              {{ vendor?.email ?? '' }}
            </p>
          </div>
        </div>
        <div class="px-4 py-3">
          <button @click="logout()"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm"
            style="color:#ef4444;background:transparent;border:0.5px solid #2e2e2b;"
            onmouseover="this.style.background='#280e0e';this.style.borderColor='#ef4444'"
            onmouseout="this.style.background='transparent';this.style.borderColor='#2e2e2b'">
            <Icons name="logout" class="w-4 h-4" /> Logout
          </button>
        </div>
      </div>
    </aside>
  </div>
</template>

<style scoped>
.overlay-enter-active, .overlay-leave-active { transition: opacity 0.2s ease; }
.overlay-enter-from,   .overlay-leave-to     { opacity: 0; }

.slide-enter-active, .slide-leave-active { transition: all 0.2s ease; overflow: hidden; }
.slide-enter-from,   .slide-leave-to     { max-height: 0; opacity: 0; }
.slide-enter-to,     .slide-leave-from   { max-height: 200px; opacity: 1; }
</style>
