<template>
    <div class="group relative flex items-center space-x-3 cursor-pointer">
        <!-- Logo avec effet de halo -->
        <div class="relative">
            <div class="absolute inset-0 bg-[#f49926] opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
            <img 
                src="/images/logo.svg" 
                class="w-96 transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3" 
            />
        </div>
    </div>
</template>