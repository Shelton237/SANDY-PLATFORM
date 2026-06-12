<template>
  <div class="app-layout min-h-screen flex flex-col bg-gray-50">
    <NotificationCenter v-if="shouldShowNotifications" />
    <NavigationHeader 
        @menu-toggle="handleMenuToggle"
      />
    <slot />
    <Footer v-if="footerVisible" />
    <div ref="footerSentinel" class="h-1 w-full" aria-hidden="true"></div>
    
    <!-- Retour en haut amélioré -->
    <transition
      name="scroll-button"
      appear
    >
      <button 
        v-show="showScrollButton"
        @click="scrollToTop"
        class="fixed bottom-16 right-4 sm:bottom-8 sm:right-8 bg-primary text-white p-3 rounded-xl shadow-xl hover:bg-primary-dark transition-all z-40"
        aria-label="Retour en haut"
      >
        <i class="bi bi-arrow-up"></i>
      </button>
    </transition>

    <a
      href="https://wa.me/237655699825"
      target="_blank"
      rel="noopener noreferrer"
      class="fixed bottom-24 right-4 sm:bottom-20 sm:right-6 z-40 hidden sm:inline-flex items-center gap-2 rounded-full bg-[#25D366] px-4 py-3 text-white font-semibold shadow-lg hover:bg-[#1eb053]"
    >
      <i class="bi bi-whatsapp text-xl"></i>
      Commander sur WhatsApp
    </a>
    <a
      href="https://wa.me/237655699825"
      target="_blank"
      rel="noopener noreferrer"
      class="fixed bottom-16 right-4 z-40 sm:hidden inline-flex items-center justify-center rounded-full bg-[#25D366] p-4 text-white shadow-lg hover:bg-[#1eb053]"
      aria-label="Commander sur WhatsApp"
    >
      <span class="sr-only">Commander sur WhatsApp</span>
      <i class="bi bi-whatsapp text-2xl"></i>
    </a>

    <PushNotificationBanner />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineAsyncComponent, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import NavigationHeader from '@/Components/Home/NavigationHeader.vue'

const Footer = defineAsyncComponent(() => import('@/Components/Home/Footer.vue'))
const NotificationCenter = defineAsyncComponent(() => import('@/Components/NotificationCenter.vue'))
const PushNotificationBanner = defineAsyncComponent(() => import('@/Components/PushNotificationBanner.vue'))

const showScrollButton = ref(false)
const footerVisible = ref(false)
const footerSentinel = ref(null)
const shouldShowNotifications = ref(false)
let footerObserver = null
const page = usePage()
const flash = computed(() => page.props?.flash || {})

// Optimisation du scroll avec debounce
let scrollTimeout = null
const checkScroll = () => {
  clearTimeout(scrollTimeout)
  scrollTimeout = setTimeout(() => {
    showScrollButton.value = window.scrollY > 300
  }, 50)
}

// Gestion des transitions
onMounted(() => {
  window.addEventListener('scroll', checkScroll, { passive: true })
  initFooterObserver()
})

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll)
  clearTimeout(scrollTimeout)
  if (footerObserver) {
    footerObserver.disconnect()
    footerObserver = null
  }
})

const handleMenuToggle = () => {
  console.log('Mobile menu toggled');
};

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

const initFooterObserver = () => {
  if (footerVisible.value) {
    return
  }

  if (window.IntersectionObserver && footerSentinel.value) {
    footerObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            footerVisible.value = true
            footerObserver?.disconnect()
          }
        })
      },
      { rootMargin: '200px' }
    )

    footerObserver.observe(footerSentinel.value)
  } else {
    footerVisible.value = true
  }
}

watch(
  flash,
  (value) => {
    if (!value) {
      return
    }

    if (value.success || value.error || value.info) {
      shouldShowNotifications.value = true
    }
  },
  { immediate: true, deep: true }
)
</script>

<style scoped>
/* Transition des pages */
.page-enter-active,
.page-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backface-visibility: hidden;
  perspective: 1000px;
}

.page-enter-from {
  opacity: 0;
  transform: translateY(8px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

/* Transition du bouton scroll */
.scroll-button-enter-active,
.scroll-button-leave-active {
  transition: all 0.3s ease-out;
}

.scroll-button-enter-from,
.scroll-button-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Optimisation pour les appareils mobiles */
@media (max-width: 640px) {
  .page-enter-active,
  .page-leave-active {
    transition-duration: 0.25s;
  }
  
  .scroll-button-enter-active,
  .scroll-button-leave-active {
    transition-duration: 0.2s;
  }
}

/* Désactivation des animations si préféré */
@media (prefers-reduced-motion: reduce) {
  .page-enter-active,
  .page-leave-active,
  .scroll-button-enter-active,
  .scroll-button-leave-active {
    transition: none !important;
  }
  
  .page-enter-from,
  .page-leave-to,
  .scroll-button-enter-from,
  .scroll-button-leave-to {
    opacity: 1 !important;
    transform: none !important;
  }
}
</style>
