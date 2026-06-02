<!-- resources/js/Pages/Vendor/Products/Index.vue -->
<script setup>
import VendorLayout from '../../Layouts/VendorLayout.vue';
import { useProducts } from '@/vendor/Back/index.js';

const {
    products,
    pagination,
    goToPage,
    deleteProduct,
    editProduct,
    goToCreate,
} = useProducts();
</script>

<template>
  <VendorLayout>
    <div class="space-y-6">

      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">My Products</h1>

        <button
          @click="goToCreate"
          class="bg-black text-white px-4 py-2 rounded"
        >
          + Add Product
        </button>
      </div>
      <!-- Products -->
      <div
        v-if="products.length"
        class="grid grid-cols-1 md:grid-cols-3 gap-4"
      >
        <div
          v-for="product in products"
          :key="product.id"
            class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
        >
          <!-- Product Image -->
          <img
            :src="`/storage/media/${product.file_name}`"
            :alt="product.name"
            class="absolute inset-0 w-full h-full object-cover"
          />
            <div class="relative z-10 p-5 flex flex-col justify-end h-full">
                <h2 class="text-2xl font-bold text-white">
                    {{ product.name }}
                </h2>
                <p class="text-lg text-gray-200 mt-1">
                    ₹ {{ product.price }}
                </p>
                <div class="flex gap-3 mt-5">
                    <button
                    @click="editProduct(product.id)"
                    class="px-4 py-2 rounded-lg bg-white text-black font-medium hover:bg-gray-100 transition"
                    >
                    Edit
                    </button>
                    <button
                    @click="deleteProduct(product.id)"
                    class="px-4 py-2 rounded-lg border border-red-400 text-red-300 hover:bg-red-500 hover:text-white transition"
                    >
                    Delete
                    </button>
                </div>
            </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-else
        class="text-gray-400 text-center py-10"
      >
        No products found.
      </div>
    </div>
  </VendorLayout>
</template>
