<template>
  <!-- Bouton flottant du chatbot avec animations -->
  <div class="fixed bottom-6 right-6 z-50">
    <button 
      v-if="!chatOpen"
      @click="openChat"
      class="w-16 h-16 rounded-full bg-[#2AB9AF] shadow-lg flex items-center justify-center text-white hover:scale-110 transition-all duration-300 relative"
      :class="{'animate-pulse-ring': !userInteracted}"
      @mouseenter="userInteracted = true"
    >
      <div class="absolute inset-0 rounded-full bg-[#2AB9AF] animate-ping opacity-75" v-if="!userInteracted"></div>
      <i class="bi bi-chat-dots text-xl relative z-10"></i>
      <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-xs animate-bounce" v-if="!userInteracted">!</span>
    </button>

    <!-- Fenêtre de chat avec animation -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      leave-active-class="transition-all duration-200 ease-in"
      enter-from-class="opacity-0 scale-95 translate-y-10"
      enter-to-class="opacity-100 scale-100 translate-y-0"
      leave-from-class="opacity-100 scale-100 translate-y-0"
      leave-to-class="opacity-0 scale-95 translate-y-10"
    >
      <div v-if="chatOpen" class="w-80 h-96 bg-white rounded-xl shadow-2xl flex flex-col border border-gray-200 transform origin-bottom-right">
        <!-- En-tête -->
        <div class="bg-[#2AB9AF] p-4 rounded-t-xl text-white flex justify-between items-center">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center animate-bounce-slow">
              <i class="bi bi-robot text-[#33D0C3]"></i>
            </div>
            <div>
              <span class="font-semibold">Assistant USRA-CARE</span>
              <p class="text-xs opacity-80">En ligne • Prêt à vous aider</p>
            </div>
          </div>
          <button @click="closeChat" class="text-white hover:text-gray-200 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <!-- Zone de messages -->
        <div class="flex-1 p-4 overflow-y-auto bg-gray-50 scroll-smooth" ref="messagesContainer">
          <div v-for="(message, index) in messages" :key="index" class="mb-4">
            <!-- Message du bot ou de l'utilisateur -->
            <transition
              appear
              enter-active-class="transition-all duration-300 ease-out"
              enter-from-class="opacity-0 translate-x-4"
              enter-to-class="opacity-100 translate-x-0"
            >
              <template v-if="message.sender === 'bot'">
                <div class="flex items-start space-x-2">
                  <div class="w-8 h-8 rounded-full bg-[#33D0C3] flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="bi bi-robot text-white text-sm"></i>
                  </div>
                  <div class="bg-white border border-gray-200 rounded-xl rounded-tl-none px-4 py-2 max-w-xs shadow-sm">
                    <p class="text-gray-800 text-sm">{{ message.text }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ message.time }}</p>
                  </div>
                </div>
              </template>
              <template v-else>
                <div class="flex items-start justify-end space-x-2">
                  <div class="bg-gradient-to-r from-[#33D0C3] to-[#2AB9AF] rounded-xl rounded-tr-none px-4 py-2 max-w-xs shadow-sm">
                    <p class="text-white text-sm">{{ message.text }}</p>
                    <p class="text-xs text-white opacity-80 mt-1">{{ message.time }}</p>
                  </div>
                </div>
              </template>
            </transition>
          </div>

          <!-- Formulaire de devis à la fin de la conversation -->
          <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
          >
            <div v-if="showQuoteForm" class="bg-white border border-gray-200 rounded-xl p-4 mt-4 shadow-sm">
              <h4 class="font-semibold text-[#0E4C59] mb-3 text-sm">Finalisons votre demande de devis</h4>
              
              <div class="space-y-3">
                <input 
                  v-model="quoteForm.name"
                  placeholder="Votre nom complet *"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#33D0C3] focus:border-transparent"
                  :class="{'border-red-300': quoteErrors.name}"
                >
                
                <input 
                  v-model="quoteForm.phone"
                  placeholder="Votre téléphone *"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#33D0C3] focus:border-transparent"
                  :class="{'border-red-300': quoteErrors.phone}"
                >
                
                <input 
                  v-model="quoteForm.email"
                  placeholder="Votre email *"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#33D0C3] focus:border-transparent"
                  :class="{'border-red-300': quoteErrors.email}"
                >
                
                <div class="flex space-x-2">
                  <button 
                    @click="submitQuote"
                    class="flex-1 bg-[#33D0C3] text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-[#2ab9af] transition-colors"
                  >
                    Obtenir mon devis
                  </button>
                  <button 
                    @click="showQuoteForm = false"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors"
                  >
                    Annuler
                  </button>
                </div>
              </div>
            </div>
          </transition>
        </div>

        <!-- Suggestions de services -->
        <transition
          enter-active-class="transition-all duration-300 ease-out"
          enter-from-class="opacity-0 translate-y-4"
          enter-to-class="opacity-100 translate-y-0"
        >
          <div v-if="showServiceSuggestions" class="px-4 py-3 bg-white border-t border-gray-100">
            <p class="text-xs text-gray-500 mb-2 font-medium">Choisissez un service :</p>
            <div class="flex flex-wrap gap-2">
              <button 
                v-for="service in services" 
                :key="service.id"
                @click="selectService(service)"
                class="px-3 py-1.5 bg-gray-100 hover:bg-[#33D0C3] hover:text-white rounded-full text-xs transition-all duration-200 transform hover:scale-105 shadow-sm"
              >
                {{ service.name }}
              </button>
            </div>
          </div>
        </transition>

        <!-- Zone de saisie -->
        <div v-if="!showQuoteForm" class="p-4 border-t border-gray-100 bg-white rounded-b-xl">
          <div class="flex space-x-2">
            <input 
              v-model="userInput" 
              @keyup.enter="sendMessage"
              placeholder="Tapez votre message..." 
              class="flex-1 border border-gray-200 rounded-full px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#33D0C3] focus:border-transparent transition-all"
              :class="{'ring-2 ring-[#33D0C3]': userInput}"
            >
            <button 
              @click="sendMessage"
              class="w-11 h-11 rounded-full bg-[#2AB9AF] flex items-center justify-center text-white shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105"
              :disabled="!userInput.trim()"
              :class="{'opacity-50 cursor-not-allowed': !userInput.trim()}"
            >
              <i class="bi bi-send text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'ChatBot',
  data() {
    return {
      chatOpen: false,
      userInteracted: false,
      userInput: '',
      messages: [],
      showServiceSuggestions: true,
      showQuoteForm: false,
      conversationStep: 0,
      selectedService: null,
      services: [
        { id: 1, name: 'Ménage', description: 'Nettoyage complet de votre domicile' },
        { id: 3, name: 'Garde d\'enfants', description: 'Éveil et sécurité pour vos enfants' },
        { id: 4, name: 'Auxiliaire de vie', description: 'Maintien à domicile en toute sérénité' },
        { id: 5, name: 'Jardinage', description: 'Entretien de vos espaces verts' },
        { id: 6, name: 'Bricolage', description: 'Petites réparations et travaux' }
      ],
      quoteForm: {
        name: '',
        phone: '',
        email: ''
      },
      quoteErrors: {
        name: false,
        phone: false,
        email: false
      }
    }
  },
  mounted() {
    // Message de bienvenue initial après un court délai
    setTimeout(() => {
      this.addBotMessage("Bonjour ! 👋 Je suis l'assistant USRA-CARE. Comment puis-je vous aider aujourd'hui ?");
    }, 500);
    
    // Animation d'appel à l'action après 5 secondes
    setTimeout(() => {
      if (!this.userInteracted && !this.chatOpen) {
        this.animateButton();
      }
    }, 5000);
  },
  methods: {
    openChat() {
      this.chatOpen = true;
      this.userInteracted = true;
      
      // Ajouter un léger délai pour garantir le rendu du DOM
      this.$nextTick(() => {
        this.scrollToBottom();
      });
    },
    closeChat() {
      this.chatOpen = false;
      this.showQuoteForm = false;
      this.conversationStep = 0;
    },
    sendMessage() {
      if (this.userInput.trim() === '') return;
      
      // Ajouter le message de l'utilisateur
      this.addUserMessage(this.userInput);
      
      // Gérer la conversation étape par étape
      this.handleConversation(this.userInput);
      
      // Réinitialiser l'input
      this.userInput = '';
    },
    addUserMessage(text) {
      this.messages.push({
        sender: 'user',
        text: text,
        time: this.getCurrentTime()
      });
      this.scrollToBottom();
    },
    addBotMessage(text) {
      this.messages.push({
        sender: 'bot',
        text: text,
        time: this.getCurrentTime()
      });
      this.scrollToBottom();
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.messagesContainer;
        if (container) {
          container.scrollTop = container.scrollHeight;
        }
      });
    },
    getCurrentTime() {
      return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
    selectService(service) {
      this.selectedService = service;
      this.addUserMessage(`Je suis intéressé par: ${service.name}`);
      this.showServiceSuggestions = false;
      this.conversationStep = 1;
      
      // Réponse automatique
      setTimeout(() => {
        this.addBotMessage(`Excellent choix ! Le service ${service.name} : ${service.description}. Souhaitez-vous un devis personnalisé ?`);
      }, 1000);
    },
    animateButton() {
      // Animation pour attirer l'attention
      if (!this.userInteracted && !this.chatOpen) {
        const button = document.querySelector('.animate-pulse-ring');
        if (button) {
          button.classList.add('animate-wiggle');
          setTimeout(() => {
            button.classList.remove('animate-wiggle');
          }, 1500);
        }
      }
    },
    handleConversation(userMessage) {
      // Logique de conversation basée sur les étapes
      switch(this.conversationStep) {
        case 0: // Début de conversation
          setTimeout(() => {
            this.addBotMessage("Je peux vous aider à obtenir un devis personnalisé pour nos services. Quel service vous intéresse ?");
            this.showServiceSuggestions = true;
          }, 1000);
          break;
          
        case 1: // Service sélectionné
          if (userMessage.toLowerCase().includes('oui') || userMessage.toLowerCase().includes('devis')) {
            setTimeout(() => {
              this.addBotMessage("Parfait ! Pour finaliser votre demande de devis, j'ai besoin de quelques informations :");
              this.showQuoteForm = true;
              this.conversationStep = 2;
            }, 1000);
          } else {
            setTimeout(() => {
              this.addBotMessage("D'accord. Y a-t-il autre chose que je puisse faire pour vous aider ?");
            }, 1000);
          }
          break;
          
        case 2: // Formulaire affiché
          // Le formulaire est géré séparément
          break;
      }
    },
    validateQuoteForm() {
      let isValid = true;
      
      // Réinitialiser les erreurs
      this.quoteErrors = { name: false, phone: false, email: false };
      
      // Validation du nom
      if (!this.quoteForm.name.trim()) {
        this.quoteErrors.name = true;
        isValid = false;
      }
      
      // Validation du téléphone
      if (!this.quoteForm.phone.trim()) {
        this.quoteErrors.phone = true;
        isValid = false;
      }
      
      // Validation de l'email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!this.quoteForm.email.trim() || !emailRegex.test(this.quoteForm.email)) {
        this.quoteErrors.email = true;
        isValid = false;
      }
      
      return isValid;
    },
    submitQuote() {
      if (this.validateQuoteForm()) {
        // Simuler l'envoi du formulaire
        this.addBotMessage("Merci ! Votre demande de devis a été envoyée. 🎉");
        this.addBotMessage(`Service: ${this.selectedService.name}`);
        this.addBotMessage(`Nom: ${this.quoteForm.name}`);
        this.addBotMessage("Un conseiller USRA-CARE vous contactera sous 24h pour finaliser votre devis personnalisé.");
        
        // Réinitialiser le formulaire
        this.showQuoteForm = false;
        this.conversationStep = 0;
        this.quoteForm = { name: '', phone: '', email: '' };
        
        // Optionnel: déclencher un événement pour le parent
        this.$emit('quote-submitted', {
          service: this.selectedService,
          contactInfo: this.quoteForm
        });
      } else {
        this.addBotMessage("Veuillez remplir tous les champs obligatoires correctement.");
      }
    }
  }
}
</script>

