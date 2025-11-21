<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="[
            'px-4 py-2 rounded-md font-medium transition-colors',
            variant === 'primary' && 'bg-blue-600 text-white hover:bg-blue-700 disabled:bg-gray-400',
            variant === 'secondary' && 'bg-gray-200 text-gray-800 hover:bg-gray-300 disabled:bg-gray-100',
            variant === 'danger' && 'bg-red-600 text-white hover:bg-red-700 disabled:bg-gray-400',
            variant === 'success' && 'bg-green-600 text-white hover:bg-green-700 disabled:bg-gray-400',
        ]"
    >
        <slot />
    </button>
</template>

