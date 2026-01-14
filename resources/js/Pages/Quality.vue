<template>
  <AppLayout>
    <SeoHead
      :title="title"
      :description="description"
      image="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1000&q=80"
      :structured-data="qualityStructuredData"
    />

    <section class="bg-gradient-to-br from-[#fef7ee] via-white to-[#f0faf2]">
      <div class="container mx-auto px-4 py-16 lg:py-20 grid lg:grid-cols-[3fr,2fr] gap-12 items-center">
        <div class="space-y-5">
          <p class="text-sm uppercase tracking-[0.3em] text-amber-500">Demarche qualite</p>
          <h1 class="text-4xl font-bold text-emerald-900">{{ title }}</h1>
          <p class="text-lg text-gray-600 leading-relaxed">{{ description }}</p>
          <div class="grid sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-emerald-100 bg-white p-4">
              <p class="text-xs uppercase tracking-[0.3em] text-emerald-500">Analyses</p>
              <p class="text-2xl font-semibold text-emerald-900">+120/mois</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-4">
              <p class="text-xs uppercase tracking-[0.3em] text-emerald-500">PH</p>
              <p class="text-2xl font-semibold text-emerald-900">3.5 +-0.2</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-4">
              <p class="text-xs uppercase tracking-[0.3em] text-emerald-500">Procedures</p>
              <p class="text-2xl font-semibold text-emerald-900">ISO 22000</p>
            </div>
          </div>
        </div>
        <div class="relative rounded-[32px] overflow-hidden shadow-2xl border border-white/60">
          <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1000&q=80" alt="Laboratoire Sandy" class="object-cover w-full h-[420px]" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Lab Sandy</p>
            <p class="text-2xl font-semibold">Controles multi-etapes</p>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-white py-16">
      <div class="container mx-auto px-4 max-w-6xl">
        <div class="grid md:grid-cols-2 gap-10">
          <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-emerald-900 mb-6 flex items-center">
              <i class="bi bi-patch-check-fill text-amber-500 mr-3 text-2xl"></i>
              Certifications
            </h2>
            <ul class="space-y-4">
              <li
                v-for="certification in certifications"
                :key="certification"
                class="flex items-start text-gray-600"
              >
                <span class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-700 rounded-full mr-4 text-sm font-semibold">
                  {{ certification.slice(0, 1) }}
                </span>
                <span class="pt-1">{{ certification }}</span>
              </li>
            </ul>
          </div>

          <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-emerald-900 mb-6 flex items-center">
              <i class="bi bi-diagram-3-fill text-amber-500 mr-3 text-2xl"></i>
              Notre processus
            </h2>
            <ol class="space-y-4">
              <li
                v-for="(step, index) in processSteps"
                :key="step"
                class="flex items-start text-gray-600"
              >
                <span class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-full mr-4 text-sm font-semibold">
                  {{ index + 1 }}
                </span>
                <span class="pt-1">{{ step }}</span>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <ImageMosaic
      eyebrow="Sur le terrain"
      title="La qualite se voit aussi en images"
      subtitle="Des controles en laboratoire aux livraisons, chaque etape est photographiee."
      :items="qualityGallery"
    />
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ImageMosaic from '@/Components/Common/ImageMosaic.vue'
import SeoHead from '@/Components/Common/SeoHead.vue'

defineProps({
  title: {
    type: String,
    default: 'Notre demarche qualite'
  },
  description: {
    type: String,
    default: ''
  },
  certifications: {
    type: Array,
    default: () => []
  },
  processSteps: {
    type: Array,
    default: () => []
  }
})

const qualityGallery = computed(() => [
  {
    badge: 'Laboratoire',
    title: 'Analyse nutriments',
    caption: 'Dosage vitamine C sur chaque lot',
    src: 'https://images.unsplash.com/photo-1502740479091-635887520276?auto=format&fit=crop&w=900&q=80',
    size: 'large'
  },
  {
    badge: 'Stockage',
    title: 'Chambre froide',
    caption: 'Tracabilite temperature',
    src: '/images/publication/gingembre.jpg',
    tall: true
  },
  {
    badge: 'Production',
    title: 'Documentation lot',
    caption: 'Photo avant depart livraison',
    src: '/images/publication/bissap-bienfaits-infusion-hibiscus.jpg'
  },
  {
    badge: 'Livraison',
    title: 'Mode froid',
    caption: 'Dernier controle avant client',
    src: '/images/publication/pineapple-ginger-juice.webp'
  }
])

const qualityStructuredData = computed(() => ({
  '@context': 'https://schema.org',
  '@type': 'Service',
  name: 'Sandy Juice - controle qualite',
  serviceType: 'Production de jus naturels',
  description: 'Demarche qualite multi-etapes pour les jus pressés a froid.',
  areaServed: 'Cameroun'
}))
</script>
