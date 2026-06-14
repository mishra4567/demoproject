<!-- resources/js/vendor/Pages/Categories/Index.vue -->
<script setup>
import {VendorLayout} from '..'
import { Icons }    from '@/vendor/Components'
import { useCategories }    from '@/vendor/Back'

const {
    displayed, deletedData, parents,
    showDeleted, showForm, editTarget,
    selected, bulkAction, form,
    generateSlug,
    openCreate, openEdit, closeForm,
    saveCategory, toggleStatus,
    deleteCategory, restoreCategory, forceDeleteCategory,
    toggleAll, applyBulk,
} = useCategories()
</script>

<template>
    <VendorLayout>
        <div class="space-y-5">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium" style="color:white;">Categories</h1>
                <button @click="openCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium"
                    style="background:#d97706;color:#111110;">
                    <Icons name="plus" class="w-3.5 h-3.5" /> Add Category
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
                    <table class="w-full text-sm min-w-[500px]">
                        <thead style="background:#1c1c1a;">
                            <tr>
                                <th class="px-4 py-3 text-left w-8">
                                    <input type="checkbox"
                                        @change="(e) => toggleAll(e.target.checked)" />
                                </th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">ID</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Name</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Slug</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Parent</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Status</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cat in displayed" :key="cat.id"
                                style="border-top:0.5px solid #2e2e2b;">

                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                        :value="cat.id" v-model="selected" />
                                </td>

                                <td class="px-4 py-3 text-xs" style="color:#6b6660;">
                                    #{{ cat.id }}
                                </td>

                                <td class="px-4 py-3 text-sm"
                                    :style="showDeleted
                                        ? 'color:#6b6660;text-decoration:line-through;'
                                        : 'color:white;'">
                                    {{ cat.category_name }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono" style="color:#6b6660;">
                                        {{ cat.category_slug }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-xs" style="color:#9e9890;">
                                    {{ parents.find(p => p.id === cat.parent_id)?.category_name ?? '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    <button @click="toggleStatus(cat.id)"
                                        class="px-2 py-0.5 rounded text-xs"
                                        :style="cat.status
                                            ? 'background:#1a2e1a;color:#4ade80;'
                                            : 'background:#2e1a1a;color:#f87171;'">
                                        {{ cat.status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>

                                <td class="px-4 py-3">
                                    <div v-if="!showDeleted" class="flex items-center gap-2">
                                        <button @click="openEdit(cat)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.color='white'"
                                            onmouseout="this.style.color='#9e9890'">
                                            <Icons name="edit" class="w-3.5 h-3.5" /> Edit
                                        </button>
                                        <button @click="deleteCategory(cat.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#280e0e'"
                                            onmouseout="this.style.background='transparent'">
                                            <Icons name="trash" class="w-3.5 h-3.5" /> Delete
                                        </button>
                                    </div>
                                    <div v-else class="flex items-center gap-2">
                                        <button @click="restoreCategory(cat.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#4ade80;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#1a2e1a'"
                                            onmouseout="this.style.background='transparent'">
                                            Restore
                                        </button>
                                        <button @click="forceDeleteCategory(cat.id)"
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
                                <td colspan="7" class="px-4 py-12 text-center text-sm"
                                    style="color:#6b6660;">
                                    {{ showDeleted ? 'No deleted categories.' : 'No categories found.' }}
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

                <div class="rounded-xl w-full max-w-sm"
                    style="background:#1c1c1a;border:0.5px solid #2e2e2b;">

                    <!-- Header -->
                    <div class="flex items-center justify-between px-5 py-4"
                        style="border-bottom:0.5px solid #2e2e2b;">
                        <h2 class="text-sm font-medium" style="color:white;">
                            {{ editTarget ? 'Edit Category' : 'Add Category' }}
                        </h2>
                        <button @click="closeForm" style="color:#6b6660;">
                            <Icons name="x" class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Body -->
                    <form @submit.prevent="saveCategory" class="p-5 space-y-4">

                        <input type="hidden" v-model="form.id" />

                        <!-- Name -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Category name <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.category_name"
                                @input="generateSlug"
                                type="text" placeholder="e.g. Electronics"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                            <p v-if="form.errors.category_name" class="text-xs mt-1"
                                style="color:#ef4444;">
                                {{ form.errors.category_name }}
                            </p>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Slug <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.category_slug"
                                type="text" placeholder="e.g. electronics"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none font-mono"
                                style="background:#111110;color:#9e9890;border:0.5px solid #2e2e2b;" />
                            <p v-if="form.errors.category_slug" class="text-xs mt-1"
                                style="color:#ef4444;">
                                {{ form.errors.category_slug }}
                            </p>
                        </div>

                        <!-- Parent -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Parent category
                            </label>
                            <select v-model="form.parent_id"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                                <option :value="0">Main category</option>
                                <option v-for="p in parents" :key="p.id" :value="p.id">
                                    {{ p.category_name }}
                                </option>
                            </select>
                        </div>

                        <!-- Edit indicator -->
                        <div v-if="editTarget"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs"
                            style="background:#1a1a2e;color:#818cf8;border:0.5px solid #2e2e2b;">
                            <Icons name="edit" class="w-3.5 h-3.5" />
                            Editing
                            <span class="font-medium" style="color:white;">
                                {{ editTarget.category_name }}
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
                                    : editTarget ? 'Update Category' : 'Create Category' }}
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
