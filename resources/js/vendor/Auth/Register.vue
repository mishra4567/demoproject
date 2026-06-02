<!-- resources/js/Vendor/Auth/Register.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3'
import { useAuth } from '@/vendor/Back'
import { PasswordInput } from '../Components'

const { register } = useAuth()

const form = useForm({
  name:                  '',
  email:                 '',
  password:              '',
  password_confirmation: '',
  shop_name:             '',
  phone:                 '',
})

const submit = () => register(form, {
  onError: () => form.reset('password', 'password_confirmation'),
})
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-10">
    <div class="w-full max-w-md bg-white rounded-xl border shadow-sm p-8 space-y-6">

      <!-- Logo -->
      <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 rounded-md flex items-center justify-center"
          style="background:#d97706;">
          <i class="ti ti-building-store" style="color:white;font-size:16px;" aria-hidden="true"></i>
        </div>
        <div>
          <p class="text-base font-semibold leading-none">Vendor Panel</p>
          <p class="text-xs text-gray-400 leading-none mt-0.5">Create your seller account</p>
        </div>
      </div>

      <!-- Errors -->
      <div v-if="Object.keys(form.errors).length"
        class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-600 space-y-1">
        <p v-for="(err, field) in form.errors" :key="field">
          <strong class="capitalize">{{ field.replace('_', ' ') }}:</strong> {{ err }}
        </p>
      </div>

      <form @submit.prevent="submit" class="space-y-4">

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Full Name</label>
            <input v-model="form.name" type="text" placeholder="John Doe"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
              required autocomplete="name" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Shop Name</label>
            <input v-model="form.shop_name" type="text" placeholder="My Store"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" placeholder="you@example.com"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
            required autocomplete="email" />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Phone</label>
          <input v-model="form.phone" type="tel" placeholder="+91 98765 43210"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
            autocomplete="tel" />
        </div>

        <div>
            <!-- Password with eye toggle -->
            <PasswordInput
            v-model="form.password"
            label="Password"
            placeholder="Min 8 characters"
            autocomplete="new-password"
            :error="form.errors.password"
            />
        </div>

        <div>
            <!-- ✅ Confirm Password with eye toggle -->
            <PasswordInput
            v-model="form.password_confirmation"
            label="Confirm Password"
            placeholder="Repeat password"
            autocomplete="new-password"
            :error="form.errors.password_confirmation"
            />
        </div>

        <button type="submit" :disabled="form.processing"
          class="w-full py-2.5 bg-black text-white rounded-lg text-sm font-medium
            hover:bg-gray-800 transition disabled:opacity-50">
          {{ form.processing ? 'Creating account...' : 'Create account' }}
        </button>

      </form>

      <p class="text-center text-sm text-gray-500">
        Already have an account?
        <a href="/vendor/login" class="text-black font-medium underline underline-offset-2">
          Sign in
        </a>
      </p>

    </div>
  </div>
</template>
