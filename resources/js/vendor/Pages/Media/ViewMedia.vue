<!-- resources/js/vendor/Media/ViewMedia.vue -->

<script setup>
import {VendorLayout} from "..";
import { Link } from "@inertiajs/vue3";
import { useMedia } from "@/vendor/Back";

const { media } = useMedia();
</script>

<template>
    <VendorLayout>
        <div class="max-w-7xl mx-auto p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">
                    Media Library
                </h1>

                <Link
                    href="/vendor/media/upload"
                    class="px-4 py-2 bg-black text-white rounded-lg"
                >
                    + Upload Media
                </Link>
            </div>

            <!-- Media Grid -->
            <div
                v-if="media.length"
                class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4"
            >
                <div
                    v-for="item in media"
                    :key="item.id"
                    class="border rounded-lg overflow-hidden bg-white"
                >
                    <img
                        :src="`/storage/media/${item.file_name}`"
                        class="w-full h-40 object-cover"
                    />

                    <div class="p-2">
                        <p class="text-sm font-medium">
                            {{ item.tags }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ item.media_type }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                v-else
                class="text-center py-12 text-gray-500"
            >
                No media found.
            </div>

        </div>
    </VendorLayout>
</template>
