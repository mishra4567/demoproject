<!-- resources/js/Pages/Vendor/Products/ManageProduct.vue -->
<script setup>
import { ref, computed }     from 'vue'
import { router, useForm }   from '@inertiajs/vue3'
import VendorLayout          from '../../Layouts/VendorLayout.vue'
import { MediaModal }        from '../Media/'
import { useProducts }       from '@/vendor/Back'

const { categories, brands, coupons, media, product, isEdit, saveProduct } = useProducts()

const p = product.value ?? {}

// ── Form ─────────────────────────────────────────────────────────────────────
const form = useForm({
  id:                      p.id                      ?? 0,
  name:                    p.name                    ?? '',
  slug:                    p.slug                    ?? '',
  category_id:             p.category_id             ?? '',
  brand_id:                p.brand                   ?? '',
  model:                   p.model                   ?? '',
  price:                   p.price                   ?? '',
  mrp:                     p.mrp                     ?? '',
  media_id:                p.media_id                ?? '',
  coupon_id:               p.coupon_id               ?? '',
  short_desc:              p.short_desc              ?? '',
  desc:                    p.desc                    ?? '',
  keywords:                p.keywords                ?? '',
  technical_specification: p.technical_specification ?? '',
  uses:                    p.uses                    ?? '',
  warranty:                p.warranty                ?? '',
  gallery_media_ids:       (p.gallery_images ?? []).map(g => g.id),
})

// ── Auto slug ────────────────────────────────────────────────────────────────
const generateSlug = () => {
  form.slug = form.name
    .toLowerCase().trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
}

// ── Product image ─────────────────────────────────────────────────────────────
const imagePreview = ref(p.image ? `/storage/media/${p.image}` : null)

// ── Gallery ───────────────────────────────────────────────────────────────────
const galleryPreviews = ref([...(p.gallery_images ?? [])])

const removeGallery = (id) => {
  form.gallery_media_ids = form.gallery_media_ids.filter(i => i !== id)
  galleryPreviews.value  = galleryPreviews.value.filter(i => i.id !== id)
}

// ── Media modal ───────────────────────────────────────────────────────────────
const showModal  = ref(false)
const modalMode  = ref('single')  // 'single' | 'gallery'

const openProductMedia = () => {
  modalMode.value = 'single'
  showModal.value = true
}

const openGalleryMedia = () => {
  modalMode.value = 'gallery'
  showModal.value = true
}

const onMediaSelect = (item) => {
  if (modalMode.value === 'single') {
    form.media_id      = item.id
    imagePreview.value = `/storage/media/${item.file_name}`
  } else {
    // gallery — avoid duplicates
    if (!form.gallery_media_ids.includes(item.id)) {
      form.gallery_media_ids.push(item.id)
      galleryPreviews.value.push({ id: item.id, file_name: item.file_name })
    }
  }
}

// ── Submit ────────────────────────────────────────────────────────────────────
const submit = () => saveProduct(form)
</script>

