<script setup>
import { VendorLayout } from '..'
import { Icons } from '@/vendor/Components'
import { useSizes } from '@/vendor/Back'

const {
    displayed,deletedData,showDeleted,
    showForm,editTarget,selected,
    bulkAction,form, openCreate,
    openEdit, closeForm, saveSize,
    toggleStatus,deleteSize,restoreSize,
    forceDeleteSize, toggleAll, applyBulk,
} = useSizes()
</script>

<template>
    <VendorLayout>
        <div class="space-y-5">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1
                    class="text-xl font-medium"
                    style="color:white;"
                >
                    Sizes
                </h1>

                <button
                    @click="openCreate"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium"
                    style="background:#d97706;color:#111110;"
                >
                    <Icons name="plus" class="w-3.5 h-3.5" />
                    Add Size
                </button>
            </div>

            <!-- Toolbar -->
            <div class="flex items-center justify-between gap-3 flex-wrap">

                <button
                    @click="showDeleted = !showDeleted"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs"
                    :style="
                        showDeleted
                            ? 'background:#280e0e;color:#ef4444;border:0.5px solid #ef4444;'
                            : 'background:#1c1c1a;color:#9e9890;border:0.5px solid #2e2e2b;'
                    "
                >
                    <Icons name="trash" class="w-3.5 h-3.5" />

                    {{ showDeleted ? 'Show Active' : 'Show Deleted' }}

                    <span
                        v-if="deletedData.length"
                        class="px-1.5 py-0.5 rounded-full text-xs"
                        style="background:#ef4444;color:white;line-height:1;"
                    >
                        {{ deletedData.length }}
                    </span>
                </button>

                <div class="flex items-center gap-2">

                    <select
                        v-model="bulkAction"
                        class="text-xs rounded-lg px-3 py-1.5 outline-none"
                        style="background:#1c1c1a;color:#9e9890;border:0.5px solid #2e2e2b;"
                    >
                        <option value="">
                            Bulk Action
                        </option>

                        <template v-if="!showDeleted">
                            <option value="activate">
                                Activate
                            </option>

                            <option value="deactivate">
                                Deactivate
                            </option>

                            <option value="trash">
                                Move to Trash
                            </option>
                        </template>

                        <template v-else>
                            <option value="restore">
                                Restore
                            </option>

                            <option value="permanent_delete">
                                Delete Forever
                            </option>
                        </template>
                    </select>

                    <button
                        @click="applyBulk"
                        class="px-3 py-1.5 rounded-lg text-xs"
                        style="background:#1c1c1a;color:white;border:0.5px solid #2e2e2b;"
                    >
                        Apply
                    </button>

                </div>
            </div>

            <!-- Table -->
            <div
                class="rounded-xl overflow-hidden"
                style="border:0.5px solid #2e2e2b;"
            >
                <div class="overflow-x-auto">

                    <table class="w-full text-sm min-w-[700px]">

                        <thead style="background:#1c1c1a;">
                            <tr>

                                <th class="px-4 py-3 text-left w-8">
                                    <input
                                        type="checkbox"
                                        @change="(e) => toggleAll(e.target.checked)"
                                    >
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs"
                                    style="color:#6b6660;"
                                >
                                    ID
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs"
                                    style="color:#6b6660;"
                                >
                                    Size
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs"
                                    style="color:#6b6660;"
                                >
                                    Type
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs"
                                    style="color:#6b6660;"
                                >
                                    Details
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs"
                                    style="color:#6b6660;"
                                >
                                    Status
                                </th>

                                <th
                                    class="px-4 py-3 text-left text-xs"
                                    style="color:#6b6660;"
                                >
                                    Actions
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            <tr
                                v-for="item in displayed"
                                :key="item.id"
                                style="border-top:0.5px solid #2e2e2b;"
                            >

                                <td class="px-4 py-3">
                                    <input
                                        type="checkbox"
                                        :value="item.id"
                                        v-model="selected"
                                    >
                                </td>

                                <td
                                    class="px-4 py-3 text-xs"
                                    style="color:#6b6660;"
                                >
                                    #{{ item.id }}
                                </td>

                                <td
                                    class="px-4 py-3 text-sm font-medium"
                                    style="color:white;"
                                >
                                    {{ item.size }}
                                </td>
                                <td
                                    class="px-4 py-3 text-sm font-medium"
                                    style="color:white;"
                                >
                                    {{ item.type }}
                                </td>
                                <td
                                    class="px-4 py-3 text-sm font-medium"
                                    style="color:white;"
                                >
                                    {{ item.details }}
                                </td>

                                <td class="px-4 py-3">
                                    <button
                                        @click="toggleStatus(item.id)"
                                        class="px-2 py-0.5 rounded text-xs"
                                        :style="
                                            item.status
                                                ? 'background:#1a2e1a;color:#4ade80;'
                                                : 'background:#2e1a1a;color:#f87171;'
                                        "
                                    >
                                        {{ item.status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>

                                <td class="px-4 py-3">

                                    <div
                                        v-if="!showDeleted"
                                        class="flex items-center gap-2"
                                    >
                                        <button
                                            @click="openEdit(item)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#9e9890;border:0.5px solid #2e2e2b;"
                                        >
                                            <Icons
                                                name="edit"
                                                class="w-3.5 h-3.5"
                                            />
                                            Edit
                                        </button>

                                        <button
                                            @click="deleteSize(item.id)"
                                            class="flex items-center gap-1 px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;"
                                        >
                                            <Icons
                                                name="trash"
                                                class="w-3.5 h-3.5"
                                            />
                                            Delete
                                        </button>
                                    </div>

                                    <div
                                        v-else
                                        class="flex items-center gap-2"
                                    >
                                        <button
                                            @click="restoreSize(item.id)"
                                            class="px-2 py-1 rounded text-xs"
                                            style="color:#4ade80;border:0.5px solid #2e2e2b;"
                                        >
                                            Restore
                                        </button>

                                        <button
                                            @click="forceDeleteSize(item.id)"
                                            class="px-2 py-1 rounded text-xs"
                                            style="color:#ef4444;border:0.5px solid #2e2e2b;"
                                        >
                                            Delete Forever
                                        </button>
                                    </div>

                                </td>

                            </tr>

                            <tr v-if="!displayed.length">
                                <td
                                    colspan="5"
                                    class="px-4 py-12 text-center text-sm"
                                    style="color:#6b6660;"
                                >
                                    {{
                                        showDeleted
                                            ? 'No deleted sizes found.'
                                            : 'No sizes found.'
                                    }}
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

        <!-- Modal -->
<Transition name="fade">

    <div
        v-if="showForm"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="background:rgba(0,0,0,0.75);"
        @click.self="closeForm"
    >

        <div
            class="rounded-xl w-full max-w-md"
            style="background:#1c1c1a;border:0.5px solid #2e2e2b;"
        >

            <!-- Header -->
            <div
                class="flex items-center justify-between px-5 py-4"
                style="border-bottom:0.5px solid #2e2e2b;"
            >
                <h2
                    class="text-sm font-medium"
                    style="color:white;"
                >
                    {{ editTarget ? 'Edit Size' : 'Add Size' }}
                </h2>

                <button
                    @click="closeForm"
                    style="color:#6b6660;"
                >
                    <Icons name="x" class="w-4 h-4" />
                </button>
            </div>

            <!-- Form -->
            <form
                @submit.prevent="saveSize"
                class="p-5 space-y-4"
            >

                <input
                    type="hidden"
                    v-model="form.id"
                >

                <!-- Type -->
                <div>
                    <label
                        class="block text-xs mb-1.5"
                        style="color:#6b6660;"
                    >
                        Type
                    </label>

                    <select
                        v-model="form.type"
                        class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                        style="background:#111110;color:white;border:0.5px solid #2e2e2b;"
                    >
                        <option value="">Select Type</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Shoes">Shoes</option>
                        <option value="Other">Other</option>
                    </select>

                    <p
                        v-if="form.errors.type"
                        class="text-xs mt-1"
                        style="color:#ef4444;"
                    >
                        {{ form.errors.type }}
                    </p>
                </div>

                <!-- Custom type (shown only when Type = Other) -->
                <div v-if="form.type === 'Other'">
                    <label
                        class="block text-xs mb-1.5"
                        style="color:#6b6660;"
                    >
                        Custom Type
                    </label>

                    <input
                        v-model="form.custom_type"
                        type="text"
                        placeholder="e.g. Watch, Helmet, Bag"
                        class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                        style="background:#111110;color:white;border:0.5px solid #2e2e2b;"
                    >

                    <p
                        v-if="form.errors.custom_type"
                        class="text-xs mt-1"
                        style="color:#ef4444;"
                    >
                        {{ form.errors.custom_type }}
                    </p>
                </div>

                <!-- Size -->
                <div>
                    <label
                        class="block text-xs mb-1.5"
                        style="color:#6b6660;"
                    >
                        Size <span style="color:#ef4444;">*</span>
                    </label>

                    <input
                        v-model="form.size"
                        type="text"
                        placeholder="Enter size"
                        class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                        style="background:#111110;color:white;border:0.5px solid #2e2e2b;"
                    >

                    <p
                        v-if="form.errors.size"
                        class="text-xs mt-1"
                        style="color:#ef4444;"
                    >
                        {{ form.errors.size }}
                    </p>
                </div>

                <!-- Details -->
                <div>
                    <label
                        class="block text-xs mb-1.5"
                        style="color:#6b6660;"
                    >
                        Details
                    </label>

                    <input
                        v-model="form.details"
                        type="text"
                        placeholder="e.g. Chest 46-48 inch"
                        class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                        style="background:#111110;color:white;border:0.5px solid #2e2e2b;"
                    >

                    <p
                        v-if="form.errors.details"
                        class="text-xs mt-1"
                        style="color:#ef4444;"
                    >
                        {{ form.errors.details }}
                    </p>
                </div>

                <div
                    v-if="editTarget"
                    class="text-xs px-3 py-2 rounded-lg"
                    style="background:#1a1a2e;color:#818cf8;"
                >
                    Editing:
                    <strong>{{ editTarget.size }}</strong>
                </div>

                <div class="flex gap-3">

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex-1 py-2.5 rounded-lg text-sm font-medium"
                        style="background:#d97706;color:#111110;"
                    >
                        {{
                            form.processing
                                ? 'Saving...'
                                : editTarget
                                    ? 'Update'
                                    : 'Create'
                        }}
                    </button>

                    <button
                        type="button"
                        @click="closeForm"
                        class="px-4 py-2.5 rounded-lg text-sm"
                        style="color:#9e9890;border:0.5px solid #2e2e2b;"
                    >
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
.fade-enter-active,
.fade-leave-active {
    transition: opacity .15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
