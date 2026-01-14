<template>
  <section class="py-4 md:py-8 bg-white">
    <div class="container mx-auto px-5 max-w-5xl">
      <!-- En-tête de section avec espacement généreux -->
      <div class="text-center mb-10 md:mb-12">
        <div class="inline-flex items-center justify-center bg-[#FFF5F0] rounded-full px-6 py-3 mb-8">
          <i class="bi bi-question-circle text-[#FF6B35] text-lg mr-3"></i>
          <span class="text-base font-semibold text-[#0E4C59]">Support & Informations</span>
        </div>
        
        <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#0E4C59] mb-8 leading-tight">
          Questions <span class="text-[#FF6B35]">fréquentes</span>
        </h2>
        
        <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Trouvez rapidement les réponses à toutes vos questions sur nos services
        </p>
      </div>
      <!-- Accordéon FAQ avec espacement sur-mesure -->
      <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Question 1 -->
        <div class="bg-white rounded-2xl overflow-hidden transition-all duration-500 shadow-lg hover:shadow-xl border border-gray-100"
             :class="{ 'ring-2 ring-[#FF6B35]': activeQuestion === 1 }">
          <button 
            @click="toggleQuestion(1)"
            class="w-full px-8 py-7 text-left flex items-center justify-between focus:outline-none group"
          >
            <div class="flex items-start space-x-5">
              <div class="w-12 h-12 bg-[#FFF5F0] rounded-xl flex items-center justify-center text-[#FF6B35] text-xl flex-shrink-0 mt-1">
                <i class="bi bi-house-door"></i>
              </div>
              <div>
                <span class="text-xl font-semibold text-[#0E4C59] group-hover:text-[#FF6B35] transition-colors duration-300">Quels services proposez-vous ?</span>
                <p class="text-gray-500 text-sm mt-2">Découvrez notre gamme complète de services à domicile</p>
              </div>
            </div>
            <i class="bi text-2xl text-[#FF6B35] transition-transform duration-500 flex-shrink-0" 
               :class="activeQuestion === 1 ? 'bi-dash-circle' : 'bi-plus-circle'"></i>
          </button>
          
          <div v-if="activeQuestion === 1" class="px-8 pb-8">
            <div class="border-t border-gray-100 pt-6">
              <div class="text-gray-700 leading-relaxed text-lg space-y-4">
                <p>Nous proposons une gamme complète de services à domicile adaptés à vos besoins :</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                  <div class="flex items-start space-x-4 p-4 bg-[#FFF5F0] rounded-xl">
                    <div class="w-10 h-10 bg-[#FF6B35] rounded-lg flex items-center justify-center text-white flex-shrink-0">
                      <i class="bi bi-broom"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-[#0E4C59]">Aide ménagère</h4>
                      <p class="text-gray-600 text-sm mt-1">Nettoyage régulier et entretien complet</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start space-x-4 p-4 bg-[#FFF5F0] rounded-xl">
                    <div class="w-10 h-10 bg-[#33D0C3] rounded-lg flex items-center justify-center text-white flex-shrink-0">
                      <i class="bi bi-balloon-heart"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-[#0E4C59]">Garde d'enfants</h4>
                      <p class="text-gray-600 text-sm mt-1">Garde bienveillante et éducative</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start space-x-4 p-4 bg-[#FFF5F0] rounded-xl">
                    <div class="w-10 h-10 bg-[#0E4C59] rounded-lg flex items-center justify-center text-white flex-shrink-0">
                      <i class="bi bi-tree"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-[#0E4C59]">Jardinage & Bricolage</h4>
                      <p class="text-gray-600 text-sm mt-1">Entretien extérieur et réparations</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start space-x-4 p-4 bg-[#FFF5F0] rounded-xl">
                    <div class="w-10 h-10 bg-[#FF6B35] rounded-lg flex items-center justify-center text-white flex-shrink-0">
                      <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-[#0E4C59]">Auxiliaire de vie</h4>
                      <p class="text-gray-600 text-sm mt-1">Accompagnement personnalisé seniors</p>
                    </div>
                  </div>
                </div>
                
                <p class="mt-6 text-gray-600">Tous nos intervenants sont qualifiés, formés régulièrement et rigoureusement sélectionnés pour vous garantir qualité et sérénité.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Question 2 -->
        <div class="bg-white rounded-2xl overflow-hidden transition-all duration-500 shadow-lg hover:shadow-xl border border-gray-100"
             :class="{ 'ring-2 ring-[#33D0C3]': activeQuestion === 2 }">
          <button 
            @click="toggleQuestion(2)"
            class="w-full px-8 py-7 text-left flex items-center justify-between focus:outline-none group"
          >
            <div class="flex items-start space-x-5">
              <div class="w-12 h-12 bg-[#E6F7F6] rounded-xl flex items-center justify-center text-[#33D0C3] text-xl flex-shrink-0 mt-1">
                <i class="bi bi-credit-card"></i>
              </div>
              <div>
                <span class="text-xl font-semibold text-[#0E4C59] group-hover:text-[#33D0C3] transition-colors duration-300">Comment se passe la facturation ?</span>
                <p class="text-gray-500 text-sm mt-2">Transparence et simplicité dans nos tarifs</p>
              </div>
            </div>
            <i class="bi text-2xl text-[#33D0C3] transition-transform duration-500 flex-shrink-0" 
               :class="activeQuestion === 2 ? 'bi-dash-circle' : 'bi-plus-circle'"></i>
          </button>
          
          <div v-if="activeQuestion === 2" class="px-8 pb-8">
            <div class="border-t border-gray-100 pt-6">
              <div class="text-gray-700 leading-relaxed text-lg space-y-6">
                <p>Notre système de facturation est conçu pour être simple et transparent :</p>
                
                <div class="bg-[#F9FDFC] p-6 rounded-xl border border-[#33D0C3]/20">
                  <h4 class="font-semibold text-[#0E4C59] mb-4">Modalités de facturation</h4>
                  <ul class="list-disc list-inside space-y-3 pl-4">
                    <li><span class="font-medium">Facturation à l'heure</span> de service prestée</li>
                    <li><span class="font-medium">Forfaits avantageux</span> disponibles selon la fréquence</li>
                    <li><span class="font-medium">Facture détaillée mensuelle</span> avec le détail des heures effectuées</li>
                    <li><span class="font-medium">Aucun frais caché</span> - tout est inclus dans nos tarifs</li>
                  </ul>
                </div>
                
                <div class="bg-[#FFF5F0] p-6 rounded-xl">
                  <h4 class="font-semibold text-[#0E4C59] mb-4">Modes de paiement acceptés</h4>
                  <div class="flex flex-wrap gap-3">
                    <span class="bg-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">Virement bancaire</span>
                    <span class="bg-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">Chèques</span>
                    <span class="bg-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">Espèces (avec reçu)</span>
                    <span class="bg-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">Mobile Money</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Question 3 -->
        <div class="bg-white rounded-2xl overflow-hidden transition-all duration-500 shadow-lg hover:shadow-xl border border-gray-100"
             :class="{ 'ring-2 ring-[#0E4C59]': activeQuestion === 3 }">
          <button 
            @click="toggleQuestion(3)"
            class="w-full px-8 py-7 text-left flex items-center justify-between focus:outline-none group"
          >
            <div class="flex items-start space-x-5">
              <div class="w-12 h-12 bg-[#E8F4F8] rounded-xl flex items-center justify-center text-[#0E4C59] text-xl flex-shrink-0 mt-1">
                <i class="bi bi-clock"></i>
              </div>
              <div>
                <span class="text-xl font-semibold text-[#0E4C59] group-hover:text-[#0E4C59] transition-colors duration-300">Quels sont vos horaires d'intervention ?</span>
                <p class="text-gray-500 text-sm mt-2">Flexibilité adaptée à vos besoins</p>
              </div>
            </div>
            <i class="bi text-2xl text-[#0E4C59] transition-transform duration-500 flex-shrink-0" 
               :class="activeQuestion === 3 ? 'bi-dash-circle' : 'bi-plus-circle'"></i>
          </button>
          
          <div v-if="activeQuestion === 3" class="px-8 pb-8">
            <div class="border-t border-gray-100 pt-6">
              <div class="text-gray-700 leading-relaxed text-lg space-y-6">
                <p>Nous nous adaptons à vos contraintes avec des plages horaires étendues :</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div class="bg-[#F0F9FF] p-6 rounded-xl">
                    <h4 class="font-semibold text-[#0E4C59] mb-4 flex items-center">
                      <i class="bi bi-calendar-check mr-3"></i>
                      Horaires standards
                    </h4>
                    <ul class="space-y-3">
                      <li class="flex justify-between">
                        <span>Lundi - Vendredi</span>
                        <span class="font-semibold text-[#0E4C59]">7h - 20h</span>
                      </li>
                      <li class="flex justify-between">
                        <span>Samedi</span>
                        <span class="font-semibold text-[#0E4C59]">8h - 18h</span>
                      </li>
                      <li class="flex justify-between">
                        <span>Dimanche</span>
                        <span class="font-semibold text-[#FF6B35]">Sur demande</span>
                      </li>
                    </ul>
                  </div>
                  
                  <div class="bg-[#FFF5F0] p-6 rounded-xl">
                    <h4 class="font-semibold text-[#0E4C59] mb-4 flex items-center">
                      <i class="bi bi-lightning-charge mr-3"></i>
                      Interventions urgentes
                    </h4>
                    <ul class="space-y-3">
                      <li class="flex items-start">
                        <i class="bi bi-check-circle text-[#33D0C3] mr-2 mt-1"></i>
                        <span>Disponibles sous 24-48h</span>
                      </li>
                      <li class="flex items-start">
                        <i class="bi bi-check-circle text-[#33D0C3] mr-2 mt-1"></i>
                        <span>Contact direct par téléphone</span>
                      </li>
                      <li class="flex items-start">
                        <i class="bi bi-check-circle text-[#33D0C3] mr-2 mt-1"></i>
                        <span>Majoration selon le créneau</span>
                      </li>
                    </ul>
                  </div>
                </div>
                
                <p class="text-sm text-gray-500 italic border-t pt-4 mt-4">
                  * Les interventions le week-end et jours fériés font l'objet de majorations tarifaires spécifiques.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const activeQuestion = ref(1);
const searchQuery = ref('');

const toggleQuestion = (index) => {
  activeQuestion.value = activeQuestion.value === index ? null : index;
};
</script>

<style scoped>
/* Animation d'ouverture/fermeture */
button {
  transition: all 0.3s ease;
}

/* Effet de survol pour les questions */
.bg-white:hover {
  transform: translateY(-2px);
}

/* Animation douce pour l'ouverture des réponses */
.border-t {
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>