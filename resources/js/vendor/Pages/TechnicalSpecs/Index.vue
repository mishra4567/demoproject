<!-- resources/js/vendor/Pages/TechnicalSpecs/Index.vue -->
<script setup>
import { VendorLayout }       from '..'
import { Icons }          from '@/vendor/Components/index'
import { useTechnicalSpecs }  from '@/vendor/Back'

const {
    displayed, deletedData, products,
    showDeleted, showForm, editTarget,
    selected, bulkAction, form,
    openCreate, openEdit, closeForm,
    saveTechnicalSpec, toggleStatus,
    deleteTechnicalSpec, restoreTechnicalSpec, forceDeleteTechnicalSpec,
    toggleAll, applyBulk,
} = useTechnicalSpecs()

const formatDate = (d) => d
    ? new Date(d).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
    : '—'

const flagConfig = [
    { key: 'is_promo',      label: 'Promo',      color: '#818cf8' },
    { key: 'is_featured',   label: 'Featured',   color: '#f59e0b' },
    { key: 'is_discounted', label: 'Discounted', color: '#4ade80' },
    { key: 'is_trending',   label: 'Trending',   color: '#f87171' },
]
</script>

<template>
    <VendorLayout>
        <div class="space-y-5">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium" style="color:white;">Technical Specs</h1>
                <button @click="openCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium"
                    style="background:#d97706;color:#111110;">
                    <Icons name="plus" class="w-3.5 h-3.5" /> Add Spec
                </button>
            </div>

            <!-- Toolbar -->
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <button @click="showDeleted = !showDeleted"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs"
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
                        class="px-3 py-1.5 rounded-lg text-xs"
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
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[700px]">
                        <thead style="background:#1c1c1a;">
                            <tr>
                                <th class="px-4 py-3 text-left w-8">
                                    <input type="checkbox"
                                        @change="(e) => toggleAll(e.target.checked)" />
                                </th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">ID</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Product</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Title</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Lead Time</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Tax</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Flags</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Status</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="spec in displayed" :key="spec.id"
                                style="border-top:0.5px solid #2e2e2b;">

                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                        :value="spec.id" v-model="selected" />
                                </td>

                                <td class="px-4 py-3 text-xs" style="color:#6b6660;">
                                    #{{ spec.id }}
                                </td>

                                <td class="px-4 py-3 text-sm" style="color:white;">
                                    {{ spec.product_name ?? '#' + spec.product_id }}
                                </td>

                                <td class="px-4 py-3 text-sm"
                                    :style="showDeleted
                                        ? 'color:#6b6660;text-decoration:line-through;'
                                        : 'color:white;'">
                                    {{ spec.title }}
                                </td>

                                <!-- Lead time -->
                                <td class="px-4 py-3 text-xs" style="color:#9e9890;">
                                    <span v-if="spec.lead_time_from && spec.lead_time_to">
                                        {{ formatDate(spec.lead_time_from) }}
                                        <span style="color:#6b6660;"> — </span>
                                        {{ formatDate(spec.lead_time_to) }}
                                    </span>
                                    <span v-else style="color:#6b6660;">—</span>
                                </td>

                                <!-- Tax -->
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-0.5 rounded"
                                        style="background:#1c1c1a;color:#9e9890;border:0.5px solid #2e2e2b;">
                                        {{ spec.tax ?? 0 }}%
                                        <span style="color:#6b6660;">{{ spec.tax_type ?? '' }}</span>
                                    </span>
                                </td>

                                <!-- Flags -->
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-if="spec.is_promo"
                                            class="text-xs px-1.5 py-0.5 rounded"
                                            style="background:#1a1a2e;color:#818cf8;">Promo</span>
                                        <span v-if="spec.is_featured"
                                            class="text-xs px-1.5 py-0.5 rounded"
                                            style="background:#2e2010;color:#f59e0b;">Featured</span>
                                        <span v-if="spec.is_discounted"
                                            class="text-xs px-1.5 py-0.5 rounded"
                                            style="background:#1a2e1a;color:#4ade80;">Discounted</span>
                                        <span v-if="spec.is_trending"
                                            class="text-xs px-1.5 py-0.5 rounded"
                                            style="background:#2e1a1a;color:#f87171;">Trending</span>
                                        <span v-if="!spec.is_promo && !spec.is_featured
                                                     && !spec.is_discounted && !spec.is_trending"
                                            style="color:#6b6660;" class="text-xs">—</span>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    <button @click="toggleStatus(spec.id)"
                                        class="px-2 py-0.5 rounded text-xs"
                                        :style="spec.status
                                            ? 'background:#1a2e1a;color:#4ade80;'
                                            : 'background:#2e1a1a;color:#f87171;'">
                                        {{ spec.status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3">
                                    <div v-if="!showDeleted" class="flex items-center gap-2">
                                        <button @click="openEdit(spec)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.color='white'"
                                            onmouseout="this.style.color='#9e9890'">
                                            <Icons name="edit" class="w-3.5 h-3.5" /> Edit
                                        </button>
                                        <button @click="deleteTechnicalSpec(spec.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#280e0e'"
                                            onmouseout="this.style.background='transparent'">
                                            <Icons name="trash" class="w-3.5 h-3.5" /> Delete
                                        </button>
                                    </div>
                                    <div v-else class="flex items-center gap-2">
                                        <button @click="restoreTechnicalSpec(spec.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#4ade80;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#1a2e1a'"
                                            onmouseout="this.style.background='transparent'">
                                            Restore
                                        </button>
                                        <button @click="forceDeleteTechnicalSpec(spec.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#280e0e'"
                                            onmouseout="this.style.background='transparent'">
                                            Delete Forever
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!displayed.length">
                                <td colspan="9" class="px-4 py-12 text-center text-sm"
                                    style="color:#6b6660;">
                                    {{ showDeleted ? 'No deleted specs.' : 'No technical specs found.' }}
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

                <div class="rounded-xl w-full max-w-lg"
                    style="background:#1c1c1a;border:0.5px solid #2e2e2b;
                           max-height:90vh;display:flex;flex-direction:column;">

                    <!-- Header -->
                    <div class="flex items-center justify-between px-5 py-4 shrink-0"
                        style="border-bottom:0.5px solid #2e2e2b;">
                        <h2 class="text-sm font-medium" style="color:white;">
                            {{ editTarget ? 'Edit Technical Spec' : 'Add Technical Spec' }}
                        </h2>
                        <button @click="closeForm" style="color:#6b6660;">
                            <Icons name="x" class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Body -->
                    <form @submit.prevent="saveTechnicalSpec"
                        class="overflow-y-auto flex-1 px-5 py-4 space-y-4">

                        <input type="hidden" v-model="form.id" />

                        <!-- Product -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Product <span style="color:#ef4444;">*</span>
                            </label>
                            <select v-model="form.product_id"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                                <option value="">Select product</option>
                                <option v-for="p in products" :key="p.id" :value="p.id">
                                    #{{ p.id }} — {{ p.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.product_id" class="text-xs mt-1"
                                style="color:#ef4444;">{{ form.errors.product_id }}</p>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Title <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.title" type="text"
                                placeholder="e.g. Delivery & Tax Info"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                            <p v-if="form.errors.title" class="text-xs mt-1"
                                style="color:#ef4444;">{{ form.errors.title }}</p>
                        </div>

                        <!-- Lead Time -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                    Lead time from
                                </label>
                                <input v-model="form.lead_time_from" type="datetime-local"
                                    class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                    style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                                <p v-if="form.errors.lead_time_from" class="text-xs mt-1"
                                    style="color:#ef4444;">{{ form.errors.lead_time_from }}</p>
                            </div>
                            <div>
                                <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                    Lead time to
                                </label>
                                <input v-model="form.lead_time_to" type="datetime-local"
                                    class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                    style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                                <p v-if="form.errors.lead_time_to" class="text-xs mt-1"
                                    style="color:#ef4444;">{{ form.errors.lead_time_to }}</p>
                            </div>
                        </div>

                        <!-- Tax + Tax Type -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1.5" style="color:#6b6660;">Tax (%)</label>
                                <input v-model="form.tax" type="number"
                                    min="0" max="100" step="0.01" placeholder="0"
                                    class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                    style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                            </div>
                            <div>
                                <label class="block text-xs mb-1.5" style="color:#6b6660;">Tax type</label>
                                <select v-model="form.tax_type"
                                    class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                    style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                                    <option value="none">None</option>
                                    <option value="inclusive">Inclusive</option>
                                    <option value="exclusive">Exclusive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Flags -->
                        <div>
                            <label class="block text-xs mb-2" style="color:#6b6660;">Flags</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label v-for="flag in flagConfig" :key="flag.key"
                                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg cursor-pointer"
                                    :style="form[flag.key]
                                        ? `background:${flag.color}18;border:0.5px solid ${flag.color}44;`
                                        : 'background:#111110;border:0.5px solid #2e2e2b;'">
                                    <input type="checkbox" v-model="form[flag.key]"
                                        class="w-3.5 h-3.5 rounded" />
                                    <span class="text-xs font-medium"
                                        :style="form[flag.key]
                                            ? `color:${flag.color}`
                                            : 'color:#9e9890'">
                                        {{ flag.label }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Edit indicator -->
                        <div v-if="editTarget"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs"
                            style="background:#1a1a2e;color:#818cf8;border:0.5px solid #2e2e2b;">
                            <Icons name="edit" class="w-3.5 h-3.5" />
                            Editing spec for
                            <span class="font-medium" style="color:white;">
                                {{ editTarget.product_name ?? '#' + editTarget.product_id }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit" :disabled="form.processing"
                                class="flex-1 py-2.5 rounded-lg text-sm font-medium"
                                style="background:#d97706;color:#111110;"
                                :style="form.processing ? 'opacity:0.6' : ''">
                                {{ form.processing
                                    ? 'Saving...'
                                    : editTarget ? 'Update Spec' : 'Create Spec' }}
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
