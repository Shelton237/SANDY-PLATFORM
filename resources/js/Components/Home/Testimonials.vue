<template>
  <section class="relative bg-[#fbe6c8]/20 py-8 lg:py-10 overflow-hidden">
    <!-- Éléments d'arrière-plan décoratifs -->
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-20 right-10% w-24 h-24 bg-[#f49926]/5 rounded-full blur-2xl"></div>
      <div class="absolute bottom-30 left-15% w-20 h-20 bg-[#254a29]/5 rounded-full blur-2xl"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-6 relative z-10">
      <!-- En-tête de section -->
      <div class="text-center mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-light text-[#254a29] mb-4">
          Ce que disent nos <span class="font-bold text-[#f49926]">clients</span>
        </h2>
        <p class="text-lg text-[#254a29]/80 max-w-2xl mx-auto">
          Découvrez les expériences de ceux qui ont déjà adopté Sandy Juice à Yaoundé
        </p>
      </div>

      <!-- Témoignages -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Témoignage 1 -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-[#fbe6c8]">
          <div class="flex items-center mb-4">
            <div class="flex text-[#f49926]">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
          </div>
          <p class="text-[#254a29]/80 italic mb-5">
            "Je commande chez Sandy Juice chaque semaine. Leurs jus sont toujours frais et délicieux. 
            La livraison est ultra-rapide ici à Yaoundé. Mon préféré est le Tropical !"
          </p>
          <div class="flex items-center">
            <div class="w-12 h-12 bg-[#254a29]/10 rounded-full flex items-center justify-center mr-3">
              <span class="text-[#254a29] font-semibold">AM</span>
            </div>
            <div>
              <h4 class="font-semibold text-[#254a29]">Aïssa M.</h4>
              <p class="text-sm text-[#254a29]/60">Bastos, Yaoundé</p>
            </div>
          </div>
        </div>

        <!-- Témoignage 2 -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-[#fbe6c8]">
          <div class="flex items-center mb-4">
            <div class="flex text-[#f49926]">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
          </div>
          <p class="text-[#254a29]/80 italic mb-5">
            "Service exceptionnel ! J'adore que leurs produits soient 100% bio et locaux. 
            Le Saveur Cameroon avec gingembre est une tuerie. Je recommande vivement."
          </p>
          <div class="flex items-center">
            <div class="w-12 h-12 bg-[#254a29]/10 rounded-full flex items-center justify-center mr-3">
              <span class="text-[#254a29] font-semibold">KE</span>
            </div>
            <div>
              <h4 class="font-semibold text-[#254a29]">Kevin E.</h4>
              <p class="text-sm text-[#254a29]/60">Odza, Yaoundé</p>
            </div>
          </div>
        </div>

        <!-- Témoignage 3 -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-[#fbe6c8]">
          <div class="flex items-center mb-4">
            <div class="flex text-[#f49926]">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
          </div>
          <p class="text-[#254a29]/80 italic mb-5">
            "Parfait pour ma routine santé ! Les smoothies sont onctueux et nourrissants. 
            Le paiement par Mobile Money est très pratique. Une adresse incontournable à Yaoundé."
          </p>
          <div class="flex items-center">
            <div class="w-12 h-12 bg-[#254a29]/10 rounded-full flex items-center justify-center mr-3">
              <span class="text-[#254a29] font-semibold">NS</span>
            </div>
            <div>
              <h4 class="font-semibold text-[#254a29]">Nadia S.</h4>
              <p class="text-sm text-[#254a29]/60">Mvan, Yaoundé</p>
            </div>
          </div>
        </div>

      </div>

      <!-- CTA -->
      <div class="text-center mt-12">
        <Link 
          :href="route('products')" 
          class="inline-flex items-center gap-2 bg-[#f49926] hover:bg-[#254a29] text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105"
        >
          <i class="bi bi-cart-plus"></i>
          Découvrir nos produits
        </Link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

// Données des témoignages (pourrait venir du backend)
const testimonials = [
  {
    id: 1,
    name: "Aïssa M.",
    location: "Bastos, Yaoundé",
    rating: 5,
    comment: "Je commande chez Sandy Juice chaque semaine. Leurs jus sont toujours frais et délicieux. La livraison est ultra-rapide ici à Yaoundé. Mon préféré est le Tropical !",
    initials: "AM"
  },
  {
    id: 2,
    name: "Kevin E.",
    location: "Odza, Yaoundé",
    rating: 4.5,
    comment: "Service exceptionnel ! J'adore que leurs produits soient 100% bio et locaux. Le Saveur Cameroon avec gingembre est une tuerie. Je recommande vivement.",
    initials: "KE"
  },
  {
    id: 3,
    name: "Nadia S.",
    location: "Mvan, Yaoundé",
    rating: 5,
    comment: "Parfait pour ma routine santé ! Les smoothies sont onctueux et nourrissants. Le paiement par Mobile Money est très pratique. Une adresse incontournable à Yaoundé.",
    initials: "NS"
  }
];
</script>

<style scoped>
.transition-all {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>