<script setup>
import { Link }    from '@inertiajs/vue3'
import { ref }     from 'vue'
import { useAuth } from '@/vendor/Back'
import { usePage } from '@inertiajs/vue3'
import { Icons, SearchBar }    from '@/vendor/Components/index'       // ← import Icon
import { useClickOutside } from '@/vendor/assets'

const { vendor, initial, logout } = useAuth()

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

const navLinks = [
  { href: '/vendor',          label: 'Dashboard', icon: 'dashboard' },
  { href: '/vendor/products', label: 'Products',  icon: 'product'   },
  { href: '/vendor/orders',   label: 'Orders',    icon: 'orders'    },
]

const page     = usePage()
const isActive = (path) => {
    // Exact match for root vendor dashboard only
    if (path === '/vendor') {
        return page.url === '/vendor'
    }

    // Exact or sub-route match for everything else
    return page.url === path || page.url.startsWith(path + '/')
}
</script>

<template>
  <div>
    <nav style="background:#111110; border-bottom:0.5px solid #2e2e2b;">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-14 gap-4">

          <!-- Logo -->
          <div class="flex items-center gap-2.5 shrink-0">
            <button @click="sidebarOpen = true"
              class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg"
              style="border:0.5px solid #2e2e2b;color:#9e9890;background:transparent;">
              <Icons name="menu" class="w-4 h-4" />
            </button>
            <div class="w-7 h-7 rounded-md flex items-center justify-center shrink-0"
              style="background:#d97706;">
              <Icons name="store" class="w-4 h-4" style="color:white;" />
            </div>
            <div>
              <p class="text-sm font-medium leading-none" style="color:white;">Vendor Panel</p>
              <p class="text-xs leading-none mt-0.5" style="color:#6b6660;">Seller Dashboard</p>
            </div>
          </div>

          <!-- Search  -->
          <div class=" flex flex-1 max-w-[200px] md:max-w-sm">
            <SearchBar />
          </div>

          <!-- Right: avatar + dropdown -->
          <div class="relative shrink-0" ref="profileRef">   <!-- ← add ref here -->
            <button @click="toggleProfile"
                class="flex items-center gap-2 cursor-pointer bg-transparent border-0 p-0">
                <div class="w-7 h-7 rounded-full flex items-center justify-center
                    text-xs font-semibold shrink-0"
                    style="background:#d97706;color:#111110;">
                    {{ initial }}
                </div>
                <span class="hidden sm:block text-xs" style="color:#9e9890;">
                    {{ vendor?.name ?? 'Vendor' }}
                </span>
                <Icon name="chevron-down" class="hidden sm:block w-3 h-3" style="color:#6b6660;" />
            </button>

            <!-- Profile dropdown (unchanged) -->
            <Transition name="fade">
              <div v-if="profileOpen"
                class="absolute right-0 top-10 w-52 rounded-xl overflow-hidden z-50"
                style="background:#1c1c1a;border:0.5px solid #2e2e2b;">
                <div class="flex items-center gap-2.5 px-3 py-3"
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
                <div style="height:0.5px;background:#2e2e2b;"></div>
                <button @click="logout(); closeAll()"
                  class="w-full flex items-center gap-2 px-3 py-2.5 text-sm"
                  style="color:#ef4444;background:transparent;"
                  onmouseover="this.style.background='#280e0e'"
                  onmouseout="this.style.background='transparent'">
                  <Icons name="logout" class="w-4 h-4" /> Logout
                </button>
              </div>
            </Transition>
          </div>

        </div>
      </div>

      <!-- Desktop secondary nav bar -->
      <div class="hidden md:block" style="border-top:0.5px solid #2e2e2b;">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-1 h-10">
                <Link
                    v-for="link in navLinks" :key="link.href" :href="link.href"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    :style="isActive(link.href) ? 'background:#1c1c1a;color:white;' : 'color:#9e9890;'"
                >
                    <Icons :name="link.icon" class="w-3.5 h-3.5" />
                    {{ link.label }}
                </Link>
            </div>
        </div>
    </div>
    </nav>

    <!-- Mobile sidebar overlay + drawer unchanged below -->
    <Transition name="overlay">
      <div v-if="sidebarOpen"
        class="fixed inset-0 z-40 md:hidden"
        style="background:rgba(0,0,0,0.6);"
        @click="sidebarOpen = false" />
    </Transition>

    <Transition name="sidebar">
      <div v-if="sidebarOpen"
        class="fixed top-0 left-0 h-full z-50 md:hidden flex flex-col"
        style="width:260px;background:#111110;border-right:0.5px solid #2e2e2b;">
        <div class="flex items-center justify-between px-4 h-14"
          style="border-bottom:0.5px solid #2e2e2b;">
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-md flex items-center justify-center"
              style="background:#d97706;">
              <Icons name="store" class="w-4 h-4" style="color:white;" />
            </div>
            <p class="text-sm font-medium" style="color:white;">Vendor Panel</p>
          </div>
          <button @click="sidebarOpen = false"
            style="color:#6b6660;background:transparent;border:none;cursor:pointer;">
            <Icons name="x" class="w-5 h-5" />
          </button>
        </div>
        <div class="flex items-center gap-3 px-4 py-4"
          style="border-bottom:0.5px solid #2e2e2b;">
          <div class="w-9 h-9 rounded-full flex items-center justify-center
            text-sm font-semibold shrink-0"
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
        <div class="flex flex-col py-3 flex-1">
          <p class="px-4 pb-2 text-xs" style="color:#6b6660;letter-spacing:0.05em;">NAVIGATION</p>
          <Link v-for="link in navLinks" :key="link.href" :href="link.href"
            @click="closeAll"
            class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors"
            :style="isActive(link.href) ? 'background:#1c1c1a;color:white;' : 'color:#9e9890;'"
          >
            <Icons :name="link.icon" class="w-4 h-4" />
            {{ link.label }}
          </Link>
          <div style="height:0.5px;background:#2e2e2b;margin:8px 16px;"></div>
          <Link href="/vendor/profile" @click="closeAll"
            class="flex items-center gap-3 px-4 py-2.5 text-sm" style="color:#9e9890;">
            <Icons name="user" class="w-4 h-4" /> Profile
          </Link>
          <Link href="/vendor/settings" @click="closeAll"
            class="flex items-center gap-3 px-4 py-2.5 text-sm" style="color:#9e9890;">
            <Icons name="settings" class="w-4 h-4" /> Settings
          </Link>
        </div>
        <div style="border-top:0.5px solid #2e2e2b;padding:12px 16px;">
          <button @click="logout(); closeAll()"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm"
            style="color:#ef4444;background:transparent;border:0.5px solid #2e2e2b;"
            onmouseover="this.style.background='#280e0e';this.style.borderColor='#ef4444'"
            onmouseout="this.style.background='transparent';this.style.borderColor='#2e2e2b'">
            <Icons name="logout" class="w-4 h-4" /> Logout
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.fade-enter-active,    .fade-leave-active    { transition: opacity 0.15s ease, transform 0.15s ease; }
.fade-enter-from,      .fade-leave-to        { opacity: 0; transform: translateY(-6px); }
.overlay-enter-active, .overlay-leave-active { transition: opacity 0.2s ease; }
.overlay-enter-from,   .overlay-leave-to     { opacity: 0; }
.sidebar-enter-active, .sidebar-leave-active { transition: transform 0.25s ease; }
.sidebar-enter-from,   .sidebar-leave-to     { transform: translateX(-100%); }
</style>
