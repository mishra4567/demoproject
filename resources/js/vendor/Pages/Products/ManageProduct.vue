<!-- resources/js/vendor/Pages/Products/ManageProduct.vue -->
<script setup>
import { ref }             from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import VendorLayout        from '../../Layouts/VendorLayout.vue'
import { Icons, MediaSelector } from '@/vendor/Components'
import { useProducts }     from '@/vendor/Back'

const { categories, brands, coupons, product, isEdit, saveProduct } = useProducts()

const p = product.value ?? {}

// ── Form ──────────────────────────────────────────────────────────────────────
const form = useForm({
    id:                      p.id                      ?? null,
    name:                    p.name                    ?? '',
    slug:                    p.slug                    ?? '',
    category_id:             p.category_id             ?? '',
    brand_id:                p.brand_id                ?? '',
    model:                   p.model                   ?? '',
    price:                   p.price                   ?? '',
    mrp:                     p.mrp                     ?? '',
    media_id:                p.media_id                ?? null,
    coupon_id:               p.coupon_id               ?? '',
    short_desc:              p.short_desc              ?? '',
    desc:                    p.desc                    ?? '',
    keywords:                p.keywords                ?? '',
    technical_specification: p.technical_specification ?? '',
    uses:                    p.uses                    ?? '',
    warranty:                p.warranty                ?? '',
    gallery_media_ids:       (p.gallery_images ?? []).map(g => g.id),
})

// ── Preview state ─────────────────────────────────────────────────────────────
const imagePreviewFile  = ref(p.image ?? null)
const galleryPreviews   = ref([...(p.gallery_images ?? [])])

// ── Auto slug ─────────────────────────────────────────────────────────────────
const generateSlug = () => {
    form.slug = form.name
        .toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
}

// ── Gallery ───────────────────────────────────────────────────────────────────
const galleryPreviewFile = ref(null)    // unused but required for MediaSelector v-model

function onGallerySelect(item) {       // ← receives full item object
    if (!form.gallery_media_ids.includes(item.id)) {
        form.gallery_media_ids.push(item.id)
        galleryPreviews.value.push({ id: item.id, file_name: item.file_name })
    }
}

function removeGallery(id) {
    form.gallery_media_ids = form.gallery_media_ids.filter(i => i !== id)
    galleryPreviews.value  = galleryPreviews.value.filter(i => i.id !== id)
}

// ── Submit ────────────────────────────────────────────────────────────────────
const submit = () => saveProduct(form)

// Description fields config
const descFields = [
    { key: 'short_desc',              label: 'Short description',       rows: 3, placeholder: 'Brief product description...' },
    { key: 'desc',                    label: 'Full description',         rows: 5, placeholder: 'Detailed product description...' },
    { key: 'keywords',                label: 'Keywords',                rows: 2, placeholder: 'keyword1, keyword2, keyword3' },
    { key: 'technical_specification', label: 'Technical specification', rows: 4, placeholder: 'Technical details...' },
    { key: 'uses',                    label: 'Uses',                    rows: 3, placeholder: 'How to use this product...' },
    { key: 'warranty',                label: 'Warranty',                rows: 2, placeholder: 'Warranty information...' },
]
</script>

