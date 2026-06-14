<!-- resources/js/vendor/Pages/LinkProducts/Index.vue -->
<script setup>
import { VendorLayout }     from '..'
import { Icons, MediaSelector } from '@/vendor/Components'
import { useLinkProducts }  from '@/vendor/Back'

const {
    displayed, deletedData,
    products, sizes, colors,
    showDeleted, showForm, editTarget,
    selected, bulkAction, form, rows, addRow, removeRow,
    openCreate, openEdit, closeForm,
    saveLinkProduct, toggleStatus,
    deleteLinkProduct, restoreLinkProduct, forceDeleteLinkProduct,
    toggleAll, applyBulk,
} = useLinkProducts()

// find color hex for preview
const colorHex = (id) => colors.value.find(c => c.id == id)?.hex_id ?? '#888'
</script>

<template>
    <VendorLayout>
        <div class="space-y-5">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium" style="color:white;">Link Products</h1>
                <button @click="openCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium"
                    style="background:#d97706;color:#111110;">
                    <Icons name="plus" class="w-3.5 h-3.5" /> Add Link Product
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
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Image</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Product</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">SKU</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">MRP</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Price</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Qty</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Size</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Color</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Status</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in displayed" :key="item.id"
                                style="border-top:0.5px solid #2e2e2b;">

                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                        :value="item.id" v-model="selected" />
                                </td>

                                <td class="px-4 py-3 text-xs" style="color:#6b6660;">
                                    #{{ item.id }}
                                </td>

                                <!-- Image -->
                                <td class="px-4 py-3">
                                    <img v-if="item.file_name"
                                        :src="`/storage/media/${item.file_name}`"
                                        class="w-10 h-10 rounded-lg object-cover"
                                        :style="showDeleted ? 'filter:grayscale(100%)' : ''" />
                                    <span v-else class="text-xs" style="color:#6b6660;">—</span>
                                </td>

                                <!-- Product -->
                                <td class="px-4 py-3 text-sm" style="color:white;">
                                    {{ item.product_name ?? '#' + item.product_id }}
                                </td>

                                <!-- SKU -->
                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono px-2 py-0.5 rounded"
                                        style="background:#1c1c1a;color:#d97706;
                                               border:0.5px solid #2e2e2b;">
                                        {{ item.sku }}
                                    </span>
                                </td>

                                <!-- MRP -->
                                <td class="px-4 py-3 text-xs"
                                    style="color:#6b6660;text-decoration:line-through;">
                                    ₹{{ item.mrp }}
                                </td>

                                <!-- Price -->
                                <td class="px-4 py-3 text-sm font-medium" style="color:white;">
                                    ₹{{ item.price }}
                                </td>

                                <!-- Qty -->
                                <td class="px-4 py-3 text-sm" style="color:white;">
                                    {{ item.qty }}
                                </td>

                                <!-- Size -->
                                <td class="px-4 py-3 text-xs" style="color:#9e9890;">
                                    {{ item.size_name ?? '—' }}
                                </td>

                                <!-- Color -->
                                <td class="px-4 py-3">
                                    <div v-if="item.color_name"
                                        class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded-full"
                                            :style="`background:${item.hex_id};
                                                     border:0.5px solid #2e2e2b;`">
                                        </div>
                                        <span class="text-xs" style="color:#9e9890;">
                                            {{ item.color_name }}
                                        </span>
                                    </div>
                                    <span v-else class="text-xs" style="color:#6b6660;">—</span>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    <button @click="toggleStatus(item.id)"
                                        class="px-2 py-0.5 rounded text-xs"
                                        :style="item.status
                                            ? 'background:#1a2e1a;color:#4ade80;'
                                            : 'background:#2e1a1a;color:#f87171;'">
                                        {{ item.status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3">
                                    <div v-if="!showDeleted" class="flex items-center gap-2">
                                        <button @click="openEdit(item)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.color='white'"
                                            onmouseout="this.style.color='#9e9890'">
                                            <Icons name="edit" class="w-3.5 h-3.5" /> Edit
                                        </button>
                                        <button @click="deleteLinkProduct(item.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#280e0e'"
                                            onmouseout="this.style.background='transparent'">
                                            <Icons name="trash" class="w-3.5 h-3.5" /> Delete
                                        </button>
                                    </div>
                                    <div v-else class="flex items-center gap-2">
                                        <button @click="restoreLinkProduct(item.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#4ade80;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#1a2e1a'"
                                            onmouseout="this.style.background='transparent'">
                                            Restore
                                        </button>
                                        <button @click="forceDeleteLinkProduct(item.id)"
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
                                <td colspan="12" class="px-4 py-12 text-center text-sm"
                                    style="color:#6b6660;">
                                    {{ showDeleted ? 'No deleted items.' : 'No link products found.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Create / Edit Modal -->
<Transition name="fade">
    <div v-if="showForm && !editTarget"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="background:rgba(0,0,0,0.75);"
        @click.self="closeForm">

        <div class="rounded-xl w-full max-w-2xl"
            style="background:#1c1c1a;border:0.5px solid #2e2e2b;
                   max-height:90vh;display:flex;flex-direction:column;">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 shrink-0"
                style="border-bottom:0.5px solid #2e2e2b;">
                <div>
                    <h2 class="text-sm font-medium" style="color:white;">Add Variants</h2>
                    <p class="text-xs mt-0.5" style="color:#6b6660;">
                        {{ rows.length }} variant{{ rows.length > 1 ? 's' : '' }} ready to save
                    </p>
                </div>
                <button @click="closeForm" style="color:#6b6660;">
                    <Icons name="x" class="w-4 h-4" />
                </button>
            </div>

            <!-- Rows -->
            <div class="overflow-y-auto flex-1 px-5 py-4 space-y-3">
                <div v-for="(row, idx) in rows" :key="idx"
                    class="rounded-lg p-4"
                    style="background:#111110;border:0.5px solid #2e2e2b;">

                    <!-- Row header -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium"
                            style="color:#6b6660;letter-spacing:0.05em;">
                            VARIANT #{{ idx + 1 }}
                        </span>
                        <button v-if="rows.length > 1"
                            type="button" @click="removeRow(idx)"
                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                            style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                            onmouseover="this.style.background='#280e0e'"
                            onmouseout="this.style.background='transparent'">
                            <Icons name="x" class="w-3 h-3" /> Remove
                        </button>
                    </div>

                    <!-- Product + SKU -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs mb-1" style="color:#6b6660;">
                                Product <span style="color:#ef4444;">*</span>
                            </label>
                            <select v-model="row.product_id"
                                class="w-full rounded-lg px-3 py-2 text-xs outline-none"
                                style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;">
                                <option value="">Select product</option>
                                <option v-for="p in products" :key="p.id" :value="p.id">
                                    {{ p.name ?? '#' + p.id }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs mb-1" style="color:#6b6660;">
                                SKU <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="row.sku" type="text"
                                placeholder="KRT-M-RED-001"
                                class="w-full rounded-lg px-3 py-2 text-xs outline-none font-mono uppercase"
                                style="background:#1c1c1a;color:#d97706;border:0.5px solid #2e2e2b;" />
                        </div>
                    </div>

                    <!-- MRP + Price + Qty -->
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-xs mb-1" style="color:#6b6660;">MRP (₹)</label>
                            <input v-model="row.mrp" type="number" min="0"
                                placeholder="0.00"
                                class="w-full rounded-lg px-3 py-2 text-xs outline-none"
                                style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;" />
                        </div>
                        <div>
                            <label class="block text-xs mb-1" style="color:#6b6660;">
                                Price (₹) <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="row.price" type="number" min="0"
                                placeholder="0.00"
                                class="w-full rounded-lg px-3 py-2 text-xs outline-none"
                                style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;" />
                        </div>
                        <div>
                            <label class="block text-xs mb-1" style="color:#6b6660;">
                                Qty <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="row.qty" type="number" min="0"
                                placeholder="0"
                                class="w-full rounded-lg px-3 py-2 text-xs outline-none"
                                style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;" />
                        </div>
                    </div>

                    <!-- Size + Color -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs mb-1" style="color:#6b6660;">Size</label>
                            <select v-model="row.size_id"
                                class="w-full rounded-lg px-3 py-2 text-xs outline-none"
                                style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;">
                                <option value="">Select size</option>
                                <option v-for="s in sizes" :key="s.id" :value="s.id">
                                    {{ s.size }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs mb-1" style="color:#6b6660;">Color</label>
                            <div class="flex items-center gap-2">
                                <select v-model="row.color_id"
                                    class="flex-1 rounded-lg px-3 py-2 text-xs outline-none"
                                    style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;">
                                    <option value="">Select color</option>
                                    <option v-for="c in colors" :key="c.id" :value="c.id">
                                        {{ c.color_name }}
                                    </option>
                                </select>
                                <div class="w-7 h-7 rounded-full shrink-0 transition-colors"
                                    :style="`background:${row.color_id
                                        ? colorHex(row.color_id)
                                        : '#2e2e2b'};
                                        border:0.5px solid #2e2e2b;`">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div>
                        <label class="block text-xs mb-1" style="color:#6b6660;">Image</label>
                        <MediaSelector
                            v-model="row.media_id"
                            v-model:previewFile="row.previewFile"
                        />
                    </div>
                </div>

                <!-- Add row -->
                <button type="button" @click="addRow"
                    class="w-full flex items-center justify-center gap-2
                           py-3 rounded-lg text-xs transition-colors"
                    style="color:#9e9890;border:0.5px dashed #2e2e2b;background:transparent;"
                    onmouseover="this.style.borderColor='#d97706';this.style.color='#d97706'"
                    onmouseout="this.style.borderColor='#2e2e2b';this.style.color='#9e9890'">
                    <Icons name="plus" class="w-3.5 h-3.5" />
                    Add another variant
                </button>
            </div>

            <!-- Footer -->
            <div class="flex items-center gap-3 px-5 py-4 shrink-0"
                style="border-top:0.5px solid #2e2e2b;">
                <button type="button" @click="saveLinkProduct"
                    class="flex-1 py-2.5 rounded-lg text-sm font-medium"
                    style="background:#d97706;color:#111110;">
                    Save {{ rows.length }} variant{{ rows.length > 1 ? 's' : '' }}
                </button>
                <button type="button" @click="closeForm"
                    class="px-4 py-2.5 rounded-lg text-sm"
                    style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                    onmouseover="this.style.color='white'"
                    onmouseout="this.style.color='#9e9890'">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</Transition>

<!-- Edit Modal -->
<Transition name="fade">
    <div v-if="showForm && editTarget"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="background:rgba(0,0,0,0.75);"
        @click.self="closeForm">

        <div class="rounded-xl w-full max-w-lg"
            style="background:#1c1c1a;border:0.5px solid #2e2e2b;
                   max-height:90vh;display:flex;flex-direction:column;">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 shrink-0"
                style="border-bottom:0.5px solid #2e2e2b;">
                <div>
                    <h2 class="text-sm font-medium" style="color:white;">Edit Variant</h2>
                    <p class="text-xs mt-0.5 font-mono" style="color:#d97706;">
                        {{ editTarget.sku }}
                    </p>
                </div>
                <button @click="closeForm" style="color:#6b6660;">
                    <Icons name="x" class="w-4 h-4" />
                </button>
            </div>

            <!-- Body -->
            <form @submit.prevent="saveLinkProduct"
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
                            {{ p.name ?? '#' + p.id }}
                        </option>
                    </select>
                    <p v-if="form.errors.product_id" class="text-xs mt-1"
                        style="color:#ef4444;">{{ form.errors.product_id }}</p>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-xs mb-1.5" style="color:#6b6660;">
                        SKU <span style="color:#ef4444;">*</span>
                    </label>
                    <input v-model="form.sku" type="text"
                        placeholder="KRT-M-RED-001"
                        class="w-full rounded-lg px-3 py-2 text-sm outline-none font-mono uppercase"
                        style="background:#111110;color:#d97706;border:0.5px solid #2e2e2b;" />
                    <p v-if="form.errors.sku" class="text-xs mt-1"
                        style="color:#ef4444;">{{ form.errors.sku }}</p>
                </div>

                <!-- MRP + Price -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs mb-1.5" style="color:#6b6660;">MRP (₹)</label>
                        <input v-model="form.mrp" type="number" min="0" step="0.01"
                            placeholder="0.00"
                            class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                            style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                    </div>
                    <div>
                        <label class="block text-xs mb-1.5" style="color:#6b6660;">
                            Price (₹) <span style="color:#ef4444;">*</span>
                        </label>
                        <input v-model="form.price" type="number" min="0" step="0.01"
                            placeholder="0.00"
                            class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                            style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                        <p v-if="form.errors.price" class="text-xs mt-1"
                            style="color:#ef4444;">{{ form.errors.price }}</p>
                    </div>
                </div>

                <!-- Qty -->
                <div>
                    <label class="block text-xs mb-1.5" style="color:#6b6660;">
                        Quantity <span style="color:#ef4444;">*</span>
                    </label>
                    <input v-model="form.qty" type="number" min="0"
                        placeholder="0"
                        class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                        style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                    <p v-if="form.errors.qty" class="text-xs mt-1"
                        style="color:#ef4444;">{{ form.errors.qty }}</p>
                </div>

                <!-- Size + Color -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs mb-1.5" style="color:#6b6660;">Size</label>
                        <select v-model="form.size_id"
                            class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                            style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                            <option value="">Select size</option>
                            <option v-for="s in sizes" :key="s.id" :value="s.id">
                                {{ s.size }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs mb-1.5" style="color:#6b6660;">Color</label>
                        <div class="flex items-center gap-2">
                            <select v-model="form.color_id"
                                class="flex-1 rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                                <option value="">Select color</option>
                                <option v-for="c in colors" :key="c.id" :value="c.id">
                                    {{ c.color_name }}
                                </option>
                            </select>
                            <div class="w-8 h-8 rounded-full shrink-0"
                                :style="`background:${form.color_id
                                    ? colorHex(form.color_id)
                                    : '#2e2e2b'};
                                    border:0.5px solid #2e2e2b;`">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-xs mb-1.5" style="color:#6b6660;">Image</label>
                    <MediaSelector
                        v-model="form.media_id"
                        v-model:previewFile="form.previewFile"
                    />
                </div>

                <!-- Footer -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" :disabled="form.processing"
                        class="flex-1 py-2.5 rounded-lg text-sm font-medium"
                        style="background:#d97706;color:#111110;"
                        :style="form.processing ? 'opacity:0.6' : ''">
                        {{ form.processing ? 'Updating...' : 'Update Variant' }}
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
