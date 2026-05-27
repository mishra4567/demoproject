<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '', password: '', remember: false,
});

function submit() {
    form.post('/vendor/login');
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6">Vendor Login</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input v-model="form.email" type="email"
                           class="w-full border rounded p-2" />
                    <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm mb-1">Password</label>
                    <input v-model="form.password" type="password"
                           class="w-full border rounded p-2" />
                    <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <input v-model="form.remember" type="checkbox" id="remember" />
                    <label for="remember" class="text-sm">Remember me</label>
                </div>
                <button type="submit" :disabled="form.processing"
                        class="w-full bg-black text-white py-2 rounded">
                    Login
                </button>
                <p class="text-center text-sm">
                    No account?
                    <a href="/vendor/register" class="text-blue-500">Register</a>
                </p>
            </form>
        </div>
    </div>
</template>
