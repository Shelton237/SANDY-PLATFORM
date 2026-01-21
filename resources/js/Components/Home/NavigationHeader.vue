<template>
  <header class="relative top-0 left-0 w-full bg-white/95 backdrop-blur-md shadow-lg z-50 border-b border-[#254a29]/20">
    <nav class="container mx-auto px-4 py-3">
      <div class="flex items-center justify-between">
        <!-- Logo amélioré -->
        <Link :href="route('home')" class="flex items-center group cursor-pointer transition-transform duration-300 hover:scale-105">
          <div class="flex items-center">
            <div class="relative">
              <img src="/images/logo.png" alt="Sandy Juice" class="w-16 h-16 md:w-20 md:h-20 object-contain drop-shadow-lg" />
              <!-- Badge fraîcheur -->
              <span class="absolute -top-1 -right-1 bg-[#f49926] text-white text-[10px] px-2 py-1 rounded-full font-semibold shadow-lg">
                Frais
              </span>
            </div>
            <div class="ml-3 hidden sm:block">
              <span class="font-bold text-[#254a29] text-xl md:text-2xl tracking-tight">
                SANDY JUICE
              </span>
              <span class="block text-xs text-gray-500 mt-0.5 font-light">100% Naturel • Pressé à froid</span>
            </div>
          </div>
        </Link>

        <!-- Desktop Navigation amélioré -->
        <div class="hidden lg:flex items-center space-x-1 xl:space-x-4">
          <Link 
            :href="route('home')" 
            class="px-4 py-2.5 text-gray-700 hover:text-[#254a29] font-medium rounded-xl transition-all duration-300 group/nav-item"
            :class="{ 'text-[#254a29] bg-[#f49926]/10': $page.url === '/' }"
          >
            <span class="relative">
              Accueil
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#f49926] transition-all duration-300 group-hover/nav-item:w-full"></span>
            </span>
          </Link>

          <!-- Menu déroulant NOS PRODUITS amélioré -->
          <div class="relative group" ref="productsDropdownRef">
            <button 
              @click="toggleProductsDropdown"
              class="px-4 py-2.5 text-gray-700 hover:text-[#254a29] font-medium rounded-xl transition-all duration-300 flex items-center group/nav-item"
              :class="{ 'text-[#254a29] bg-[#f49926]/10': $page.url.startsWith('/products') }"
            >
              <span class="relative">
                Nos Produits
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#f49926] transition-all duration-300 group-hover/nav-item:w-full"></span>
              </span>
              <i class="bi bi-chevron-down text-xs ml-1 transition-transform duration-300" :class="{ 'rotate-180': productsDropdownOpen }"></i>
            </button>
            
            <transition
              enter-active-class="transition-all duration-300 ease-out"
              enter-from-class="transform opacity-0 -translate-y-2"
              enter-to-class="transform opacity-100 translate-y-0"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="transform opacity-100 translate-y-0"
              leave-to-class="transform opacity-0 -translate-y-2"
            >
              <div v-if="productsDropdownOpen" class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-[#f49926]/30 py-3 z-50">
                <div class="px-3 py-2 border-b border-[#f49926]/20">
                  <span class="text-xs font-semibold text-[#254a29] uppercase tracking-wide">Catégories</span>
                </div>
                <div class="p-2">
                  <Link 
                    v-for="category in productCategories"
                    :key="category.id"
                    :href="route('products.category', category.slug)" 
                    class="flex items-center px-4 py-3 rounded-xl text-gray-700 hover:text-[#254a29] hover:bg-[#fbe6c8] transition-all duration-200 group/category"
                    :class="{ 'text-[#254a29] bg-[#fbe6c8]': $page.url === route('products.category', category.slug) }"
                    @click="closeDropdowns"
                  >
                    <div class="w-8 h-8 rounded-full bg-[#fbe6c8] flex items-center justify-center mr-3 group-hover/category:bg-[#f7a345] transition-colors">
                      <i :class="category.icon" class="text-[#254a29]"></i>
                    </div>
                    <div>
                      <span class="font-medium">{{ category.name }}</span>
                      <span class="block text-xs text-gray-500">{{ category.description }}</span>
                    </div>
                  </Link>
                </div>
              </div>
            </transition>
          </div>

          <Link 
            :href="route('about')" 
            class="px-4 py-2.5 text-gray-700 hover:text-[#254a29] font-medium rounded-xl transition-all duration-300 group/nav-item"
            :class="{ 'text-[#254a29] bg-[#f49926]/10': $page.url === route('about') }"
          >
            <span class="relative">
              Notre Histoire
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#f49926] transition-all duration-300 group-hover/nav-item:w-full"></span>
            </span>
          </Link>

          <Link 
            :href="route('blog')" 
            class="px-4 py-2.5 text-gray-700 hover:text-[#254a29] font-medium rounded-xl transition-all duration-300 group/nav-item"
            :class="{ 'text-[#254a29] bg-[#f49926]/10': $page.url === route('blog') }"
          >
            <span class="relative">
              Blog
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#f49926] transition-all duration-300 group-hover/nav-item:w-full"></span>
            </span>
          </Link>

          <Link 
            :href="route('contact')" 
            class="px-4 py-2.5 text-gray-700 hover:text-[#254a29] font-medium rounded-xl transition-all duration-300 group/nav-item"
            :class="{ 'text-[#254a29] bg-[#f49926]/10': $page.url === route('contact') }"
          >
            <span class="relative">
              Contact
              <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#f49926] transition-all duration-300 group-hover/nav-item:w-full"></span>
            </span>
          </Link>
        </div>

        <!-- CTA et Panier amélioré -->
        <div class="flex items-center space-x-3">
          <!-- Recherche (nouveau) -->
          <button @click="openSearch" class="p-2 text-gray-500 hover:text-[#254a29] transition-colors duration-300 hidden md:block">
            <i class="bi bi-search text-lg"></i>
          </button>

          <!-- Panier amélioré -->
          <Link 
            :href="route('cart')" 
            class="relative p-2.5 text-gray-600 hover:text-[#254a29] transition-all duration-300 group/cart"
            :class="{ 'text-[#254a29]': $page.url === route('cart') }"
          >
            <div class="relative">
              <i class="bi bi-cart3 text-xl group-hover/cart:scale-110 transition-transform"></i>
              <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-[#f49926] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center shadow-lg transform group-hover/cart:scale-110 transition-transform">
                {{ cartCount }}
              </span>
            </div>
          </Link>

          <!-- Compte utilisateur amélioré -->
          <div class="relative">
            <button 
              @click="showAccountDropdown = !showAccountDropdown" 
              class="flex items-center text-gray-600 hover:text-[#254a29] p-2.5 transition-colors duration-300 rounded-xl hover:bg-[#fbe6c8]"
              :class="{ 'text-[#254a29] bg-[#fbe6c8]': showAccountDropdown }"
            >
              <i class="bi bi-person-circle text-xl"></i>
            </button>
            <transition
              enter-active-class="transition-all duration-300 ease-out"
              enter-from-class="transform opacity-0 -translate-y-2"
              enter-to-class="transform opacity-100 translate-y-0"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="transform opacity-100 translate-y-0"
              leave-to-class="transform opacity-0 -translate-y-2"
            >
              <div v-if="showAccountDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-2 z-50 border border-[#f49926]/30">
                <template v-if="$page.props.auth.user">
                  <div class="px-4 py-3 border-b border-[#f49926]/20">
                    <p class="text-sm font-medium text-[#254a29]">Bonjour, {{ $page.props.auth.user.name }}</p>
                    <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                  </div>
                  <Link :href="route('account.profile')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:text-[#254a29] hover:bg-[#fbe6c8] transition-colors">
                    <i class="bi bi-person mr-3 text-[#254a29]"></i>Mon Compte
                  </Link>
                  <Link :href="route('account.orders')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:text-[#254a29] hover:bg-[#fbe6c8] transition-colors">
                    <i class="bi bi-bag mr-3 text-[#254a29]"></i>Mes Commandes
                  </Link>
                  <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 hover:text-[#254a29] hover:bg-[#fbe6c8] transition-colors">
                    <i class="bi bi-box-arrow-right mr-3 text-[#254a29]"></i>Déconnexion
                  </Link>
                </template>
                <template v-else>
                  <div class="px-4 py-3 border-b border-[#f49926]/20">
                    <p class="text-sm font-medium text-[#254a29]">Mon compte</p>
                  </div>
                  <Link :href="route('login')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:text-[#254a29] hover:bg-[#fbe6c8] transition-colors">
                    <i class="bi bi-box-arrow-in-right mr-3 text-[#254a29]"></i>Connexion
                  </Link>
                  <Link :href="route('register')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:text-[#254a29] hover:bg-[#fbe6c8] transition-colors">
                    <i class="bi bi-person-plus mr-3 text-[#254a29]"></i>Inscription
                  </Link>
                </template>
              </div>
            </transition>
          </div>
          
          <!-- Bouton CTA amélioré -->
          <Link 
            :href="route('products')" 
            class="hidden md:flex bg-[#f49926] hover:bg-[#f7a345] text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-[#f49926]/40 items-center group/cta"
          >
            <i class="bi bi-lightning-charge-fill mr-2 group-hover/cta:animate-bounce"></i>
            Commander
            <i class="bi bi-arrow-right ml-2 group-hover/cta:translate-x-1 transition-transform"></i>
          </Link>
          
          <!-- Menu Mobile Button amélioré -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="lg:hidden text-gray-600 hover:text-[#254a29] p-2.5 rounded-xl transition-colors duration-300 hover:bg-[#fbe6c8]"
            :class="{ 'text-[#254a29] bg-[#fbe6c8]': mobileMenuOpen }"
          >
            <i class="bi bi-list text-xl"></i>
          </button>
        </div>
      </div>

      <!-- Mobile Navigation amélioré -->
      <transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="transform opacity-0 -translate-y-4"
        enter-to-class="transform opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="transform opacity-100 translate-y-0"
        leave-to-class="transform opacity-0 -translate-y-4"
      >
        <div v-if="mobileMenuOpen" class="lg:hidden mt-4 bg-white rounded-2xl shadow-2xl p-5 border border-[#f49926]/30">
          <div class="space-y-2">
            <Link 
              v-for="item in mobileMenuItems"
              :key="item.name"
              :href="item.href" 
              class="flex items-center py-3 px-4 text-gray-700 hover:text-[#254a29] hover:bg-[#fbe6c8] rounded-xl transition-all duration-300 group/mobile-item"
              :class="{ 'text-[#254a29] bg-[#fbe6c8]': $page.url === item.href }"
              @click="mobileMenuOpen = false"
            >
              <i :class="item.icon" class="mr-3 text-lg text-[#254a29]"></i>
              <span class="font-medium">{{ item.name }}</span>
              <i class="bi bi-arrow-right ml-auto opacity-0 group-hover/mobile-item:opacity-100 transition-opacity text-[#254a29]"></i>
            </Link>
          </div>

          <!-- Séparateur -->
          <div class="my-4 border-t border-[#f49926]/30"></div>

          <!-- Bouton CTA mobile amélioré -->
          <Link 
            :href="route('products')" 
            class="bg-[#f49926] hover:bg-[#f7a345] text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-[#f49926]/40 flex items-center justify-center group/mobile-cta"
            @click="mobileMenuOpen = false"
          >
            <i class="bi bi-cart-plus mr-2 group-hover/mobile-cta:animate-bounce"></i>
            Commander maintenant
          </Link>
        </div>
      </transition>
    </nav>
  </header>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';

// État du menu mobile
const mobileMenuOpen = ref(false);

// États des dropdowns desktop
const productsDropdownOpen = ref(false);
const showAccountDropdown = ref(false);

// Références aux éléments dropdown
const productsDropdownRef = ref(null);

// États des dropdowns mobile
const mobileDropdown = ref(null);

// Données des catégories de produits

const page = usePage()

const productCategories = computed(() => {
  const categories = page.props.value?.navCategories ?? page.props.navCategories
  return Array.isArray(categories) ? categories : []
})

// Menu mobile
const mobileMenuItems = computed(() => [
  { name: 'Accueil', href: route('home'), icon: 'bi bi-house' },
  { name: 'Nos Produits', href: route('products'), icon: 'bi bi-grid' },
  { name: 'Notre Histoire', href: route('about'), icon: 'bi bi-info-circle' },
  { name: 'Blog', href: route('blog'), icon: 'bi bi-journal' },
  { name: 'Contact', href: route('contact'), icon: 'bi bi-envelope' }
])

// Compteur du panier
const cartCount = computed(() => Number(page.props.cartCount ?? page.props.value?.cartCount ?? 0));

// Fonction pour ouvrir la recherche
const openSearch = () => {
  // Implémentation de la recherche
  console.log('Ouvrir la recherche');
};

// Fonction pour détecter les clics à l'extérieur
const handleClickOutside = (event) => {
  if (productsDropdownRef.value && !productsDropdownRef.value.contains(event.target)) {
    productsDropdownOpen.value = false;
  }
  
  if (!event.target.closest('.relative')) {
    showAccountDropdown.value = false;
  }
};

// Ajouter et supprimer l'écouteur d'événements
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Fonctions pour les dropdowns desktop
const toggleProductsDropdown = (event) => {
  event.stopPropagation();
  productsDropdownOpen.value = !productsDropdownOpen.value;
};

const closeDropdowns = () => {
  productsDropdownOpen.value = false;
  showAccountDropdown.value = false;
};

// Fonctions pour les dropdowns mobile
const toggleMobileDropdown = (dropdown) => {
  mobileDropdown.value = mobileDropdown.value === dropdown ? null : dropdown;
};
</script>

<style scoped>
/* Animation pour l'ouverture du menu mobile */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Rotation des icônes de dropdown */
.rotate-180 {
  transform: rotate(180deg);
}

/* Amélioration du backdrop blur pour le header */
.backdrop-blur-md {
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

/* Responsive design amélioré */
@media (max-width: 768px) {
  .container {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
  
  .grid {
    gap: 2.5rem;
  }
}
</style>
