<script setup>
defineProps({
    title: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 v-if="title" class="text-lg font-semibold text-gray-900 mb-4">
            {{ title }}
        </h3>
        <slot />
    </div>
</template>

