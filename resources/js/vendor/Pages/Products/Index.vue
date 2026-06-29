<!-- resources/js/Vendor/Pages/Products/Index.vue -->
<script setup>
import { VendorLayout } from '..';
import { useProducts } from '@/vendor/Back';
import { Icons }          from '@/vendor/Components'
const {
    products, displayed, deletedData,
    showDeleted, selected, bulkAction,
    goToCreate, editProduct, deleteProduct,
    restoreProduct, forceDeleteProduct, toggleStatus,
    toggleAll, applyBulk,
} = useProducts();
</script>

<template>
<VendorLayout>
<div class="space-y-5">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium" style="color:white;">My Products</h1>
                <button @click="goToCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium"
                    style="background:#d97706;color:#111110;">
                    <Icons name="plus" class="w-3.5 h-3.5" /> Add Product
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
                    <table class="w-full text-sm min-w-[760px]">
                        <thead style="background:#1c1c1a;">
                            <tr>
                                <th class="px-4 py-3 text-left w-8">
                                    <input type="checkbox"
                                        @change="(e) => toggleAll(e.target.checked)" />
                                </th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">ID</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Image</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Name</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Category</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Brand</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Price</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Status</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in displayed" :key="product.id"
                                style="border-top:0.5px solid #2e2e2b;">

                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                        :value="product.id" v-model="selected" />
                                </td>

                                <td class="px-4 py-3 text-xs" style="color:#6b6660;">
                                    #{{ product.id }}
                                </td>

                                <!-- Image -->
                                <td class="px-4 py-3">
                                    <img v-if="product.file_name"
                                        :src="`/storage/media/${product.file_name}`"
                                        :alt="product.name"
                                        class="w-10 h-10 rounded-lg object-cover"
                                        :style="showDeleted ? 'filter:grayscale(100%)' : ''" />
                                    <div v-else
                                        class="w-10 h-10 rounded-lg flex items-center justify-center"
                                        style="background:#1c1c1a;">
                                        <Icons name="product" class="w-4 h-4" style="color:#6b6660;" />
                                    </div>
                                </td>

                                <!-- Name -->
                                <td class="px-4 py-3 text-sm"
                                    :style="showDeleted
                                        ? 'color:#6b6660;text-decoration:line-through;'
                                        : 'color:white;'">
                                    {{ product.name }}
                                </td>

                                <!-- Category -->
                                <td class="px-4 py-3 text-xs" style="color:#9e9890;">
                                    {{ product.category_name ?? '—' }}
                                </td>

                                <!-- Brand -->
                                <td class="px-4 py-3 text-xs" style="color:#9e9890;">
                                    {{ product.brand_name ?? '—' }}
                                </td>

                                <!-- Price -->
                                <td class="px-4 py-3 text-sm font-medium" style="color:white;">
                                    ₹{{ product.price }}
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    <button @click="toggleStatus(product.id)"
                                        class="px-2 py-0.5 rounded text-xs"
                                        :style="product.status
                                            ? 'background:#1a2e1a;color:#4ade80;'
                                            : 'background:#2e1a1a;color:#f87171;'">
                                        {{ product.status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3">
                                    <div v-if="!showDeleted" class="flex items-center gap-2">
                                        <button @click="editProduct(product.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.color='white'"
                                            onmouseout="this.style.color='#9e9890'">
                                            <Icons name="edit" class="w-3.5 h-3.5" /> Edit
                                        </button>
                                        <button @click="deleteProduct(product.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#280e0e'"
                                            onmouseout="this.style.background='transparent'">
                                            <Icons name="trash" class="w-3.5 h-3.5" /> Delete
                                        </button>
                                    </div>
                                    <div v-else class="flex items-center gap-2">
                                        <button @click="restoreProduct(product.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#4ade80;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#1a2e1a'"
                                            onmouseout="this.style.background='transparent'">
                                            Restore
                                        </button>
                                        <button @click="forceDeleteProduct(product.id)"
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
                                    {{ showDeleted ? 'No deleted products.' : 'No products found.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</VendorLayout>
</template>