<template>
    <VendorLayout>
        <div class="max-w-3xl mx-auto space-y-5">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-medium" style="color:white;">
                    {{ isEdit ? 'Edit Product' : 'Add Product' }}
                </h1>
                <button @click="router.get('/vendor/products')"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs"
                    style="color:#9e9890;border:0.5px solid #2e2e2b;background:transparent;"
                    onmouseover="this.style.color='white'"
                    onmouseout="this.style.color='#9e9890'">
                    <Icons name="chevron-left" class="w-3.5 h-3.5" /> Back
                </button>
            </div>

            <!-- Validation errors -->
            <div v-if="Object.keys(form.errors).length"
                class="rounded-lg p-4 text-xs space-y-1"
                style="background:#280e0e;border:0.5px solid #ef4444;color:#f87171;">
                <p v-for="(err, field) in form.errors" :key="field">
                    <span class="capitalize font-medium">{{ field.replace('_', ' ') }}:</span>
                    {{ err }}
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Basic Info -->
                <div class="rounded-xl p-5 space-y-4"
                    style="background:#1c1c1a;border:0.5px solid #2e2e2b;">
                    <h2 class="text-xs font-medium pb-2"
                        style="color:#6b6660;letter-spacing:0.06em;
                               border-bottom:0.5px solid #2e2e2b;">
                        BASIC INFORMATION
                    </h2>

                    <!-- Name + Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Product name <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.name" @input="generateSlug"
                                type="text" placeholder="e.g. Cotton Kurta"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;"
                                required />
                            <p v-if="form.errors.name" class="text-xs mt-1"
                                style="color:#ef4444;">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Slug <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.slug" type="text"
                                placeholder="product-slug"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:#9e9890;border:0.5px solid #2e2e2b;"
                                required />
                            <p v-if="form.errors.slug" class="text-xs mt-1"
                                style="color:#ef4444;">{{ form.errors.slug }}</p>
                        </div>
                    </div>

                    <!-- Category + Brand + Model -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">Category</label>
                            <select v-model="form.category_id"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                                <option value="">Select category</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.category_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">Brand</label>
                            <select v-model="form.brand_id"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                                <option value="">Select brand</option>
                                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                    {{ brand.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">Model</label>
                            <input v-model="form.model" type="text"
                                placeholder="e.g. XL-2024"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                        </div>
                    </div>

                    <!-- Price + MRP -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">
                                Price (₹) <span style="color:#ef4444;">*</span>
                            </label>
                            <input v-model="form.price" type="number"
                                min="0" step="0.01" placeholder="0.00"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;"
                                required />
                            <p v-if="form.errors.price" class="text-xs mt-1"
                                style="color:#ef4444;">{{ form.errors.price }}</p>
                        </div>
                        <div>
                            <label class="block text-xs mb-1.5" style="color:#6b6660;">MRP (₹)</label>
                            <input v-model="form.mrp" type="number"
                                min="0" step="0.01" placeholder="0.00"
                                class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                                style="background:#111110;color:white;border:0.5px solid #2e2e2b;" />
                        </div>
                    </div>

                    <!-- Coupon -->
                    <div>
                        <label class="block text-xs mb-1.5" style="color:#6b6660;">Coupon</label>
                        <select v-model="form.coupon_id"
                            class="w-full rounded-lg px-3 py-2 text-sm outline-none"
                            style="background:#111110;color:white;border:0.5px solid #2e2e2b;">
                            <option value="">No coupon</option>
                            <option v-for="coupon in coupons" :key="coupon.id" :value="coupon.id">
                                {{ coupon.title }} — {{ coupon.code }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="rounded-xl p-5 space-y-3"
                    style="background:#1c1c1a;border:0.5px solid #2e2e2b;">
                    <h2 class="text-xs font-medium pb-2"
                        style="color:#6b6660;letter-spacing:0.06em;
                               border-bottom:0.5px solid #2e2e2b;">
                        PRODUCT IMAGE
                    </h2>
                    <MediaSelector
                        v-model="form.media_id"
                        v-model:previewFile="imagePreviewFile"
                    />
                    <p v-if="form.errors.media_id" class="text-xs"
                        style="color:#ef4444;">{{ form.errors.media_id }}</p>
                </div>

                <!-- Gallery section in template -->
<div class="rounded-xl p-5 space-y-3"
    style="background:#1c1c1a;border:0.5px solid #2e2e2b;">
    <h2 class="text-xs font-medium pb-2"
        style="color:#6b6660;letter-spacing:0.06em;
               border-bottom:0.5px solid #2e2e2b;">
        GALLERY IMAGES
    </h2>

    <!-- Previews -->
    <div v-if="galleryPreviews.length" class="flex flex-wrap gap-2">
        <div v-for="img in galleryPreviews" :key="img.id"
            class="relative w-20 h-20 rounded-lg overflow-hidden"
            style="border:0.5px solid #2e2e2b;">
            <img :src="`/storage/media/${img.file_name}`"
                class="w-full h-full object-cover" />
            <button type="button" @click="removeGallery(img.id)"
                class="absolute top-1 right-1 w-5 h-5 rounded-full
                       flex items-center justify-center"
                style="background:#ef4444;">
                <Icons name="x" class="w-3 h-3" style="color:white;" />
            </button>
        </div>
    </div>

    <!-- Gallery picker — uses @select, ignores v-model -->
    <MediaSelector
        :modelValue="null"
        :previewFile="null"
        label="Add Gallery Image"
        @select="onGallerySelect"
    />

    <p v-if="!galleryPreviews.length" class="text-xs" style="color:#6b6660;">
        No gallery images added yet.
    </p>
</div>

                <!-- Descriptions -->
                <div class="rounded-xl p-5 space-y-4"
                    style="background:#1c1c1a;border:0.5px solid #2e2e2b;">
                    <h2 class="text-xs font-medium pb-2"
                        style="color:#6b6660;letter-spacing:0.06em;
                               border-bottom:0.5px solid #2e2e2b;">
                        DESCRIPTIONS
                    </h2>

                    <div v-for="field in descFields" :key="field.key">
                        <label class="block text-xs mb-1.5" style="color:#6b6660;">
                            {{ field.label }}
                        </label>
                        <textarea
                            v-model="form[field.key]"
                            :rows="field.rows"
                            :placeholder="field.placeholder"
                            class="w-full rounded-lg px-3 py-2 text-sm outline-none resize-y"
                            style="background:#111110;color:white;
                                   border:0.5px solid #2e2e2b;"
                        />
                        <p v-if="form.errors[field.key]" class="text-xs mt-1"
                            style="color:#ef4444;">
                            {{ form.errors[field.key] }}
                        </p>
                    </div>
                </div>

                <input type="hidden" v-model="form.id" />

                <!-- Submit -->
                <button type="submit" :disabled="form.processing"
                    class="w-full py-3 rounded-xl text-sm font-medium transition"
                    style="background:#d97706;color:#111110;"
                    :style="form.processing ? 'opacity:0.6' : ''">
                    {{ form.processing
                        ? 'Saving...'
                        : isEdit ? 'Update Product' : 'Create Product' }}
                </button>

            </form>
        </div>
    </VendorLayout>
</template>
