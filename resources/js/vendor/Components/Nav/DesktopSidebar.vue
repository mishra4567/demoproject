<script setup >
import { Icons } from '..';

</script>

<!-- ... existing mobile overlay + sidebar ... -->

    <!-- Desktop sidebar -->
     <template>
<aside class="hidden md:flex flex-col fixed left-0 shrink-0 z-30"
    style="width:220px;
           top:96px;
           height:calc(100vh - 96px);
           background:#111110;
           border-right:0.5px solid #2e2e2b;">

    <div class="flex flex-col py-3 flex-1">

        <p class="px-4 pb-2 text-xs"
            style="color:#6b6660;letter-spacing:0.05em;">
            NAVIGATION
        </p>

        <!-- ← navLinks must be here, BEFORE the divider -->
        <Link
            v-for="link in navLinks" :key="link.href" :href="link.href"
            class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors"
            :style="isActive(link.href)
                ? 'background:#1c1c1a;color:white;'
                : 'color:#9e9890;'"
            onmouseover="if(!this.style.background.includes('1c1c1a')){this.style.color='white'}"
            onmouseout="if(!this.style.background.includes('1c1c1a')){this.style.color='#9e9890'}"
        >
            <Icons :name="link.icon" class="w-4 h-4" />
            {{ link.label }}
        </Link>

        <div style="height:0.5px;background:#2e2e2b;margin:8px 16px;"></div>

        <Link href="/vendor/profile"
            class="flex items-center gap-3 px-4 py-2.5 text-sm"
            style="color:#9e9890;"
            onmouseover="this.style.color='white'"
            onmouseout="this.style.color='#9e9890'">
            <Icons name="user" class="w-4 h-4" /> Profile
        </Link>

        <Link href="/vendor/settings"
            class="flex items-center gap-3 px-4 py-2.5 text-sm"
            style="color:#9e9890;"
            onmouseover="this.style.color='white'"
            onmouseout="this.style.color='#9e9890'">
            <Icons name="settings" class="w-4 h-4" /> Settings
        </Link>

    </div>

    <!-- Bottom: vendor info + logout -->
    <div style="border-top:0.5px solid #2e2e2b;">
        <div class="flex items-center gap-3 px-4 py-4"
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
</template>