<template>
  <VendorLayout>
    <div class="max-w-4xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">
          {{ isEdit ? 'Edit Product' : 'Add Product' }}
        </h1>
        <button @click="router.get('/vendor/products')"
          class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-50 transition">
          ← Back
        </button>
      </div>

      <!-- Validation errors -->
      <div v-if="Object.keys(form.errors).length"
        class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-600 space-y-1">
        <p v-for="(err, field) in form.errors" :key="field">
          <strong class="capitalize">{{ field.replace('_', ' ') }}:</strong> {{ err }}
        </p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">

        <!-- ── Basic Info ── -->
        <div class="bg-white rounded-xl border shadow-sm p-6 space-y-4">
          <h2 class="font-semibold border-b pb-2">Basic Information</h2>

          <div>
            <label class="block text-sm font-medium mb-1">Product Name <span class="text-red-500">*</span></label>
            <input v-model="form.name" @input="generateSlug" type="text"
              placeholder="Enter product name"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
              required />
            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Slug <span class="text-red-500">*</span></label>
            <input v-model="form.slug" type="text" placeholder="product-slug"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
              required />
            <p v-if="form.errors.slug" class="text-red-500 text-xs mt-1">{{ form.errors.slug }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Category</label>
              <select v-model="form.category_id"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <option value="">Select Category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.category_name }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Brand</label>
              <select v-model="form.brand_id"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <option value="">Select Brand</option>
                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                  {{ brand.name }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Model</label>
              <input v-model="form.model" type="text" placeholder="Product model"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Price (₹) <span class="text-red-500">*</span></label>
              <input v-model="form.price" type="number" min="0" step="0.01" placeholder="0.00"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                required />
              <p v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">MRP (₹)</label>
              <input v-model="form.mrp" type="number" min="0" step="0.01" placeholder="0.00"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Coupon</label>
            <select v-model="form.coupon_id"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
              <option value="">Select Coupon</option>
              <option v-for="coupon in coupons" :key="coupon.id" :value="coupon.id">
                {{ coupon.title }}
              </option>
            </select>
          </div>
        </div>

        <!-- ── Product Image ── -->
        <div class="bg-white rounded-xl border shadow-sm p-6 space-y-4">
          <h2 class="font-semibold border-b pb-2">Product Image</h2>
          <div class="flex items-start gap-6">
            <!-- Preview box -->
            <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg
              flex items-center justify-center overflow-hidden shrink-0 bg-gray-50">
              <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
              <span v-else class="text-xs text-gray-400 text-center px-2">No image</span>
            </div>
            <div class="space-y-2">
              <button type="button" @click="openProductMedia"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm
                  hover:bg-gray-50 transition flex items-center gap-2">
                <span>🖼</span> Select from Media Library
              </button>
              <p class="text-xs text-gray-400">Click to open media library</p>
              <p v-if="form.errors.media_id" class="text-red-500 text-xs">{{ form.errors.media_id }}</p>
            </div>
          </div>
        </div>

        <!-- ── Gallery ── -->
        <div class="bg-white rounded-xl border shadow-sm p-6 space-y-4">
          <h2 class="font-semibold border-b pb-2">Gallery Images</h2>
          <button type="button" @click="openGalleryMedia"
            class="px-4 py-2 border border-gray-300 rounded-lg text-sm
              hover:bg-gray-50 transition flex items-center gap-2">
            <span>+</span> Add Gallery Image
          </button>

          <div class="flex flex-wrap gap-3 mt-2">
            <div v-for="img in galleryPreviews" :key="img.id"
              class="relative w-24 h-24 rounded-lg overflow-hidden border group">
              <img :src="`/storage/media/${img.file_name}`"
                class="w-full h-full object-cover" />
              <button type="button" @click="removeGallery(img.id)"
                class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full
                  text-xs items-center justify-center hidden group-hover:flex">
                ✕
              </button>
            </div>
            <p v-if="!galleryPreviews.length" class="text-sm text-gray-400">
              No gallery images added.
            </p>
          </div>
        </div>

        <!-- ── Descriptions ── -->
        <div class="bg-white rounded-xl border shadow-sm p-6 space-y-4">
          <h2 class="font-semibold border-b pb-2">Descriptions</h2>

          <div v-for="field in [
            { key: 'short_desc',              label: 'Short Description',        rows: 3, placeholder: 'Brief product description...' },
            { key: 'desc',                    label: 'Full Description',          rows: 5, placeholder: 'Detailed product description...' },
            { key: 'keywords',               label: 'Keywords',                 rows: 2, placeholder: 'keyword1, keyword2, keyword3' },
            { key: 'technical_specification', label: 'Technical Specification',  rows: 4, placeholder: 'Technical details...' },
            { key: 'uses',                   label: 'Uses',                     rows: 3, placeholder: 'How to use this product...' },
            { key: 'warranty',               label: 'Warranty',                 rows: 2, placeholder: 'Warranty information...' },
          ]" :key="field.key">
            <div>
              <label class="block text-sm font-medium mb-1">{{ field.label }}</label>
              <textarea
                v-model="form[field.key]"
                :rows="field.rows"
                :placeholder="field.placeholder"
                class="w-full border rounded-lg px-3 py-2 text-sm
                  focus:outline-none focus:ring-2 focus:ring-black resize-y"
              />
              <p v-if="form.errors[field.key]" class="text-red-500 text-xs mt-1">
                {{ form.errors[field.key] }}
              </p>
            </div>
          </div>
        </div>

        <!-- Hidden ID -->
        <input type="hidden" v-model="form.id" />

        <!-- Submit -->
        <button type="submit" :disabled="form.processing"
          class="w-full py-3 bg-black text-white rounded-xl font-medium
            hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed">
          {{ form.processing ? 'Saving...' : (isEdit ? 'Update Product' : 'Create Product') }}
        </button>

      </form>
    </div>

    <!-- Media Modal -->
    <MediaModal
      :show="showModal"
      :media="media"
      :mode="modalMode"
      @close="showModal = false"
      @select="onMediaSelect"
    />

  </VendorLayout>
</template>
