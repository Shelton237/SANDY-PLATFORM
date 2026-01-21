<template>
  <footer class="relative bg-gradient-to-b from-[#1e3e23] to-[#254a29] text-white border-t border-[#f49926]/30">
    <div class="container mx-auto px-4 py-12">
      
      <!-- Grid principale -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
        
        <!-- Logo + description -->
        <div>
          <div class="flex items-center mb-4">
            <img src="/images/logo.png" alt="Sandy Juice" class="w-16 h-16 object-contain drop-shadow-lg" />
            <div class="ml-3">
              <h2 class="font-bold text-xl tracking-tight">SANDY JUICE</h2>
              <p class="text-xs text-gray-300 font-light">100% Naturel • Pressé à froid</p>
            </div>
          </div>
          <p class="text-gray-300 text-sm leading-relaxed max-w-xs">
            Jus naturels pressés à froid et snacks healthy pour nourrir corps et esprit au quotidien.
          </p>

          <!-- Réseaux sociaux -->
          <div class="mt-6">
            <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-300 mb-3">Suivez-nous</h4>
            <div class="flex gap-3">
              <a v-for="(social, index) in socialLinks" :key="index" :href="social.url"
                class="w-9 h-9 bg-[#f49926]/10 border border-[#f49926]/30 hover:bg-[#f49926] rounded-xl flex items-center justify-center transition-all duration-300 group"
                :aria-label="social.name">
                <i :class="social.icon" class="text-[#f49926] group-hover:text-white"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Produits -->
        <div>
          <h3 class="text-lg font-semibold mb-5 flex items-center gap-3">
            <div class="w-8 h-8 bg-[#f49926]/10 rounded-lg flex items-center justify-center">
              <i class="bi bi-cup-straw text-[#f49926]"></i>
            </div>
            <span class="bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Nos Produits</span>
          </h3>
          <ul class="space-y-3">
            <li v-for="(product, index) in products" :key="index">
              <a :href="product.url"
                class="flex items-center text-gray-300 hover:text-white text-sm py-1.5 group transition-all duration-200 hover:pl-2">
                <span class="w-1.5 h-1.5 bg-[#f49926] rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span class="group-hover:translate-x-1 transition-transform">{{ product.text }}</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Informations -->
        <div>
          <h3 class="text-lg font-semibold mb-5 flex items-center gap-3">
            <div class="w-8 h-8 bg-[#f49926]/10 rounded-lg flex items-center justify-center">
              <i class="bi bi-info-circle text-[#f49926]"></i>
            </div>
            <span class="bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Informations</span>
          </h3>
          <ul class="space-y-3">
            <li v-for="(link, index) in quickLinks" :key="index">
              <a :href="link.url"
                class="flex items-center text-gray-300 hover:text-white text-sm py-1.5 group transition-all duration-200 hover:pl-2">
                <span class="w-1.5 h-1.5 bg-[#f49926] rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span class="group-hover:translate-x-1 transition-transform">{{ link.text }}</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h3 class="text-lg font-semibold mb-5 flex items-center gap-3">
            <div class="w-8 h-8 bg-[#f49926]/10 rounded-lg flex items-center justify-center">
              <i class="bi bi-geo-alt text-[#f49926]"></i>
            </div>
            <span class="bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Contact</span>
          </h3>
          <div class="space-y-4 text-sm">
            <div class="flex items-start text-gray-300">
              <div class="w-6 h-6 bg-[#f49926]/10 rounded-full flex items-center justify-center mr-3 mt-0.5">
                <i class="bi bi-geo-alt text-[#f49926] text-xs"></i>
              </div>
              <span>Yaoundé, Cameroun</span>
            </div>
            <a href="tel:+237655699825"
              class="flex items-center text-gray-300 hover:text-white transition-colors group">
              <div class="w-6 h-6 bg-[#f49926]/10 rounded-full flex items-center justify-center mr-3">
                <i class="bi bi-telephone text-[#f49926] text-xs"></i>
              </div>
              <span class="group-hover:text-[#f49926] transition-colors">+237 6556998253</span>
            </a>
            <a href="mailto:contact@sandy-juice.com"
              class="flex items-center text-gray-300 hover:text-white transition-colors group">
              <div class="w-6 h-6 bg-[#f49926]/10 rounded-full flex items-center justify-center mr-3">
                <i class="bi bi-envelope text-[#f49926] text-xs"></i>
              </div>
              <span class="group-hover:text-[#f49926] transition-colors">contact@sandy-juice.com</span>
            </a>
          </div>

          <!-- CTA itinéraire -->
          <button
            class="mt-6 w-full bg-gradient-to-r from-[#f49926] to-[#ffb347] hover:from-[#ffb347] hover:to-[#f49926] text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl hover:shadow-[#f49926]/30 hover:-translate-y-0.5">
            <i class="bi bi-geo-alt mr-2"></i> Itinéraire
          </button>
        </div>
      </div>

      <!-- Newsletter -->
      <div
        class="mt-12 bg-gradient-to-r from-[#2a5530] to-[#1e3e23] border border-[#f49926]/30 rounded-2xl p-6 shadow-xl backdrop-blur-md">
        <div class="flex flex-col lg:flex-row items-center gap-6">
          <div class="flex-1 text-center lg:text-left">
            <h3 class="font-semibold text-xl mb-2 flex items-center justify-center lg:justify-start gap-2">
              <div class="w-8 h-8 bg-[#f49926]/10 rounded-lg flex items-center justify-center">
                <i class="bi bi-envelope text-[#f49926]"></i>
              </div>
              Restez informé
            </h3>
            <p class="text-sm text-gray-300 max-w-md">Recevez nos nouveautés, offres spéciales et conseils santé directement dans votre boîte mail.</p>
          </div>
          <div class="flex-1 w-full max-w-md">
            <div class="flex flex-col sm:flex-row gap-3">
              <input type="email" placeholder="Votre email"
                class="flex-1 bg-white/5 border border-[#f49926]/30 text-white placeholder-gray-400 text-sm py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#f49926]/50 focus:border-transparent transition-all" />
              <button
                class="bg-gradient-to-r from-[#f49926] to-[#ffb347] hover:from-[#ffb347] hover:to-[#f49926] text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-[#f49926]/30 flex items-center justify-center whitespace-nowrap">
                <i class="bi bi-envelope-open mr-2"></i> S'inscrire
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Bas de page -->
      <div
        class="mt-12 pt-8 border-t border-[#f49926]/20 flex flex-col md:flex-row justify-between items-center text-sm text-gray-300 gap-4">
        <div class="flex items-center">
          <span>© 2025 Sandy Juice. Tous droits réservés.</span>
        </div>
        <div class="flex flex-wrap gap-5 justify-center">
          <a v-for="(link, index) in legalLinks" :key="index" :href="link.url"
            class="hover:text-[#f49926] transition-colors flex items-center group">
            <span class="w-1 h-1 bg-[#f49926] rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
            {{ link.text }}
          </a>
        </div>
        <div class="flex items-center text-xs">
          <span class="mr-1">Fait avec</span>
          <i class="bi bi-heart-fill text-[#f49926] mx-1"></i>
          <span>pour votre santé</span>
        </div>
      </div>
    </div>

    <!-- Scroll top -->
    <button @click="scrollToTop"
      class="fixed bottom-6 right-6 w-12 h-12 bg-gradient-to-br from-[#f49926] to-[#ffb347] hover:from-[#ffb347] hover:to-[#f49926] text-white rounded-full flex items-center justify-center shadow-xl transition-all duration-300 hover:scale-110 z-50 group"
      aria-label="Retour en haut">
      <i class="bi bi-arrow-up group-hover:-translate-y-0.5 transition-transform"></i>
    </button>
  </footer>
</template>

<script setup>
import { ref } from 'vue'

const socialLinks = [
  { name: 'Facebook', url: '#', icon: 'bi bi-facebook' },
  { name: 'Instagram', url: '#', icon: 'bi bi-instagram' },
  { name: 'TikTok', url: '#', icon: 'bi bi-tiktok' },
  { name: 'YouTube', url: '#', icon: 'bi bi-youtube' }
]

const products = [
  { text: 'Jus Pressés', url: '/products#jus' },
  { text: 'Smoothies', url: '/products#smoothies' },
  { text: 'Snacks Healthy', url: '/products#snacks' },
  { text: 'Accompagnements', url: '/products#accompagnements' }
]

const quickLinks = [
  { text: 'Notre histoire', url: '/about' },
  { text: 'Engagement qualité', url: '/quality' },
  { text: 'Blog santé', url: '/blog' },
  { text: 'Livraison', url: '/delivery' },
  { text: 'FAQ', url: '/faq' }
]

const legalLinks = [
  { text: 'Confidentialité', url: '/privacy' },
  { text: 'Conditions d\'utilisation', url: '/terms' },
  { text: 'Politique cookies', url: '/cookies' }
]

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>