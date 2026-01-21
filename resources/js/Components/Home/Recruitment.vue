<template>
  <section class="py-16 md:py-24 bg-gradient-to-b from-[#FFF5F0] to-white">
    <div class="container mx-auto px-4 max-w-6xl">
      <!-- En-tête de section -->
      <div class="text-center mb-16 md:mb-20">
        <h2 class="text-4xl md:text-5xl font-bold text-[#0E4C59] mb-6">
          Rejoignez <span class="text-[#FF6B35]">notre équipe</span>
        </h2>
        <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto">
          Faites carrière dans les services à domicile et participez à une aventure humaine extraordinaire
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Section Avantages -->
        <div>
          <h3 class="text-3xl font-bold text-[#0E4C59] mb-8">
            Pourquoi travailler <span class="text-[#FF6B35]">chez USRA CARE</span> ?
          </h3>

          <!-- Liste des avantages -->
          <div class="space-y-6">
            <!-- Stabilité -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-[#0E4C59] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-shield-check"></i>
              </div>
              <div>
                <h4 class="text-xl font-bold text-[#0E4C59] mb-2">Stabilité professionnelle</h4>
                <p class="text-gray-600">
                  Contrats durables, rémunération attractive et planning régulier pour une sérénité au quotidien.
                </p>
              </div>
            </div>

            <!-- Formation -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-[#33D0C3] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-mortarboard"></i>
              </div>
              <div>
                <h4 class="text-xl font-bold text-[#0E4C59] mb-2">Formation continue</h4>
                <p class="text-gray-600">
                  Formations certifiantes régulières pour développer vos compétences et évoluer dans votre carrière.
                </p>
              </div>
            </div>

            <!-- Esprit d'équipe -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-[#FF6B35] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-people-fill"></i>
              </div>
              <div>
                <h4 class="text-xl font-bold text-[#0E4C59] mb-2">Esprit d'équipe</h4>
                <p class="text-gray-600">
                  Une communauté bienveillante où l'entraide et le partage d'expérience sont au cœur de notre culture.
                </p>
              </div>
            </div>

            <!-- Avantages supplémentaires -->
            <div class="flex items-start">
              <div class="w-14 h-14 bg-gradient-to-r from-[#0E4C59] to-[#33D0C3] rounded-xl flex items-center justify-center text-white text-xl mr-4 flex-shrink-0">
                <i class="bi bi-heart"></i>
              </div>
              <div>
                <h4 class="text-xl font-bold text-[#0E4C59] mb-2">Équilibre vie pro/perso</h4>
                <p class="text-gray-600">
                  Horaires adaptés, proximité géographique et congés payés pour concilier travail et vie personnelle.
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
            <h4 class="font-bold text-[#0E4C59] mb-4">Postes disponibles :</h4>
            <div class="space-y-3">
              <div class="flex items-center justify-between bg-[#FFF5F0] p-4 rounded-lg">
                <div>
                  <div class="font-semibold">Auxiliaire de Vie</div>
                  <div class="text-sm text-gray-600">Yaoundé, Douala, Antananarivo</div>
                </div>
                <span class="bg-[#FF6B35] text-white text-xs px-3 py-1 rounded-full">5 postes</span>
              </div>
              <div class="flex items-center justify-between bg-[#FFF5F0] p-4 rounded-lg">
                <div>
                  <div class="font-semibold">Garde d'Enfants</div>
                  <div class="text-sm text-gray-600">Yaoundé, Antananarivo</div>
                </div>
                <span class="bg-[#33D0C3] text-white text-xs px-3 py-1 rounded-full">3 postes</span>
              </div>
              <div class="flex items-center justify-between bg-[#FFF5F0] p-4 rounded-lg">
                <div>
                  <div class="font-semibold">Agent d'Entretien</div>
                  <div class="text-sm text-gray-600">Douala, Antananarivo</div>
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
              <a href="mailto:recrutement@usracare.com" class="flex items-center justify-center text-[#0E4C59] hover:text-[#FF6B35] transition-colors">
                <i class="bi bi-envelope mr-2"></i>
                recrutement@usracare.com
              </a>
              <a href="tel:+237699999999" class="flex items-center justify-center text-[#0E4C59] hover:text-[#FF6B35] transition-colors">
                <i class="bi bi-telephone mr-2"></i>
                +237 699 999 999
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
            "Chez USRA CARE, j'ai trouvé bien plus qu'un emploi : une véritable vocation. La formation continue et l'esprit d'équipe font toute la différence."
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