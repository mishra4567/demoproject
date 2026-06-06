<!-- ./resources/js/vendor/Components/Nav/Navbar.vue -->
<script setup>
import { Link }    from '@inertiajs/vue3'
import { ref }     from 'vue'
import { useAuth } from '@/vendor/Back'
import { usePage } from '@inertiajs/vue3'
import { Icons, SearchBar }    from '@/vendor/Components/index'       // ← import Icon
import { useActiveLink, useClickOutside, useSidebar } from '@/vendor/assets'

const { vendor, initial, logout } = useAuth()
const { toggleSidebar } = useSidebar();

const sidebarOpen = ref(false)
const profileOpen = ref(false)
const profileRef   = ref(null)

// Close dropdown when clicking outside
useClickOutside(profileRef, () => {
    profileOpen.value = false
})
function toggleProfile() {
  profileOpen.value = !profileOpen.value
}
function closeAll() {
  profileOpen.value = false
  sidebarOpen.value = false
}

const { isActive } = useActiveLink()
</script>

<template>
  <div>
    <!-- ── Top bar ───────────────────────────────────────────────── -->
    <nav style="background:#111110; border-bottom:0.5px solid #2e2e2b;">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-14 gap-4">

          <!-- Logo -->
          <div class="flex items-center gap-2.5 shrink-0">

            <!-- Hamburger — mobile only -->
            <button
              @click="toggleSidebar"
              class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg cursor-pointer"
              style="border:0.5px solid #2e2e2b;color:#9e9890;background:transparent;"
              aria-label="Toggle sidebar">
              <Icons name="menu" class="w-4 h-4" />
            </button>
            <div class="w-7 h-7 rounded-md flex items-center justify-center shrink-0"
              style="background:#d97706;">
              <Icons name="store" class="w-4 h-4" style="color:white;" />
            </div>
            <div class="hidden sm:block">
              <p class="text-sm font-medium leading-none" style="color:white;">Vendor Panel</p>
              <p class="text-xs leading-none mt-0.5" style="color:#6b6660;">Seller Dashboard</p>
            </div>
          </div>

          <!-- Search -->
          <div class="flex flex-1 max-w-[160px] md:max-w-sm">
            <SearchBar />
          </div>

          <!-- Profile dropdown -->
          <div class="relative shrink-0" ref="profileRef">
            <button @click="profileOpen = !profileOpen"
              class="flex items-center gap-2 cursor-pointer bg-transparent border-0 p-0">
              <div class="w-7 h-7 rounded-full flex items-center justify-center
                text-xs font-semibold shrink-0"
                style="background:#d97706; color:#111110;">
                {{ initial }}
              </div>
              <span class="hidden sm:block text-xs" style="color:#9e9890;">
                {{ vendor?.name ?? 'Vendor' }}
              </span>
              <Icons name="chevron-down" class="hidden sm:block w-3 h-3" style="color:#6b6660;" />
            </button>

            <Transition name="fade">
              <div v-if="profileOpen"
                class="absolute right-0 top-10 w-52 rounded-xl overflow-hidden z-50"
                style="background:#1c1c1a; border:0.5px solid #2e2e2b;">
                <div class="flex items-center gap-2.5 px-3 py-3"
                  style="border-bottom:0.5px solid #2e2e2b;">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center
                    text-xs font-semibold shrink-0"
                    style="background:#d97706; color:#111110;">
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
                <Link href="/vendor/profile" @click="closeAll"
                  class="flex items-center gap-2 px-3 py-2.5 text-sm w-full"
                  style="color:#9e9890;"
                  onmouseover="this.style.background='#252523';this.style.color='white'"
                  onmouseout="this.style.background='transparent';this.style.color='#9e9890'">
                  <Icons name="user" class="w-4 h-4" /> Profile
                </Link>
                <Link href="/vendor/settings" @click="closeAll"
                  class="flex items-center gap-2 px-3 py-2.5 text-sm w-full"
                  style="color:#9e9890;"
                  onmouseover="this.style.background='#252523';this.style.color='white'"
                  onmouseout="this.style.background='transparent';this.style.color='#9e9890'">
                  <Icons name="settings" class="w-4 h-4" /> Settings
                </Link>
                <div style="height:0.5px; background:#2e2e2b;"></div>
                <button @click="logout(); closeAll()"
                  class="w-full flex items-center gap-2 px-3 py-2.5 text-sm"
                  style="color:#ef4444; background:transparent;"
                  onmouseover="this.style.background='#280e0e'"
                  onmouseout="this.style.background='transparent'">
                  <Icons name="logout" class="w-4 h-4" /> Logout
                </button>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; transform: translateY(-6px); }
</style>
