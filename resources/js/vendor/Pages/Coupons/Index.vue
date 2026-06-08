<!-- resources/js/vendor/Pages/Coupons/Index.vue -->
<script setup>
import VendorLayout from '../../Layouts/VendorLayout.vue'
import { Icons }    from '@/vendor/Components/index'
import {useCoupon}   from '@/vendor/Back'

const {
    displayed,
    deletedData,
    showDeleted,
    showForm,
    editTarget,
    selected,
    bulkAction,
    form,
    openCreate,
    openEdit,
    closeForm,
    saveCoupon,
    toggleStatus,
    deleteCoupon,
    restoreCoupon,
    forceDeleteCoupon,
    toggleAll,
    applyBulk,
} = useCoupon()
</script>

<template>
    <VendorLayout>
        <div class="space-y-5">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium" style="color:white;">Coupons</h1>
                <button @click="openCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium"
                    style="background:#d97706;color:#111110;">
                    <Icons name="plus" class="w-3.5 h-3.5" /> Add Coupon
                </button>
            </div>

            <!-- Toolbar -->
            <div class="flex items-center justify-between gap-3 flex-wrap">

                <!-- Trash toggle -->
                <button @click="showDeleted = !showDeleted"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition-colors"
                    :style="showDeleted
                        ? 'background:#280e0e;color:#ef4444;border:0.5px solid #ef4444;'
                        : 'background:#1c1c1a;color:#9e9890;border:0.5px solid #2e2e2b;'">
                    <Icons name="trash" class="w-3.5 h-3.5" />
                    {{ showDeleted ? 'Show Active' : 'Show Deleted' }}
                    <span v-if="deletedData.length"
                        class="px-1.5 py-0.5 rounded-full text-xs"
                        style="background:#ef4444;color:white;line-height:1;">
                        {{ deletedData.length }}
                    </span>
                </button>

                <!-- Bulk action -->
                <div class="flex items-center gap-2">
                    <select v-model="bulkAction"
                        class="text-xs rounded-lg px-3 py-1.5 outline-none"
                        style="background:#1c1c1a;color:#9e9890;border:0.5px solid #2e2e2b;">
                        <option value="">Bulk Action</option>
                        <template v-if="!showDeleted">
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="trash">Move to Trash</option>
                        </template>
                        <template v-else>
                            <option value="restore">Restore</option>
                            <option value="permanent_delete">Delete Forever</option>
                        </template>
                    </select>
                    <button @click="applyBulk"
                        class="px-3 py-1.5 rounded-lg text-xs transition-colors"
                        style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;"
                        onmouseover="this.style.borderColor='#6b6660'"
                        onmouseout="this.style.borderColor='#2e2e2b'">
                        Apply
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-xl overflow-hidden"
                style="border:0.5px solid #2e2e2b;">
                <div class="overflow-x-auto">        <!-- ← add this wrapper -->
                <table class="w-full text-sm min-w-[640px]">
                    <thead style="background:#1c1c1a;">
                        <tr>
                            <th class="px-4 py-3 text-left w-8">
                                <input type="checkbox"
                                    @change="(e) => toggleAll(e.target.checked)" />
                            </th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">ID</th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Title</th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Code</th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Value</th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Type</th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Expiry</th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Status</th>
                            <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="coupon in displayed" :key="coupon.id"
                            style="border-top:0.5px solid #2e2e2b;">

                            <!-- Checkbox -->
                            <td class="px-4 py-3">
                                <input type="checkbox"
                                    :value="coupon.id" v-model="selected" />
                            </td>

                            <!-- ID -->
                            <td class="px-4 py-3 text-xs" style="color:#6b6660;">
                                #{{ coupon.id }}
                            </td>

                            <!-- Title -->
                            <td class="px-4 py-3 text-sm" style="color:white;">
                                {{ coupon.title }}
                            </td>

                            <!-- Code -->
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-mono"
                                    style="background:#111110;color:#d97706;border:0.5px solid #2e2e2b;">
                                    {{ coupon.code }}
                                </span>
                            </td>

                            <!-- Value -->
                            <td class="px-4 py-3 text-sm font-medium" style="color:white;">
                                {{ coupon.type === 'percent'
                                    ? coupon.value + '%'
                                    : '₹' + coupon.value }}
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-xs"
                                    :style="coupon.type === 'percent'
                                        ? 'background:#1a2e1a;color:#4ade80;'
                                        : 'background:#1a1a2e;color:#818cf8;'">
                                    {{ coupon.type }}
                                </span>
                            </td>

                            <!-- Expiry -->
                            <td class="px-4 py-3 text-xs" style="color:#6b6660;">
                                {{ coupon.expiry ?? '—' }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <button @click="toggleStatus(coupon.id)"
                                    class="px-2 py-0.5 rounded text-xs transition-colors cursor-pointer"
                                    :style="coupon.status
                                        ? 'background:#1a2e1a;color:#4ade80;'
                                        : 'background:#2e1a1a;color:#f87171;'">
                                    {{ coupon.status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3">
                                <div v-if="!showDeleted" class="flex items-center gap-2">
                                    <button @click="openEdit(coupon)"
                                        class="flex items-center gap-1 px-2 py-1 rounded text-xs cursor-pointer"
                                        style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                                        onmouseover="this.style.color='white'"
                                        onmouseout="this.style.color='#9e9890'">
                                        <Icons name="edit" class="w-3.5 h-3.5" /> Edit
                                    </button>
                                    <button @click="deleteCoupon(coupon.id)"
                                        class="flex items-center gap-1 px-2 py-1 rounded text-xs cursor-pointer"
                                        style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                        onmouseover="this.style.background='#280e0e'"
                                        onmouseout="this.style.background='transparent'">
                                        <Icons name="trash" class="w-3.5 h-3.5" /> Delete
                                    </button>
                                </div>
                                <div v-else class="flex items-center gap-2">
                                    <button @click="restoreCoupon(coupon.id)"
                                        class="flex items-center gap-1 px-2 py-1 rounded text-xs cursor-pointer"
                                        style="color:#4ade80;border:0.5px solid #2e2e2b;background:transparent;"
                                        onmouseover="this.style.background='#1a2e1a'"
                                        onmouseout="this.style.background='transparent'">
                                        Restore
                                    </button>
                                    <button @click="forceDeleteCoupon(coupon.id)"
                                        class="flex items-center gap-1 px-2 py-1 rounded text-xs cursor-pointer"
                                        style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                        onmouseover="this.style.background='#280e0e'"
                                        onmouseout="this.style.background='transparent'">
                                        Delete Forever
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Empty -->
                        <tr v-if="!displayed.length">
                            <td colspan="9" class="px-4 py-12 text-center text-sm"
                                style="color:#6b6660;">
                                {{ showDeleted ? 'No deleted coupons.' : 'No coupons found.' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>

        </div>

        <!-- Create / Edit Modal -->
        <Transition name="fade">
            <div v-if="showForm"
                class="fixed inset-0 z-50 flex items-center justify-center px-4"
                style="background:rgba(0,0,0,0.75);"
                @click.self="closeForm">

                <div class="rounded-xl w-full max-w-md"
                    style="background:#1c1c1a;border:0.5px solid #2e2e2b;">

                    <!-- Modal header -->
                    <div class="flex items-center justify-between px-5 py-4"
                        style="border-bottom:0.5px solid #2e2e2b;">
                        <h2 class="text-sm font-medium" style="color:white;">
                            {{ editTarget ? 'Edit Coupon' : 'Add Coupon' }}
                        </h2>
                        <button @click="closeForm" style="color:#6b6660;">
                            <Icons name="x" class="w-4 h-4" />
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form @submit.prevent="saveCoupon" class="p-5 space-y-4">

                        <!-- Hidden id for edit mode -->
                        <input type="hidden" v-model="form.id" />

                        <!-- Title -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Title <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.title" type="text"
                                placeholder="e.g. Summer Sale"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                            <p v-if="form.errors.title" class="text-xs mt-1" style="color:#ef4444;">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <!-- Code -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Code <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.code" type="text"
                                placeholder="e.g. SAVE20"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none font-mono uppercase"
                                style="background:#111110;color:#d97706;border:0.5px solid #2e2e2b;" />
                            <p v-if="form.errors.code" class="text-xs mt-1" style="color:#ef4444;">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <!-- Value + Type -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                    Value <span style="color:#ef4444;">*</span>
                                </label>
                                <input v-model="form.value" type="number" min="1"
                                    placeholder="0"
                                    class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                    style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                                <p v-if="form.errors.value" class="text-xs mt-1" style="color:#ef4444;">
                                    {{ form.errors.value }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                    Type <span style="color:#ef4444;">*</span>
                                </label>
                                <select v-model="form.type"
                                    class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                    style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                                    <option value="flat">Flat (₹)</option>
                                    <option value="percent">Percent (%)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Expiry -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Expiry Date
                                <span style="color:#6b6660;">(optional)</span>
                            </label>
                            <input v-model="form.expiry" type="date"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                        </div>

                        <!-- Edit mode indicator -->
                        <div v-if="editTarget"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs"
                            style="background:#1a1a2e;color:#818cf8;border:0.5px solid #2e2e2b;">
                            <Icons name="edit" class="w-3.5 h-3.5" />
                            Editing coupon <span class="font-mono" style="color:#d97706;">#{{ editTarget.id }}</span>
                            — {{ editTarget.code }}
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit" :disabled="form.processing"
                                class="flex-1 py-2.5 rounded-lg text-sm font-medium"
                                style="background:#d97706;color:#111110;"
                                :style="form.processing ? 'opacity:0.6' : ''">
                                {{ form.processing
                                    ? 'Saving...'
                                    : editTarget ? 'Update Coupon' : 'Create Coupon' }}
                            </button>
                            <button type="button" @click="closeForm"
                                class="px-4 py-2.5 rounded-lg text-sm"
                                style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                                onmouseover="this.style.color='white'"
                                onmouseout="this.style.color='#9e9890'">
                                Cancel
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </Transition>

    </VendorLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; }
</style>
