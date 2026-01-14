<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-[#254a29] placeholder-gray-400 shadow-sm transition-all duration-200 focus:border-[#254a29] focus:ring-2 focus:ring-[#f49926] focus:ring-opacity-50 focus:outline-none"
        v-model="model"
        ref="input"
    />
</template>