<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-[#faf7f2] via-white to-[#fef6e6] relative overflow-hidden">
        <!-- Background elements décoratifs -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10% left-5% w-32 h-32 bg-[#f49926]/5 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute bottom-20% right-10% w-40 h-40 bg-[#254a29]/5 rounded-full blur-3xl animate-float-medium"></div>
            <div class="absolute top-30% right-25% w-24 h-24 bg-[#33D0C3]/5 rounded-full blur-2xl animate-float-fast"></div>
        </div>

        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-8 z-10">
            <!-- Logo avec animation améliorée -->
            <div class="mb-10 transform transition-all duration-700 hover:scale-105 hover:-translate-y-1">
                <Link href="/" class="block focus:outline-none focus:ring-2 focus:ring-[#f49926] focus:ring-offset-4 focus:ring-offset-[#faf7f2] rounded-2xl transition-all">
                    <ApplicationLogo 
                        class="h-28 w-28 fill-current text-[#254a29] drop-shadow-lg filter transition-all duration-500 hover:drop-shadow-xl" 
                    />
                </Link>
            </div>

            <!-- Container principal avec design premium -->
            <div class="w-full overflow-hidden bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border border-[#fbe6c8]/50 transition-all duration-500 hover:shadow-3xl hover:border-[#fbe6c8] sm:max-w-md group">
                <!-- En-tête décoratif avec gradient animé -->
                <div class="bg-gradient-to-r from-[#254a29] via-[#2a5230] to-[#1e3f1c] h-3 w-full relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
                </div>
                
                <!-- Contenu avec espacement optimisé -->
                <div class="px-8 py-10">
                    <slot />
                </div>

                <!-- Pied de page avec micro-interactions -->
                <div class="bg-gradient-to-b from-white to-[#faf7f2] px-8 py-5 text-center border-t border-[#fbe6c8]/50">
                    <p class="text-xs text-[#254a29]/60 transition-colors duration-300 group-hover:text-[#254a29]/70">
                        © 2025 Jus Naturels • 
                        <span class="text-[#f49926] font-medium">100% Naturel</span>
                    </p>
                </div>
            </div>

            <!-- Message d'accueil avec animation -->
            <div class="mt-10 text-center">
                <p class="text-sm text-[#254a29]/60 max-w-md transform transition-all duration-500 hover:scale-105 hover:text-[#254a29]/70">
                    <span class="inline-block animate-bounce-subtle">✨</span>
                    Découvrez l'authenticité de nos jus pressés à froid
                    <span class="inline-block animate-bounce-subtle delay-150">🍃</span>
                </p>
            </div>

            <!-- Élément décoratif flottant -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 opacity-40">
                <div class="flex space-x-2">
                    <div v-for="i in 3" :key="i" 
                         class="w-2 h-2 bg-[#f49926] rounded-full animate-pulse" 
                         :style="`animation-delay: ${i * 0.2}s`">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes float-slow {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

@keyframes float-medium {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(-3deg); }
}

@keyframes float-fast {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(2deg); }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes bounce-subtle {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-3px); }
    60% { transform: translateY(-2px); }
}

.animate-float-slow {
    animation: float-slow 8s ease-in-out infinite;
}

.animate-float-medium {
    animation: float-medium 6s ease-in-out infinite;
}

.animate-float-fast {
    animation: float-fast 4s ease-in-out infinite;
}

.animate-shimmer {
    animation: shimmer 3s ease-in-out infinite;
}

.animate-bounce-subtle {
    animation: bounce-subtle 2s infinite;
}
</style>