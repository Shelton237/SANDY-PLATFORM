<template>
  <AppLayout>
    <SeoHead
      title="Jus naturels & pipeline industriel"
      description="Pilotez l'approvisionnement, la production et la livraison de vos jus naturels avec Sandy Platform."
      :image="craftImages[0]?.src"
      :structured-data="homeStructuredData"
    />

    <div class="relative min-h-screen overflow-hidden">
      <main class="relative">
        <HeroContent :hero="heroContent" />
        <FeaturedProducts :products="products" />
        <CategorySpotlight :categories="categories" />
        <ImageMosaic
          eyebrow="Immersion"
          title="Des images pour chaque Atape du pipeline"
          subtitle="Approvisionnement, stockage, production, contrA le et livraison sont documentAs pour rassurer nos clients B2B et particuliers."
          :items="craftImages"
        >
          <template #cta>
            <span class="text-xs uppercase tracking-[0.3em] text-slate-400">YaoundA a Douala a Kribi</span>
          </template>
        </ImageMosaic>
      </main>
      <Testimonials />
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import HeroContent from '@/Components/Home/HeroContent.vue'
import CategorySpotlight from '@/Components/Home/CategorySpotlight.vue'
import FeaturedProducts from '@/Components/Home/FeaturedProducts.vue'
import Testimonials from '@/Components/Home/Testimonials.vue'
import ImageMosaic from '@/Components/Common/ImageMosaic.vue'
import SeoHead from '@/Components/Common/SeoHead.vue'

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

const page = usePage()
const heroContent = computed(() => props.hero ?? {})
const products = computed(() => props.featuredProducts ?? [])
const categories = computed(() => props.featuredCategories ?? [])
const craftImages = computed(() => [
  {
    badge: 'Sourcing',
    title: 'MarchA de Nkolndongo',
    caption: 'SAlection quotidienne des fruits et tubercules',
    src: '/images/publication/gingembre.jpg',
    size: 'large'
  },
  {
    badge: 'PrAparation',
    title: 'Bissap maison',
    caption: 'Infusion contrA lAe avant pressage',
    src: '/images/publication/bissap-bienfaits-infusion-hibiscus.jpg',
    tall: true
  },
  {
    badge: 'Atelier',
    title: 'Assemblage',
    caption: 'Assemblages-minute pour prAserver les vitamines',
    src: '/images/publication/pineapple-ginger-juice.webp'
  },
  {
    badge: 'Livraison',
    title: 'Cold chain',
    caption: 'Camions rAfrigArAs prAats au dApart',
    src: 'https://images.unsplash.com/photo-1487412720507-7da8ee3b6747?auto=format&fit=crop&w=900&q=80'
  }
])
const baseUrl = computed(() => page.props.seo?.base_url || window.location.origin)
const homeStructuredData = computed(() => ({
  '@context': 'https://schema.org',
  '@type': 'Organization',
  name: 'Sandy Juice',
  url: baseUrl.value,
  logo: '/images/logo.png',
  sameAs: ['https://www.facebook.com', 'https://www.tiktok.com/@sandyjuice'],
}))
</script>
