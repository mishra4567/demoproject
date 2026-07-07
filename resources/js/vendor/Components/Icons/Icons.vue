<!-- Components/Icons/Icons.vue -->
<script setup>
defineProps({
    name: {
        type: String,
        required: true,
    },
    class: {
        type: String,
        default: 'w-4 h-4',
    },
});
</script>

<template>
    <!-- Success / Check -->
    <svg v-if="name === 'check'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>

    <!-- Close / X -->
    <svg v-else-if="name === 'x'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>

    <!-- Warning -->
    <svg v-else-if="name === 'warning'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
    </svg>

    <!-- Info -->
    <svg v-else-if="name === 'info'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
    </svg>

    <!-- Dashboard -->
    <svg v-else-if="name === 'dashboard'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 3h7v7H3zm11 0h7v7h-7zM3 14h7v7H3zm11 0h7v7h-7z" />
    </svg>

    <!-- Product / Box -->
    <svg v-else-if="name === 'product'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M20 7l-8-4-8 4m16 0v10l-8 4m-8-4V7m16 3l-8 4M4 10l8 4" />
    </svg>

    <!-- Orders / Bag -->
    <svg v-else-if="name === 'orders'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
    </svg>

    <!-- Wallet / Payouts -->
    <svg v-else-if="name === 'wallet'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm14 4h.01" />
    </svg>

    <!-- User -->
    <svg v-else-if="name === 'user'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z" />
    </svg>

    <!-- Settings -->
    <svg v-else-if="name === 'settings'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>

    <!-- Logout -->
    <svg v-else-if="name === 'logout'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
    </svg>

    <!-- Search -->
    <svg v-else-if="name === 'search'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
    </svg>

    <!-- Edit / Pencil -->
    <svg v-else-if="name === 'edit'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>

    <!-- Trash / Delete -->
    <svg v-else-if="name === 'trash'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    </svg>

    <!-- Plus / Add -->
    <svg v-else-if="name === 'plus'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>

    <!-- Bell / Notification -->
    <svg v-else-if="name === 'bell'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>

    <!-- Menu / Hamburger -->
    <svg v-else-if="name === 'menu'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 6h16M4 12h16M4 18h16" />
    </svg>

    <!-- Chevron Down -->
    <svg v-else-if="name === 'chevron-down'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
    </svg>

    <!-- Store / Building -->
    <svg v-else-if="name === 'store'" xmlns="http://www.w3.org/2000/svg"
         :class="$props.class" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10" />
    </svg>
    <!-- ── Eye open ── -->
  <svg v-else-if="name === 'eye'" viewBox="0 0 20 20" fill="currentColor"
    :class="$props.class">
    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
    <path fill-rule="evenodd"
      d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0
         7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110
         17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
      clip-rule="evenodd" />
  </svg>

  <!-- ── Eye closed / Eye off ── -->
  <svg v-else-if="name === 'eye-off'" viewBox="0 0 20 20" fill="currentColor"
    :class="$props.class">
    <path fill-rule="evenodd"
      d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06
         l-1.745-1.745a10.029 10.029 0 003.3-4.38 1.651 1.651 0 000-1.185A10.004
         10.004 0 009.999 3a9.956 9.956 0 00-4.744 1.194L3.28 2.22zM7.752
         6.69l1.092 1.092a2.5 2.5 0 013.374 3.373l1.091 1.092a4 4 0
         00-5.557-5.557z"
      clip-rule="evenodd" />
    <path d="M10.748 13.93l2.523 2.523a10.04 10.04 0 01-6.27 0l2.523-2.523a2.5
      2.5 0 001.224 0zM6.364 17.382l-1.53-1.53A9.958 9.958 0 012 10.999a10 10
      0 011.58-5.394l1.53 1.53A7.96 7.96 0 003 11a7.96 7.96 0 003.364 6.382z" />
  </svg>
  <!-- ── Media Library ── -->
    <svg
    v-else-if="name === 'media'"
    viewBox="0 0 20 20"
    fill="currentColor"
    :class="$props.class"
    >
    <path
        d="M4 3a2 2 0 00-2 2v8a2 2 0 002 2h1v1a2 2 0 002 2h9a2 2 0 002-2V8a2 2 0
        00-2-2h-1V5a2 2 0 00-2-2H4zm0 2h9v8H4V5zm4 2a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm-2 5l2-2 3 3H6z"
    />
    </svg>
    <!-- ── Coupon Discount ── -->
<svg
  v-else-if="name === 'coupons'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    fill-rule="evenodd"
    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v2a1 1 0 000 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V8a1 1 0 000-2V4zm3 1.5a1 1 0 100 2 1 1 0 000-2zm6 7a1 1 0 100 2 1 1 0 000-2zm-5.47 1.53a.75.75 0 001.06 0l4.88-4.88a.75.75 0 10-1.06-1.06l-4.88 4.88a.75.75 0 000 1.06z"
    clip-rule="evenodd"
  />
</svg>
<!-- ── Brands ── -->
<svg
  v-else-if="name === 'brands'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    d="M10 2a6 6 0 100 12A6 6 0 0010 2zm0 2a4 4 0 110 8 4 4 0 010-8z"
  />
  <path
    d="M4 16a6 6 0 0112 0v1H4v-1z"
  />
