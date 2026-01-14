<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-xl border border-[#254a29] bg-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-[#254a29] shadow-sm transition duration-150 ease-in-out hover:bg-[#254a29] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#f49926] focus:ring-offset-2 disabled:opacity-25"
    >
        <slot />
    </button>
</template>