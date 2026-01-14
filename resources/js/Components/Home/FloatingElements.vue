<template>
  <!-- WhatsApp Button -->
  <div class="fixed bottom-6 right-6 z-20 animate-bounce">
    <a href="https://wa.me/your-number" target="_blank" class="bg-ads-green hover:bg-ads-dark-green text-white px-4 py-2 rounded-full flex items-center space-x-2 shadow-lg transition-all duration-300 transform hover:scale-105 group text-sm">
      <i class="fab fa-whatsapp"></i>
      <span class="font-medium">Contact WhatsApp</span>
    </a>
  </div>

  <!-- Scroll Indicator -->
  <div class="fixed bottom-0 left-0 w-full h-1 z-20">
    <div class="h-full bg-ads-green transition-all duration-300" :style="{ width: scrollProgress + '%' }"></div>
  </div>
</template>

<script>
export default {
  name: 'FloatingElements',
  data() {
    return {
      scrollProgress: 0
    }
  },
  mounted() {
    window.addEventListener('scroll', this.updateScrollProgress);
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.updateScrollProgress);
  },
  methods: {
    updateScrollProgress() {
      const windowHeight = window.innerHeight;
      const documentHeight = document.documentElement.scrollHeight - windowHeight;
      const scrolled = window.scrollY;
      this.scrollProgress = (scrolled / documentHeight) * 100;
    }
  }
}
</script>