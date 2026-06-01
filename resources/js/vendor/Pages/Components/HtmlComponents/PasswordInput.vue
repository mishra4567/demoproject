<!-- resources/js/Components/Vendor/PasswordInput.vue -->
<script setup>
import { ref } from 'vue'
import { Icons } from '..';

defineProps({
  modelValue: { type: String,  default: '' },
  label:      { type: String,  default: 'Password' },
  placeholder:{ type: String,  default: '••••••••' },
  error:      { type: String,  default: '' },
  autocomplete:{ type: String, default: 'current-password' },
})

defineEmits(['update:modelValue'])

const show = ref(false)
</script>

<template>
  <div class="w-full">
    <label class="block text-sm font-medium mb-1">{{ label }}</label>

    <div class="relative">
      <input
        :type="show ? 'text' : 'password'"
        :value="modelValue"
        :placeholder="placeholder"
        :autocomplete="autocomplete"
        :class="[
          'w-full border rounded-lg px-3 py-2 pr-10 text-sm',
          'focus:outline-none focus:ring-2 focus:ring-black',
          error ? 'border-red-400 focus:ring-red-300' : 'border-gray-300',
        ]"
        @input="$emit('update:modelValue', $event.target.value)"
      />

      <!-- Eye toggle -->
      <button
        type="button"
        @click="show = !show"
        :aria-label="show ? 'Hide password' : 'Show password'"
        class="absolute right-3 top-1/2 -translate-y-1/2
          text-gray-400 hover:text-gray-600 transition-colors"
      >
      <Icons :name="show ? 'eye-off' : 'eye'" class="w-4 h-4" />
      </button>
    </div>

    <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
  </div>
</template>
