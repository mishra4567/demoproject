# 🛒 AdminShop — Laravel E-Commerce Platform

> A powerful, full-stack e-commerce platform built with **Laravel**, featuring a **Blade Admin Panel**, **Vendor API**, and **Frontend Customer API** — all within a single Laravel project using modular route groups.

---

## 📦 Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2 / Laravel |
| Database | Mysql 9.6.0 |
| Admin UI | Bootstrap 5, JS, Blade Templates |
| API Auth | Laravel Sanctum (Vendor & Frontend) |
| Storage | Laravel Storage (public disk) |
| Admin Auth | Laravel Session Auth |

---

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────┐
│                  Laravel Application                 │
│                                                     │
│  ┌─────────────────┐  ┌──────────────────────────┐  │
│  │   Admin Panel   │  │        API Layer         │  │
│  │  (Blade + Web)  │  │    (routes/api.php)      │  │
│  │                 │  │                          │  │
│  │  /admin/*       │  │  /api/vendor/*           │  │
│  │  Session Auth   │  │  /api/frontend/*         │  │
│  │  Bootstrap 5    │  │  Sanctum Token Auth      │  │
│  └─────────────────┘  └──────────────────────────┘  │
│                                                     │
│              Shared Database (MariaDB)               │
└─────────────────────────────────────────────────────┘
```

---

## ✨ Features Overview

### 🖥️ Admin Panel
- 🗂️ **Category Management** — CRUD with status control
- 📦 **Product Management** — Full product lifecycle with media, gallery, variants
- 🔗 **Link Products** — Attribute-based variants (size, color, SKU, price, qty)
- 🖼️ **Media Library** — WordPress-inspired image picker with multi-select
- 🎨 **Gallery System** — Multiple images per product via `product_gallery` table
- 🏷️ **Size & Color Management** — Reusable attribute tables
- 🎫 **Coupons** — Discount code management
- 📅 **Calendar** — Full calendar with events and notifications *(v1.2.2)*

### 🏪 Vendor API
- 🔐 **Vendor Auth** — Register, login, logout with Sanctum tokens
- 📦 **Product Management** — Add, edit, delete vendor products
- 🛒 **Order Management** — View and update order status
- 💰 **Payouts** — Track earnings and payout history

### 🛍️ Frontend API
- 👤 **Customer Auth** — Register, login, logout, profile
- 📋 **Product Listing** — Browse products, categories, search & filter
- 🛒 **Cart & Checkout** — Add to cart, apply coupons, place orders
- 📦 **Order Tracking** — View order history and status
- ⭐ **Reviews & Wishlist** — Product reviews and saved items


---

## 📁 Full Project Structure

```
app/
├── Http/
│   └── Controllers/
│       │
│       ├── Admin/                          ← 🖥️ Admin Panel (Blade)
│       │   ├── DashboardController.php
│       │   ├── ProductController.php
│       │   ├── ViewProductController.php
│       │   ├── LinkProductController.php
│       │   ├── MediaController.php
│       │   ├── CategoryController.php
│       │   ├── SizeController.php
│       │   ├── ColorController.php
│       │   ├── CouponController.php
│       │   └── CalendarController.php
│       │
│       ├── Api/
│       │   ├── Vendor/                     ← 🏪 Vendor API
│       │   │   ├── AuthController.php
│       │   │   ├── ProductController.php
│       │   │   ├── OrderController.php
│       │   │   └── PayoutController.php
│       │   │
│       │   └── Frontend/                   ← 🛍️ Frontend API
│       │       ├── AuthController.php
│       │       ├── ProductController.php
│       │       ├── CategoryController.php
│       │       ├── CartController.php
│       │       ├── OrderController.php
│       │       ├── CouponController.php
│       │       ├── WishlistController.php
│       │       └── ReviewController.php
│
├── Models/
│   ├── Product.php
│   ├── Category.php
│   ├── LinkProduct.php
│   ├── ProductGallery.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Cart.php
│   ├── Vendor.php
│   ├── Coupon.php
│   ├── Wishlist.php
│   └── Review.php
│
resources/
└── views/
    └── admin/
        ├── layout/
        │   └── layout.blade.php
        ├── dashboard/
        │   └── index.blade.php
        ├── product/
        │   ├── index.blade.php
        │   ├── manage_product.blade.php
        │   ├── manage_product_process.blade.php
        │   ├── viewproduct.blade.php
        │   └── linkproduct/
        │       ├── index.blade.php
        │       └── process.blade.php
        ├── category/
        │   └── index.blade.php
        ├── media/
        │   ├── index.blade.php
        │   └── create.blade.php
        ├── calendar/
        │   └── index.blade.php
        └── partials/
            └── media_modal.blade.php

routes/
├── web.php                                 ← Admin panel routes
└── api.php                                 ← Vendor + Frontend API routes
```

---

## 🛣️ Route Structure

### `routes/web.php` — Admin Panel
```php
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard',         [DashboardController::class, 'index']);
    Route::get('/product',           [ProductController::class, 'index']);
    Route::get('/product/manage',    [ProductController::class, 'manageproduct']);
    Route::post('/product/process',  [ProductController::class, 'manageproductprocess']);
    Route::get('/product/view/{id}', [ViewProductController::class, 'index']);
    Route::get('/media',             [MediaController::class, 'index']);
    Route::get('/calendar',          [CalendarController::class, 'index']);
    // ...
});
```

### `routes/api.php` — Vendor API
```php
Route::prefix('vendor')->group(function () {
    // Public
    Route::post('/register', [Vendor\AuthController::class, 'register']);
    Route::post('/login',    [Vendor\AuthController::class, 'login']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',           [Vendor\AuthController::class, 'logout']);
        Route::apiResource('/products',  Vendor\ProductController::class);
        Route::get('/orders',            [Vendor\OrderController::class, 'index']);
        Route::put('/orders/{id}',       [Vendor\OrderController::class, 'update']);
        Route::get('/payouts',           [Vendor\PayoutController::class, 'index']);
    });
});
```

### `routes/api.php` — Frontend API
```php
Route::prefix('frontend')->group(function () {
    // Public
    Route::post('/register',          [Frontend\AuthController::class, 'register']);
    Route::post('/login',             [Frontend\AuthController::class, 'login']);
    Route::get('/products',           [Frontend\ProductController::class, 'index']);
    Route::get('/products/{id}',      [Frontend\ProductController::class, 'show']);
    Route::get('/categories',         [Frontend\CategoryController::class, 'index']);
  
    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',        [Frontend\AuthController::class, 'logout']);
        Route::get('/profile',        [Frontend\AuthController::class, 'profile']);
        Route::post('/cart',          [Frontend\CartController::class, 'add']);
        Route::get('/cart',           [Frontend\CartController::class, 'index']);
        Route::post('/checkout',      [Frontend\OrderController::class, 'checkout']);
        Route::get('/orders',         [Frontend\OrderController::class, 'index']);
        Route::post('/coupon/apply',  [Frontend\CouponController::class, 'apply']);
        Route::post('/wishlist',      [Frontend\WishlistController::class, 'toggle']);
        Route::post('/reviews',       [Frontend\ReviewController::class, 'store']);
    });
});
```

---

## 🖼️ Media Library

Inspired by the WordPress media picker. Supports:

- 📂 Click-to-select with visual checkmark highlight
- 🔍 Live filename search/filter
- ✅ **Smart selection mode**:
  - `product` target → single select
  - `attr_N` target → single select per variant
  - `gallery` target → multiple select
- 📐 File name, size, and dimensions display
- ➕ Direct link to upload new media

**JS Entry Point:**
```javascript
addMedia('product');     // single select
addMedia('gallery');     // multi select
addMedia('attr_1');      // variant image
```

## 🖼️ Product Gallery

Products support multiple gallery images stored in a dedicated table:

```sql
CREATE TABLE product_gallery (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    media_id BIGINT UNSIGNED NOT NULL,
    status INT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (media_id) REFERENCES create_media_tables(id) ON DELETE CASCADE
);
```

On **update**, old gallery entries are deleted and replaced with the newly selected set.

---

## 🔍 Product View Query

The view product controller fetches product, category, main image, variant attributes, and gallery in optimized joined queries:

```php
// Main product with category + image
DB::table('products')
    ->leftJoin('create_media_tables', ...)
    ->leftJoin('categories', ...)
    ->where('products.status', 1)
    ->first();

// Variants with size, color, image
DB::table('linkproducts')
    ->leftJoin('sizes', ...)
    ->leftJoin('colors', ...)
    ->leftJoin('create_media_tables as attr_media', ...)
    ->where('linkproducts.status', 1)
    ->get();

// Gallery images
DB::table('product_gallery')
    ->leftJoin('create_media_tables', ...)
    ->where('product_gallery.status', 1)
    ->get();
```

---

## 📋 Admin Sidebar Menu

```
Dashboard
Category
Coupons
Size
Color
Product
  ├── All Products
  ├── Add Product
  ├── Link Product
  └── Add Link Product
Media
  ├── Library
  └── Add Media File
Calendar  (v1.2.2)
```

---

## 📅 Changelog

### v1.2.2 — 2026-04-03
> 🗓️ Calendar & Event System

- ✅ Added **Full Calendar** integration
- ✅ Added **Event creation** — title, date, time, description
- ✅ Added **Event Notifications** — alerts before event start
- ✅ Events stored and retrieved from database
- ✅ Calendar view supports month / week / day modes

---

### v1.2.1
> 🖼️ Media Library Overhaul

- ✅ Replaced radio-button picker with **click-to-select cards**
- ✅ Added **visual checkmark** on selected image
- ✅ Added **live search** by filename
- ✅ Smart mode: **single select** for product/variant, **multi-select** for gallery
- ✅ Selected image count shown in modal footer
- ✅ Added `+ Add Media` shortcut button inside modal

---

### v1.2.0
> 🖼️ Product Gallery

- ✅ Added `product_gallery` database table
- ✅ Gallery images selectable via media modal (`gallery` target)
- ✅ Gallery renders on product edit with existing images pre-loaded
- ✅ Remove individual gallery images before saving
- ✅ On update: old gallery cleared and reinserted cleanly

---

### v1.1.0
> 🔗 Link Product / Variants

- ✅ Dynamic add/remove attribute rows (no page reload)
- ✅ Per-variant image selector via media modal
- ✅ Size, Color, SKU, MRP, Price, Qty per variant
- ✅ `attrCount` based row ID system to prevent JS conflicts
- ✅ Edit mode pre-loads existing variants with correct `media_id`

---

### v1.0.0
> 🚀 Initial Release

- ✅ Product CRUD (name, slug, brand, model, desc, keywords, specs, uses, warranty)
- ✅ Category management with status
- ✅ Single product image via media library
- ✅ Size & Color management
- ✅ Coupon management
- ✅ Media file upload and library
- ✅ Admin layout with sidebar navigation

---

## 🚀 Installation

```bash
git clone https://github.com/yourname/adminshop.git
cd adminshop
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

---

## 🛣️ Roadmap

- [ ] Orders Management
- [ ] Customer Management
- [ ] Sales Reports & Analytics
- [ ] Banner / Slider Management
- [ ] SEO Settings
- [ ] Shipping & Payment Settings
- [ ] Product Reviews & Ratings
- [ ] Stock Alerts

---

## 👤 Author

Built with ❤️ using Laravel + Bootstrap 5.

---

> _"Built to scale. Designed to manage."_
