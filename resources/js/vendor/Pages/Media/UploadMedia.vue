<!-- resources/js/vendor/Pages/Media/UploadMedia.vue -->

<script setup>
import { VendorLayout } from "..";
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { useMedia } from "@/vendor/Back";

const { saveMedia } = useMedia();

const previews = ref([]);

const form = useForm({
    media: [null],
    tags: [""],
    description: [""],
});

const addMediaRow = () => {
    form.media.push(null);
    form.tags.push("");
    form.description.push("");
    previews.value.push(null);
};

const removeMediaRow = (index) => {
    if (form.media.length <= 1) {
        alert("At least one media row is required");
        return;
    }

    form.media.splice(index, 1);
    form.tags.splice(index, 1);
    form.description.splice(index, 1);
    previews.value.splice(index, 1);
};

const handleFileChange = (event, index) => {
    const file = event.target.files[0];

    form.media[index] = file;

    if (file) {
        previews.value[index] = URL.createObjectURL(file);
    } else {
        previews.value[index] = null;
    }
};

const submit = () => {
    const hasFile = form.media.some((file) => file);

    if (!hasFile) {
        alert("Please select at least one media file.");
        return;
    }

    const data = new FormData();

    form.media.forEach((file, index) => {
        if (file) {
            data.append(`media[${index}]`, file);
        }

        data.append(`tags[${index}]`, form.tags[index] || "");
        data.append(
            `description[${index}]`,
            form.description[index] || ""
        );
    });

    saveMedia(data);
};
</script>

<template>
    <VendorLayout>
        <div class="max-w-7xl mx-auto p-6">

            <div class="bg-white rounded-xl shadow border">

                <!-- Header -->
                <div
                    class="border-b px-6 py-4 flex items-center justify-between"
                >
                    <h1 class="text-2xl font-bold">
                        Upload Media
                    </h1>

                    <button
                        type="button"
                        @click="router.get('/vendor/media')"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100"
                    >
                        ← Back
                    </button>
                </div>

                <!-- Form -->
                <form
                    @submit.prevent="submit"
                    class="p-6"
                >

                    <div
                        v-for="(item, index) in form.media"
                        :key="index"
                        class="border rounded-xl p-4 mb-4"
                    >
                        <div
                            class="grid md:grid-cols-12 gap-4 items-center"
                        >

                            <!-- Preview -->
                            <div class="md:col-span-2">
                                <div
                                    class="h-24 border rounded flex items-center justify-center overflow-hidden bg-gray-50"
                                >
                                    <img
                                        v-if="previews[index]"
                                        :src="previews[index]"
                                        class="w-full h-full object-cover"
                                    />

                                    <span
                                        v-else
                                        class="text-gray-400 text-sm"
                                    >
                                        No Preview
                                    </span>
                                </div>
                            </div>

                            <!-- File -->
                            <div class="md:col-span-4">
                                <label
                                    class="block mb-2 font-medium"
                                >
                                    Media File
                                </label>

                                <input
                                    type="file"
                                    required
                                    accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi"
                                    class="w-full border rounded-lg p-2"
                                    @change="
                                        handleFileChange(
                                            $event,
                                            index
                                        )
                                    "
                                />

                                <div
                                    v-if="form.errors[`media.${index}`]"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{
                                        form.errors[
                                            `media.${index}`
                                        ]
                                    }}
                                </div>
                            </div>

                            <!-- Tag -->
                            <div class="md:col-span-2">
                                <label
                                    class="block mb-2 font-medium"
                                >
                                    Tag
                                </label>

                                <input
                                    v-model="form.tags[index]"
                                    maxlength="10"
                                    placeholder="Max 10 chars"
                                    class="w-full border rounded-lg p-2"
                                />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-3">
                                <label
                                    class="block mb-2 font-medium"
                                >
                                    Description
                                </label>

                                <input
                                    v-model="
                                        form.description[index]
                                    "
                                    placeholder="Description"
                                    class="w-full border rounded-lg p-2"
                                />
                            </div>

                            <!-- Remove -->
                            <div class="md:col-span-1">
                                <button
                                    v-if="form.media.length > 1"
                                    type="button"
                                    @click="
                                        removeMediaRow(index)
                                    "
                                    class="w-full bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-2"
                                >
                                    ×
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Add More -->
                    <div class="flex justify-start">
                        <button
                            type="button"
                            @click="addMediaRow"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-100"
                        >
                            + Add More Media
                        </button>
                    </div>

                    <!-- Submit -->
                    <div
                        class="mt-6 flex justify-end"
                    >
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-black text-white px-6 py-3 rounded-lg disabled:opacity-50"
                        >
                            {{
                                form.processing
                                    ? "Uploading..."
                                    : "Upload Media"
                            }}
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </VendorLayout>
</template>
