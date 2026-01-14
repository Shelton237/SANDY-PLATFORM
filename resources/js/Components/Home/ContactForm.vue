<template>
  <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
    <!-- Form Header -->
    <div class="text-center mb-5">
      <h2 class="text-xl font-medium text-gray-800 mb-1">
        Démarrez votre campagne
      </h2>
    </div>
    
    <!-- Form -->
    <form @submit.prevent="submitForm" class="space-y-4">
      <div class="relative">
        <input
          v-model="form.name"
          type="text"
          placeholder="Nom Entreprise"
          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:border-[#47AD4D] focus:ring-1 focus:ring-[#47AD4D]/20 transition-colors text-sm"
          required
        />
      </div>
      
      <div class="relative">
        <input
          v-model="form.email"
          type="email"
          placeholder="Email professionnel"
          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:border-[#47AD4D] focus:ring-1 focus:ring-[#47AD4D]/20 transition-colors text-sm"
          required
        />
      </div>
      
      <div class="relative">
        <input
          v-model="form.phone"
          type="tel"
          placeholder="Téléphone"
          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:border-[#47AD4D] focus:ring-1 focus:ring-[#47AD4D]/20 transition-colors text-sm"
          required
        />
      </div>
      
      <div class="relative">
        <select
          v-model="form.business"
          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:border-[#47AD4D] focus:ring-1 focus:ring-[#47AD4D]/20 transition-colors appearance-none text-sm"
          required
        >
          <option value="" disabled selected>Secteur d'activité</option>
          <option>Commerce</option>
          <option>Services</option>
          <option>Hôtellerie</option>
          <option>Santé</option>
          <option>Immobilier</option>
          <option>Autre</option>
        </select>
      </div>
      
      <div class="flex items-start space-x-2 p-2 bg-gray-100 rounded-lg">
        <input
          v-model="form.consent"
          type="checkbox"
          id="consent"
          class="mt-0.5 w-4 h-4 text-[#47AD4D] bg-white border-gray-300 rounded focus:ring-[#47AD4D] focus:ring-1 flex-shrink-0"
          required
        />
        <label for="consent" class="text-xs text-gray-600 leading-relaxed">
          J'accepte la <a href="#" class="text-[#47AD4D] hover:text-[#3c9a41] font-medium underline">politique de confidentialité</a>
        </label>
      </div>
      
      <button
        type="submit"
        :disabled="isSubmitting"
        class="w-full bg-[#47AD4D] hover:bg-[#3c9a41] disabled:bg-[#47AD4D]/70 text-white font-medium py-2.5 px-4 rounded-lg text-sm transition-colors duration-200 flex items-center justify-center"
      >
        <span v-if="!isSubmitting">Recevoir le devis gratuitement</span>
        <span v-else class="flex items-center">
          <i class="fas fa-spinner fa-spin mr-2"></i> Envoi...
        </span>
      </button>
    </form>
    
    <p class="text-xs text-gray-500 text-center mt-3">
      Données sécurisées et confidentielles
    </p>
  </div>
</template>

<script>
export default {
  name: 'ContactForm',
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        business: '',
        consent: false
      },
      isSubmitting: false
    }
  },
  methods: {
    submitForm() {
      this.isSubmitting = true;
      this.$emit('form-submit', this.form);
      
      // Simulation d'envoi
      setTimeout(() => {
        this.isSubmitting = false;
        this.form = {
          name: '',
          email: '',
          phone: '',
          business: '',
          consent: false
        };
        this.$emit('form-success');
      }, 2000);
    }
  },
  emits: ['form-submit', 'form-success']
}
</script>