<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '', email: '', password: '',
    password_confirmation: '', shop_name: '', phone: '',
});

function submit() {
    form.post('/vendor/register');
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6">Vendor Register</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Name</label>
                    <input v-model="form.name" type="text"
                           class="w-full border rounded p-2" />
                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                        {{ form.errors.name }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm mb-1">Shop Name</label>
                    <input v-model="form.shop_name" type="text"
                           class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="block text-sm mb-1">Phone</label>
                    <input v-model="form.phone" type="text"
                           class="w-full border rounded p-2" />
                </div>
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
                <div>
                    <label class="block text-sm mb-1">Confirm Password</label>
                    <input v-model="form.password_confirmation" type="password"
                           class="w-full border rounded p-2" />
                </div>
                <button type="submit" :disabled="form.processing"
                        class="w-full bg-black text-white py-2 rounded">
                    Register
                </button>
                <p class="text-center text-sm">
                    Already registered?
                    <a href="/vendor/login" class="text-blue-500">Login</a>
                </p>
            </form>
        </div>
    </div>
</template>
