<template>
  <AppLayout>
    <Head>
      <title>Sandy Juice - Jus naturels & pipeline industriel</title>
      <meta
        name="description"
        content="Pilotez l’approvisionnement, la production et la livraison de vos jus naturels avec Sandy Platform."
      />
      <meta property="og:title" content="Sandy Juice - Pipeline complet pour l’usine de jus" />
      <meta property="og:description" content="Approvisionnement → Stockage → Production → Commercialisation → Livraison." />
      <meta property="og:image" content="/images/logo.png" />
      <meta property="og:url" :content="currentUrl" />
      <meta property="twitter:card" content="summary_large_image" />
      <link rel="canonical" :href="currentUrl" />
    </Head>

    <div class="relative min-h-screen overflow-hidden">
      <main class="relative">
        <HeroContent :hero="heroContent" />
        <FeaturedProducts :products="products" />
        <CategorySpotlight :categories="categories" />
      </main>
      <Testimonials />
    </div>
  </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import HeroContent from '@/Components/Home/HeroContent.vue'
import CategorySpotlight from '@/Components/Home/CategorySpotlight.vue'
import FeaturedProducts from '@/Components/Home/FeaturedProducts.vue'
import Testimonials from '@/Components/Home/Testimonials.vue'

const props = defineProps({
  hero: {
    type: Object,
    default: () => ({}),
  },
  featuredProducts: {
    type: Array,
    default: () => [],
  },
  featuredCategories: {
    type: Array,
    default: () => [],
  },
})

const heroContent = computed(() => props.hero ?? {})
const products = computed(() => props.featuredProducts ?? [])
const categories = computed(() => props.featuredCategories ?? [])
const currentUrl = ref('')

onMounted(() => {
  currentUrl.value = window.location.href
})
</script>
