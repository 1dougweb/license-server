<script setup>
defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    options: {
        type: Array,
        required: true,
    },
    error: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <select
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
            :class="[
                'w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500',
                error && 'border-red-500 focus:border-red-500 focus:ring-red-500',
            ]"
        >
            <option value="">Selecione...</option>
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

