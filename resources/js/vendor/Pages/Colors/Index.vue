<!-- resources/js/vendor/Pages/Colors/Index.vue -->
<script setup>
import VendorLayout from '../../Layouts/VendorLayout.vue'
import { Icons }    from '@/vendor/Components/index'
import useColors    from '@/vendor/Back/composables/useColors'

const {
    displayed, deletedData,
    showDeleted, showForm, editTarget,
    selected, bulkAction, form,
    openCreate, openEdit, closeForm,
    saveColor, toggleStatus,
    deleteColor, restoreColor, forceDeleteColor,
    toggleAll, applyBulk,
} = useColors()
</script>

<template>
    <VendorLayout>
        <div class="space-y-5">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium" style="color:white;">Colors</h1>
                <button @click="openCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium"
                    style="background:#d97706;color:#111110;">
                    <Icons name="plus" class="w-3.5 h-3.5" /> Add Color
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
                    <table class="w-full text-sm min-w-[460px]">
                        <thead style="background:#1c1c1a;">
                            <tr>
                                <th class="px-4 py-3 text-left w-8">
                                    <input type="checkbox"
                                        @change="(e) => toggleAll(e.target.checked)" />
                                </th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">ID</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Preview</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Color</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Hex</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Status</th>
                                <th class="px-4 py-3 text-left text-xs" style="color:#6b6660;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="color in displayed" :key="color.id"
                                style="border-top:0.5px solid #2e2e2b;">

                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                        :value="color.id" v-model="selected" />
                                </td>

                                <td class="px-4 py-3 text-xs" style="color:#6b6660;">
                                    #{{ color.id }}
                                </td>

                                <!-- Color preview circle -->
                                <td class="px-4 py-3">
                                    <div class="w-7 h-7 rounded-full"
                                        :style="`background:${color.hex_id};
                                                 border:0.5px solid #2e2e2b;
                                                 ${showDeleted ? 'filter:grayscale(100%)' : ''}`">
                                    </div>
                                </td>

                                <!-- Name -->
                                <td class="px-4 py-3 text-sm"
                                    :style="showDeleted
                                        ? 'color:#6b6660;text-decoration:line-through;'
                                        : 'color:white;'">
                                    {{ color.color_name }}
                                </td>

                                <!-- Hex -->
                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono px-2 py-0.5 rounded"
                                        :style="`background:${color.hex_id}22;
                                                 color:${color.hex_id};
                                                 border:0.5px solid ${color.hex_id}44;`">
                                        {{ color.hex_id }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    <button @click="toggleStatus(color.id)"
                                        class="px-2 py-0.5 rounded text-xs"
                                        :style="color.status
                                            ? 'background:#1a2e1a;color:#4ade80;'
                                            : 'background:#2e1a1a;color:#f87171;'">
                                        {{ color.status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3">
                                    <div v-if="!showDeleted" class="flex items-center gap-2">
                                        <button @click="openEdit(color)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.color='white'"
                                            onmouseout="this.style.color='#9e9890'">
                                            <Icons name="edit" class="w-3.5 h-3.5" /> Edit
                                        </button>
                                        <button @click="deleteColor(color.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#280e0e'"
                                            onmouseout="this.style.background='transparent'">
                                            <Icons name="trash" class="w-3.5 h-3.5" /> Delete
                                        </button>
                                    </div>
                                    <div v-else class="flex items-center gap-2">
                                        <button @click="restoreColor(color.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#4ade80;border:0.5px solid #2e2e2b;background:transparent;"
                                            onmouseover="this.style.background='#1a2e1a'"
                                            onmouseout="this.style.background='transparent'">
                                            Restore
                                        </button>
                                        <button @click="forceDeleteColor(color.id)"
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
                                    {{ showDeleted ? 'No deleted colors.' : 'No colors found.' }}
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
                            {{ editTarget ? 'Edit Color' : 'Add Color' }}
                        </h2>
                        <button @click="closeForm" style="color:#6b6660;">
                            <Icons name="x" class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Body -->
                    <form @submit.prevent="saveColor" class="p-5 space-y-4">

                        <input type="hidden" v-model="form.id" />

                        <!-- Color Name -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Color name <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.color_name" type="text"
                                placeholder="e.g. Crimson Red"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                            <p v-if="form.errors.color_name" class="text-xs mt-1"
                                style="color:#ef4444;">
                                {{ form.errors.color_name }}
                            </p>
                        </div>

                        <!-- Hex picker -->
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Hex color <span style="color:#ef4444;">*</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <!-- Native color picker -->
                                <input v-model="form.hex_id" type="color"
                                    class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0.5"
                                    style="background:#111110;border:0.5px solid #2e2e2b;" />

                                <!-- Hex text input -->
                                <input v-model="form.hex_id" type="text"
                                    placeholder="#000000"
                                    class="flex-1 rounded-lg px-3 py-2 text-sm outline-none font-mono uppercase"
                                    style="background:#111110;border:0.5px solid #2e2e2b;"
                                    :style="`color:${form.hex_id};`" />

                                <!-- Live preview -->
                                <div class="w-9 h-9 rounded-full shrink-0"
                                    :style="`background:${form.hex_id};
                                             border:0.5px solid #2e2e2b;`">
                                </div>
                            </div>
                            <p v-if="form.errors.hex_id" class="text-xs mt-1"
                                style="color:#ef4444;">
                                {{ form.errors.hex_id }}
                            </p>
                        </div>

                        <!-- Edit indicator -->
                        <div v-if="editTarget"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs"
                            style="background:#1a1a2e;color:#818cf8;border:0.5px solid #2e2e2b;">
                            <Icons name="edit" class="w-3.5 h-3.5" />
                            Editing
                            <span class="font-medium" style="color:white;">
                                {{ editTarget.color_name }}
                            </span>
                            <div class="w-3.5 h-3.5 rounded-full ml-1"
                                :style="`background:${editTarget.hex_id};`">
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit" :disabled="form.processing"
                                class="flex-1 py-2.5 rounded-lg text-sm font-medium"
                                style="background:#d97706;color:#111110;"
                                :style="form.processing ? 'opacity:0.6' : ''">
                                {{ form.processing
                                    ? 'Saving...'
                                    : editTarget ? 'Update Color' : 'Create Color' }}
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