<style scoped>
/* Animation personnalisée pour le bouton */
@keyframes pulse-ring {
  0% { box-shadow: 0 0 0 0 rgba(51, 208, 195, 0.7); }
  70% { box-shadow: 0 0 0 12px rgba(51, 208, 195, 0); }
  100% { box-shadow: 0 0 0 0 rgba(51, 208, 195, 0); }
}

@keyframes bounce-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}

@keyframes wiggle {
  0%, 7% { transform: rotate(0); }
  15% { transform: rotate(-6deg); }
  20% { transform: rotate(6deg); }
  25% { transform: rotate(-4deg); }
  30% { transform: rotate(4deg); }
  35% { transform: rotate(-2deg); }
  40%, 100% { transform: rotate(0); }
}

.animate-pulse-ring {
  animation: pulse-ring 2s infinite;
}

.animate-bounce-slow {
  animation: bounce-slow 3s infinite;
}

.animate-wiggle {
  animation: wiggle 1.5s ease-in-out;
}

/* Personnalisation de la barre de défilement */
.scroll-smooth::-webkit-scrollbar {
  width: 6px;
}

.scroll-smooth::-webkit-scrollbar-track {
  background: transparent;
}

.scroll-smooth::-webkit-scrollbar-thumb {
  background-color: rgba(51, 208, 195, 0.3);
  border-radius: 20px;
}

.scroll-smooth::-webkit-scrollbar-thumb:hover {
  background-color: rgba(51, 208, 195, 0.5);
}
</style>