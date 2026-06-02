<!-- resources/js/Vendor/Auth/Login.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3'
import { useAuth } from '@/vendor/Back'
import { Icons, PasswordInput } from '../Components'

const { login } = useAuth()

const form = useForm({
  email:    '',
  password: '',
  remember: false,
})

const submit = () => login(form, {
  onError: () => form.reset('password'),
})
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-sm bg-white rounded-xl border shadow-sm p-8 space-y-6">

      <!-- Logo -->
      <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 rounded-md flex items-center justify-center"
          style="background:#d97706;">
            <Icons name="store" class="w-4 h-4" style="color:white;" />
          <!-- <i class="ti ti-building-store" style="color:white;font-size:16px;" aria-hidden="true"></i> -->
        </div>
        <div>
          <p class="text-base font-semibold leading-none">Vendor Panel</p>
          <p class="text-xs text-gray-400 leading-none mt-0.5">Sign in to your account</p>
        </div>
      </div>

      <!-- Errors -->
      <div v-if="form.errors.email || form.errors.password"
        class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-600">
        {{ form.errors.email || form.errors.password }}
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" placeholder="you@example.com"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
            required autocomplete="email" />
        </div>

        <div>
          <!-- ✅ Password with eye toggle -->
        <PasswordInput
          v-model="form.password"
          label="Password"
          placeholder="••••••••"
          autocomplete="current-password"
          :error="form.errors.password"
        />
        </div>

        <div class="flex items-center gap-2">
          <input v-model="form.remember" type="checkbox" id="remember"
            class="w-4 h-4 rounded border-gray-300" />
          <label for="remember" class="text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit" :disabled="form.processing"
          class="w-full py-2.5 bg-black text-white rounded-lg text-sm font-medium
            hover:bg-gray-800 transition disabled:opacity-50">
          {{ form.processing ? 'Signing in...' : 'Sign in' }}
        </button>
      </form>

      <p class="text-center text-sm text-gray-500">
        Don't have an account?
        <a href="/vendor/register" class="text-black font-medium underline underline-offset-2">
          Register
        </a>
      </p>

    </div>
  </div>
</template>
