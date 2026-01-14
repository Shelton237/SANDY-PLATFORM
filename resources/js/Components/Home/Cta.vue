<template>
  <!-- Section CTA Final - Version claire et moderne -->
  <section class="py-8 md:py-10 bg-gradient-to-br from-[#F0FDFA] via-[#E6F7FF] to-[#F0F9FF] relative overflow-hidden">
    <!-- Éléments décoratifs -->
    <div class="absolute top-0 left-0 w-full h-full opacity-50">
      <div class="absolute top-10 left-10 w-32 h-32 bg-[#33D0C3]/10 rounded-full"></div>
      <div class="absolute bottom-10 right-10 w-40 h-40 bg-[#FF6B35]/10 rounded-full"></div>
      <div class="absolute top-1/3 right-1/4 w-24 h-24 bg-[#0E4C59]/10 rounded-full"></div>
    </div>

    <!-- Formes géométriques -->
    <div class="absolute -top-10 -right-10 w-60 h-60 bg-[#33D0C3]/5 rounded-full"></div>
    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-[#FF6B35]/5 rounded-full"></div>

    <div class="container mx-auto px-4 relative z-10">
      <div class="max-w-3xl mx-auto text-center">
        
        <!-- Titre principal -->
        <h2 class="text-4xl md:text-5xl font-bold text-[#0E4C59] mb-6">
          Prêt à <span class="text-[#FF6B35]">simplifier votre quotidien</span> ?
        </h2>
        
        <!-- Sous-titre -->
        <p class="text-xl text-[#0E4C59]/90 mb-10 max-w-2xl mx-auto leading-relaxed">
          Rejoignez les milliers de familles qui nous font confiance pour des services à domicile de qualité
        </p>
        
        <!-- Bouton CTA principal -->
        <div class="mb-12">
          <Link 
            :href="route('quote')" 
            class="inline-flex items-center bg-gradient-to-r from-[#FF6B35] to-[#FF8E53] hover:from-[#E55A2B] hover:to-[#FF6B35] text-white font-bold text-lg py-5 px-12 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl animate-pulse-once"
          >
            <i class="bi bi-chat-dots-fill mr-4 text-xl"></i>
            Demander un devis gratuit
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
/* Animation de pulsation unique pour le bouton */
.animate-pulse-once {
  animation: pulse-once 2s ease-in-out;
}

@keyframes pulse-once {
  0%, 100% {
    transform: scale(1);
    box-shadow: 0 10px 25px rgba(255, 107, 53, 0.2);
  }
  50% {
    transform: scale(1.05);
    box-shadow: 0 15px 35px rgba(255, 107, 53, 0.3);
  }
}

/* Transition douce pour tous les éléments */
.transition-all {
  transition: all 0.3s ease;
}

/* Effet de profondeur au survol */
.hover\:shadow-xl:hover {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>