</svg>
<!-- ── Chevron Left ── -->
<svg
  v-else-if="name === 'chevron-left'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    fill-rule="evenodd"
    d="M12.78 15.53a.75.75 0 01-1.06 0l-5-5a.75.75 0 010-1.06l5-5a.75.75 0 111.06 1.06L8.31 10l4.47 4.47a.75.75 0 010 1.06z"
    clip-rule="evenodd"
  />
</svg>
<!-- ── Categories Folder ── -->
<svg
  v-else-if="name === 'category'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    d="M2 5a2 2 0 012-2h3l2 2h7a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"
  />
</svg>
<!-- ── Color / Palette ── -->
<svg
  v-else-if="name === 'colors'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    d="M10 2a8 8 0 100 16h1.5a1.5 1.5 0 001.06-2.56l-.23-.23a1 1 0 01.71-1.71H14a4 4 0 004-4 8 8 0 00-8-8z"
  />
  <circle cx="6.5" cy="8" r="1" />
  <circle cx="9" cy="5.5" r="1" />
  <circle cx="13.5" cy="8" r="1" />
  <circle cx="7.5" cy="12" r="1" />
</svg>
<!-- ── Link Product ── -->
<svg
  v-else-if="name === 'link-product'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    fill-rule="evenodd"
    d="M4.75 3A1.75 1.75 0 003 4.75v4.5C3 10.216 3.784 11 4.75 11h4.5A1.75 1.75 0 0011 9.25v-4.5A1.75 1.75 0 009.25 3h-4.5zm0 1.5h4.5a.25.25 0 01.25.25v4.5a.25.25 0 01-.25.25h-4.5a.25.25 0 01-.25-.25v-4.5a.25.25 0 01.25-.25z"
    clip-rule="evenodd"
  />
  <path
    d="M8.5 10.75a.75.75 0 01.75-.75h1.5a3.25 3.25 0 010 6.5h-1.5a.75.75 0 010-1.5h1.5a1.75 1.75 0 000-3.5h-1.5a.75.75 0 01-.75-.75z"
  />
  <path
    d="M11.5 9.25a.75.75 0 01-.75.75h-1.5a3.25 3.25 0 010-6.5h1.5a.75.75 0 010 1.5h-1.5a1.75 1.75 0 000 3.5h1.5a.75.75 0 01.75.75z"
  />
</svg>
<!-- ── Technical Specs (Gear) ── -->
<svg
  v-else-if="name === 'technical-specs'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    d="M10 2a1 1 0 011 1v1.09a4.98 4.98 0 011.57.65l.77-.77a1 1 0 011.41 1.41l-.77.77c.3.49.52 1.02.65 1.57H16a1 1 0 110 2h-1.09a4.98 4.98 0 01-.65 1.57l.77.77a1 1 0 01-1.41 1.41l-.77-.77a4.98 4.98 0 01-1.57.65V17a1 1 0 11-2 0v-1.09a4.98 4.98 0 01-1.57-.65l-.77.77a1 1 0 01-1.41-1.41l.77-.77A4.98 4.98 0 015.09 11H4a1 1 0 110-2h1.09c.13-.55.35-1.08.65-1.57l-.77-.77a1 1 0 011.41-1.41l.77.77A4.98 4.98 0 019 4.09V3a1 1 0 011-1zm0 5a3 3 0 100 6 3 3 0 000-6z"
  />
</svg>
<!-- ── Size / Measurement ── -->
<svg
  v-else-if="name === 'size'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    fill-rule="evenodd"
    d="M13.28 2.22a.75.75 0 011.06 0l3.44 3.44a.75.75 0 010 1.06l-9.5 9.5a2 2 0 01-1.414.586H3.75a.75.75 0 01-.75-.75v-3.116a2 2 0 01.586-1.414l9.694-9.306zM14 4.31L5.06 13.25a.5.5 0 00-.146.354v1.896h1.896a.5.5 0 00.354-.146L16.1 6.42 14 4.31z"
    clip-rule="evenodd"
  />
  <path
    d="M11.5 5.75a.75.75 0 011.5 0v1a.75.75 0 01-1.5 0v-1zM9.5 7.75a.75.75 0 011.5 0v1a.75.75 0 01-1.5 0v-1zM7.5 9.75a.75.75 0 011.5 0v1a.75.75 0 01-1.5 0v-1z"
  />
</svg>
<svg
  v-else-if="name === 'report'"
  viewBox="0 0 20 20"
  fill="currentColor"
  :class="$props.class"
>
  <path
    fill-rule="evenodd"
    d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7.414A2 2 0 0016.414 6L14 3.586A2 2 0 0012.586 3H5zm7 1.5V7h2.5L12 4.5z"
    clip-rule="evenodd"
  />
  <path
    d="M7 14.5a.5.5 0 01-.5-.5v-2a.5.5 0 011 0v2a.5.5 0 01-.5.5zm3 0a.5.5 0 01-.5-.5V9a.5.5 0 011 0v5a.5.5 0 01-.5.5zm3 0a.5.5 0 01-.5-.5v-3.5a.5.5 0 011 0V14a.5.5 0 01-.5.5z"
  />
</svg>
</template>
