<template>
  <AppLayout>
    <SeoHead
      :title="title"
      :description="description"
      image="https://images.unsplash.com/photo-1502740479091-635887520276?auto=format&fit=crop&w=1000&q=80"
      :structured-data="valuesStructuredData"
    />

    <section class="bg-white">
      <div class="container mx-auto px-4 py-16 lg:py-20 grid lg:grid-cols-2 gap-10 items-center">
        <div class="space-y-5">
          <p class="text-sm uppercase tracking-[0.3em] text-amber-500">Nos valeurs</p>
          <h1 class="text-4xl font-bold text-emerald-900">{{ title }}</h1>
          <p class="text-lg text-gray-600 leading-relaxed">{{ description }}</p>
          <div class="flex flex-wrap gap-4">
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
              Tracabilite totale
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3 text-sm text-amber-700">
              Filiere locale
            </div>
          </div>
        </div>
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1502740479091-635887520276?auto=format&fit=crop&w=1000&q=80" alt="Valeurs Sandy" class="rounded-[32px] object-cover w-full h-[420px] shadow-2xl" />
          <div class="absolute -bottom-6 left-10 right-10 bg-white rounded-2xl shadow-xl p-4 flex items-center justify-between text-sm text-slate-600">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Communautes</p>
              <p class="text-lg font-semibold text-[#254a29]">120 cooperatives</p>
            </div>
            <div class="text-right">
              <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Engagement</p>
              <p class="text-lg font-semibold text-[#254a29]">10% reverses</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-gradient-to-b from-emerald-50 to-white py-16">
      <div class="container mx-auto px-4 max-w-6xl">
        <div class="grid md:grid-cols-2 gap-8">
          <article
            v-for="valueItem in values"
            :key="valueItem.title"
            class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 rounded-3xl p-8 shadow-sm hover:shadow-xl transition"
          >
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 text-2xl mr-4">
                <i :class="valueItem.icon"></i>
              </div>
              <h2 class="text-2xl font-semibold text-emerald-900">{{ valueItem.title }}</h2>
            </div>
            <p class="text-gray-600 leading-relaxed">
              {{ valueItem.description }}
            </p>
          </article>
        </div>
      </div>
    </section>

    <ImageMosaic
      eyebrow="Inspiration"
      title="Ce que nous protegeons au quotidien"
      subtitle="Des images pour rappeler que chaque valeur se traduit en actions concretes."
      :items="valueGallery"
    />
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ImageMosaic from '@/Components/Common/ImageMosaic.vue'
import SeoHead from '@/Components/Common/SeoHead.vue'

const props = defineProps({
  title: {
    type: String,
    default: 'Nos valeurs'
  },
  description: {
    type: String,
    default: ''
  },
  values: {
    type: Array,
    default: () => []
  }
})

const valueGallery = computed(() => [
  {
    badge: 'Respect',
    title: 'Filiere paysanne',
    caption: 'Selections solidaires dans les vergers',
    src: '/images/publication/gingembre.jpg',
    size: 'large'
  },
  {
    badge: 'Innovation',
    title: 'Laboratoire sensoriel',
    caption: 'Tests de recettes avant mise en bouteille',
    src: 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=900&q=80',
    tall: true
  },
  {
    badge: 'Equite',
    title: 'Team atelier',
    caption: 'Plan de carriere et primes mensuelles',
    src: '/images/publication/pineapple-ginger-juice.webp'
  },
  {
    badge: 'Engagement',
    title: 'Moments clients',
    caption: 'Shooting lifestyle pour nos partenaires',
    src: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80'
  }
])

const valuesStructuredData = computed(() => ({
  '@context': 'https://schema.org',
  '@type': 'Brand',
  name: 'Sandy Juice',
  slogan: props.description,
  values: props.values?.map((value) => value.title) || []
}))
</script>
