<template>
    <button
        class="btn-primary inline-flex items-center rounded-xl px-6 py-3 text-sm font-semibold uppercase tracking-widest text-white transition-all duration-300 ease-in-out transform hover:scale-105 focus:outline-none active:scale-95"
    >
        <slot />
    </button>
</template>

<style scoped>
.btn-primary {
    background: linear-gradient(to right, #254a29, #1e3f1c);
    box-shadow: 0 4px 6px rgba(37, 74, 41, 0.3);
}
.btn-primary:hover {
    background: linear-gradient(to right, #1e3f1c, #254a29);
    box-shadow: 0 6px 8px rgba(37, 74, 41, 0.4);
}
.btn-primary:focus {
    outline: 2px solid #f49926;
    outline-offset: 2px;
}
.btn-primary:active {
    background: linear-gradient(to right, #183318, #254a29);
}
</style>