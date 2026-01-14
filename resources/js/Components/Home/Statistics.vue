<template>
  <section class="py-8 md:py-12 bg-[#FFF5F0]">
    <div class="container mx-auto px-4 max-w-6xl">
      <!-- En-tête de section -->
      <div class="text-center mb-16 md:mb-20">
        <h2 class="text-4xl md:text-5xl font-bold text-[#0E4C59] mb-6">
          Pourquoi choisir <span class="text-[#FF6B35]">USRA CARE</span> ?
        </h2>
        <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto">
          Découvrez les valeurs et engagements qui font notre différence
        </p>
      </div>

      <!-- Grille de valeurs -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Confiance & sécurité -->
        <div class="group bg-white rounded-2xl p-8 transition-all duration-500 hover:-translate-y-2 shadow-lg hover:shadow-xl border border-white">
          <div class="w-20 h-20 bg-gradient-to-br from-[#0E4C59] to-[#0A3A44] rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto group-hover:scale-110 transition-transform duration-500">
            <i class="bi bi-shield-check"></i>
          </div>
          <h3 class="text-2xl font-bold text-[#0E4C59] text-center mb-4">Confiance & sécurité</h3>
          <p class="text-gray-600 text-center leading-relaxed">
            Tous nos intervenants sont rigoureusement sélectionnés, formés et suivis pour garantir votre sérénité au quotidien.
          </p>
          <div class="mt-6 flex justify-center">
            <span class="inline-flex items-center bg-[#0E4C59]/10 text-[#0E4C59] text-xs font-semibold px-4 py-2 rounded-full">
              <i class="bi bi-fingerprint mr-2"></i>
              Vérification complète
            </span>
          </div>
        </div>

        <!-- Intervenants qualifiés -->
        <div class="group bg-white rounded-2xl p-8 transition-all duration-500 hover:-translate-y-2 shadow-lg hover:shadow-xl border border-white">
          <div class="w-20 h-20 bg-gradient-to-br from-[#33D0C3] to-[#2BB2A6] rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto group-hover:scale-110 transition-transform duration-500">
            <i class="bi bi-award"></i>
          </div>
          <h3 class="text-2xl font-bold text-[#0E4C59] text-center mb-4">Intervenants qualifiés</h3>
          <p class="text-gray-600 text-center leading-relaxed">
            Notre équipe est composée de professionnels expérimentés, régulièrement formés aux meilleures pratiques.
          </p>
          <div class="mt-6 flex justify-center">
            <span class="inline-flex items-center bg-[#33D0C3]/10 text-[#33D0C3] text-xs font-semibold px-4 py-2 rounded-full">
              <i class="bi bi-mortarboard mr-2"></i>
              Formation continue
            </span>
          </div>
        </div>

        <!-- Flexibilité et proximité -->
        <div class="group bg-white rounded-2xl p-8 transition-all duration-500 hover:-translate-y-2 shadow-lg hover:shadow-xl border border-white">
          <div class="w-20 h-20 bg-gradient-to-br from-[#FF6B35] to-[#E55A2B] rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto group-hover:scale-110 transition-transform duration-500">
            <i class="bi bi-geo-alt"></i>
          </div>
          <h3 class="text-2xl font-bold text-[#0E4C59] text-center mb-4">Flexibilité et proximité</h3>
          <p class="text-gray-600 text-center leading-relaxed">
            Des solutions adaptées à vos besoins avec une présence locale pour un service réactif et personnalisé.
          </p>
          <div class="mt-6 flex justify-center">
            <span class="inline-flex items-center bg-[#FF6B35]/10 text-[#FF6B35] text-xs font-semibold px-4 py-2 rounded-full">
              <i class="bi bi-clock mr-2"></i>
              7j/7 selon vos besoins
            </span>
          </div>
        </div>

        <!-- Aides financières -->
        <div class="group bg-white rounded-2xl p-8 transition-all duration-500 hover:-translate-y-2 shadow-lg hover:shadow-xl border border-white">
          <div class="w-20 h-20 bg-gradient-to-br from-[#0E4C59] to-[#33D0C3] rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto group-hover:scale-110 transition-transform duration-500">
            <i class="bi bi-cash-coin"></i>
          </div>
          <h3 class="text-2xl font-bold text-[#0E4C59] text-center mb-4">Aides financières</h3>
          <p class="text-gray-600 text-center leading-relaxed">
            Bénéficiez de 50% de crédit d'impôt sur l'ensemble de nos services sans conditions de ressources.
          </p>
          <div class="mt-6 flex justify-center">
            <span class="inline-flex items-center bg-[#0E4C59]/10 text-[#0E4C59] text-xs font-semibold px-4 py-2 rounded-full">
              <i class="bi bi-percent mr-2"></i>
              50% de crédit d'impôt
            </span>
          </div>
        </div>
      </div>

      <!-- Section de confiance -->
      <div class="mt-10 bg-white rounded-2xl p-4 md:p-6 shadow-lg border border-[#FFF5F0]">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
          <div class="text-center md:text-left">
        <h3 class="text-2xl md:text-3xl font-bold text-[#0E4C59] mb-4">
          Rejoignez les <span class="text-[#FF6B35]">+500 familles</span> qui nous font confiance
        </h3>
        <p class="text-gray-600 text-lg">
          Découvrez notre accompagnement personnalisé et sans engagement
        </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4">
        <Link 
          :href="route('quote')" 
          class="bg-[#FF6B35] hover:bg-[#E55A2B] text-white font-semibold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2"
        >
          <i class="bi bi-person-plus mr-2"></i>
          <span class="align-middle">Nous rejoindre</span>
        </Link>
        <Link 
          :href="route('about')" 
          class="bg-white border-2 border-[#0E4C59] text-[#0E4C59] font-semibold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 hover:bg-[#0E4C59] hover:text-white flex items-center justify-center gap-2"
        >
          <i class="bi bi-info-circle mr-2"></i>
          <span class="align-middle">En savoir plus</span>
        </Link>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
/* Animation d'apparition progressive */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Application de l'animation avec des délais différents pour chaque carte */
.grid > div:nth-child(1) {
  animation: fadeInUp 0.6s ease-out both;
}
.grid > div:nth-child(2) {
  animation: fadeInUp 0.8s ease-out both;
}
.grid > div:nth-child(3) {
  animation: fadeInUp 1.0s ease-out both;
}
.grid > div:nth-child(4) {
  animation: fadeInUp 1.2s ease-out both;
}
</style>