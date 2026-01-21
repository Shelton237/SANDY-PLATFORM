<template>
  <section class="py-16 md:py-24 bg-gradient-to-b from-[#FFF5F0] to-white">
    <div class="container mx-auto px-4 max-w-6xl">
      <!-- En-tête de section -->
      <div class="text-center mb-16 md:mb-20">
        <h2 class="text-4xl md:text-5xl font-bold text-[#0E4C59] mb-6">
          Rejoignez <span class="text-[#FF6B35]">notre équipe</span>
        </h2>
        <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto">
          Accélérez la foodtech africaine et rejoignez l’atelier qui transforme la filière jus naturels
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Section Avantages -->
        <div>
          <h3 class="text-3xl font-bold text-[#0E4C59] mb-8">
            Pourquoi travailler <span class="text-[#FF6B35]">chez Sandy Platform</span> ?
          </h3>

          <!-- Liste des avantages -->
          <div class="space-y-6">
            <!-- Stabilité -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-[#0E4C59] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-shield-check"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-[#0E4C59] mb-2">Impact terrain</h3>
                <p class="text-gray-600">
                  Vous participez directement à la valorisation des producteurs locaux et à la mise sur le marché de recettes signature.
                </p>
              </div>
            </div>

            <!-- Formation -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-[#33D0C3] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-mortarboard"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-[#0E4C59] mb-2">Apprentissage continu</h3>
                <p class="text-gray-600">
                  Sessions R&D, certifications HACCP et coaching leadership pour faire grandir votre parcours.
                </p>
              </div>
            </div>

            <!-- Esprit d'équipe -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-[#FF6B35] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-people-fill"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-[#0E4C59] mb-2">Esprit d'équipe</h3>
                <p class="text-gray-600">
                  Une communauté atelier/logistique soudée, rituels hebdos et partages d’expérience inter-équipe.
                </p>
              </div>
            </div>

            <!-- Avantages supplémentaires -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-gradient-to-r from-[#0E4C59] to-[#33D0C3] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-heart"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold text-[#0E4C59] mb-2">Équilibre vie pro/perso</h3>
                <p class="text-gray-600">
                  Horaires maîtrisés, tournées regroupées et jours off garantis après chaque campagne.
                </p>
              </div>
            </div>
          </div>

          <!-- Statistiques -->
          <div class="grid grid-cols-3 gap-6 mt-10 pt-10 border-t border-gray-200">
            <div class="text-center">
              <div class="text-3xl font-bold text-[#FF6B35] mb-2">250+</div>
              <div class="text-sm text-gray-600">Collaborateurs</div>
            </div>
            <div class="text-center">
              <div class="text-3xl font-bold text-[#33D0C3] mb-2">98%</div>
              <div class="text-sm text-gray-600">Satisfaction</div>
            </div>
            <div class="text-center">
              <div class="text-3xl font-bold text-[#0E4C59] mb-2">2+</div>
              <div class="text-sm text-gray-600">Agences</div>
            </div>
          </div>
        </div>

        <!-- Section Formulaire/CTA -->
        <div class="bg-white rounded-2xl p-8 md:p-10 shadow-xl border border-gray-100">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-[#0E4C59] mb-4">Prêt à nous rejoindre ?</h3>
            <p class="text-gray-600">
              Postulez dès aujourd'hui et devenez acteur d'un service essentiel à nos communautés
            </p>
          </div>

          <!-- Postes disponibles -->
          <div class="mb-8">
            <h3 class="font-bold text-[#0E4C59] mb-4">Postes disponibles :</h3>
            <div class="space-y-3">
              <div class="flex items-center justify-between bg-[#FFF5F0] p-4 rounded-lg">
                <div>
                  <div class="font-semibold">Chef·fe d'Atelier Pressage</div>
                  <div class="text-sm text-gray-600">Yaoundé, Douala</div>
                </div>
                <span class="bg-[#FF6B35] text-white text-xs px-3 py-1 rounded-full">5 postes</span>
              </div>
              <div class="flex items-center justify-between bg-[#FFF5F0] p-4 rounded-lg">
                <div>
                  <div class="font-semibold">Community & Sampling Lead</div>
                  <div class="text-sm text-gray-600">Yaoundé, Douala</div>
                </div>
                <span class="bg-[#33D0C3] text-white text-xs px-3 py-1 rounded-full">3 postes</span>
              </div>
              <div class="flex items-center justify-between bg-[#FFF5F0] p-4 rounded-lg">
                <div>
                  <div class="font-semibold">Responsable Logistique Cold Chain</div>
                  <div class="text-sm text-gray-600">Douala, Kribi</div>
                </div>
                <span class="bg-[#0E4C59] text-white text-xs px-3 py-1 rounded-full">4 postes</span>
              </div>
            </div>
          </div>

          <!-- CTA Principal -->
          <Link 
            :href="route('recruitment')" 
            class="w-full bg-gradient-to-r from-[#FF6B35] to-[#E55A2B] text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center text-lg"
          >
            <i class="bi bi-send-fill mr-3"></i>
            Postuler maintenant
          </Link>

          <!-- Options alternatives -->
          <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm mb-4">Ou contactez-nous directement :</p>
            <div class="flex flex-col sm:flex-row gap-3">
              <a href="mailto:talents@sandy-juice.com" class="flex items-center justify-center text-[#0E4C59] hover:text-[#FF6B35] transition-colors">
                <i class="bi bi-envelope mr-2"></i>
                talents@sandy-juice.com
              </a>
              <a href="tel:+237655699825" class="flex items-center justify-center text-[#0E4C59] hover:text-[#FF6B35] transition-colors">
                <i class="bi bi-telephone mr-2"></i>
                +237 655 69 98 25
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Témoignage collaborateur -->
      <div class="mt-20 bg-gradient-to-r from-[#0E4C59] to-[#2BB2A6] rounded-2xl p-10 text-white text-center">
        <div class="max-w-3xl mx-auto">
          <i class="bi bi-quote text-4xl opacity-50 mb-6"></i>
          <p class="text-xl md:text-2xl italic mb-6">
            "Chez Sandy Platform, j’ai découvert une équipe qui mixe rigueur industrielle et énergie créative. Chaque lot raconte une histoire locale."
          </p>
          <div class="flex items-center justify-center">
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mr-4">
              <i class="bi bi-person-circle"></i>
            </div>
            <div class="text-left">
              <div class="font-bold">Martine N.</div>
              <div class="text-sm opacity-80">Auxiliaire de vie depuis 2 ans</div>
            </div>
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
/* Animation d'apparition */
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

.grid > div {
  animation: fadeInUp 0.8s ease-out both;
}

.grid > div:nth-child(1) {
  animation-delay: 0.2s;
}

.grid > div:nth-child(2) {
  animation-delay: 0.4s;
}
</style>
