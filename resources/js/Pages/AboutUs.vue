<template>
  <AppLayout>
    <SeoHead
      :title="title"
      :description="description"
      image="/images/publication/gingembre-drit-of-studio-omggetty-images.avif"
      :structured-data="aboutStructuredData"
    />

    <section class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 text-white">
      <div class="container mx-auto px-4 py-16 lg:py-24">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
          <div class="space-y-6">
            <p class="text-xs uppercase tracking-[0.4em] text-amber-300">Notre histoire</p>
            <h1 class="text-4xl lg:text-5xl font-semibold leading-tight">{{ title }}</h1>
            <p class="text-lg text-white/80">{{ description }}</p>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                <p class="text-xs uppercase tracking-[0.4em] text-amber-200">Sites</p>
                <p class="text-2xl font-semibold">Yaounde & Douala</p>
              </div>
              <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                <p class="text-xs uppercase tracking-[0.4em] text-amber-200">Capacite</p>
                <p class="text-2xl font-semibold">4 500 bouteilles/jour</p>
              </div>
            </div>
          </div>
          <div class="relative rounded-[32px] border border-white/10 p-4 bg-white/5">
            <img src="/images/publication/gingembre-drit-of-studio-omggetty-images.avif" alt="Atelier Sandy" class="rounded-3xl shadow-2xl object-cover w-full h-[420px]" />
            <div class="absolute -bottom-6 right-6 left-6 bg-white text-[#254a29] rounded-2xl shadow-2xl p-4 flex items-center justify-between">
              <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Depuis</p>
                <p class="text-lg font-semibold">2018</p>
              </div>
              <div class="text-right">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Lots suivis</p>
                <p class="text-lg font-semibold">12 400+</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-white py-16">
      <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-14">
          <p class="text-sm uppercase tracking-[0.3em] text-amber-500 mb-3">L'equipe</p>
          <h2 class="text-3xl font-bold text-emerald-900 mb-4">Cellule operationnelle</h2>
          <p class="text-lg text-gray-600 leading-relaxed">
            {{ description }}
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <article
            v-for="member in teamMembers"
            :key="member.name"
            class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-lg transition-shadow"
          >
            <p class="text-xs uppercase tracking-[0.3em] text-[#f49926] mb-1">{{ member.position }}</p>
            <h2 class="text-xl font-semibold text-[#254a29] mb-3">{{ member.name }}</h2>
            <p class="text-sm text-slate-600 leading-relaxed">{{ member.bio }}</p>
          </article>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6 mt-10 space-y-3 text-center">
          <p class="text-xs uppercase tracking-[0.3em] text-[#f49926]">Notre mission</p>
          <h3 class="text-2xl font-semibold text-[#254a29]">Creer le pipeline complet du jus naturel camerounais</h3>
          <p class="text-sm text-slate-500">
            Approvisionnement local, production quotidienne a Yaounde, commercialisation omnicanale et livraison express.
          </p>
        </div>
      </div>
    </section>

    <ImageMosaic
      eyebrow="Album photo"
      title="Fragments de notre quotidien"
      subtitle="Les images de nos equipes sur le terrain et dans les ateliers"
      :items="aboutGallery"
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
    default: 'A propos de Sandy Juice'
  },
  description: {
    type: String,
    default: ''
  },
  teamMembers: {
    type: Array,
    default: () => []
  }
})

const aboutGallery = computed(() => [
  {
    badge: 'Reseau',
    title: 'Producteurs Cameroun',
    caption: 'Verger de Bafia avec nos partenaires historiques',
    src: 'https://images.unsplash.com/photo-1502740479091-635887520276?auto=format&fit=crop&w=900&q=80',
    size: 'large'
  },
  {
    badge: 'Atelier',
    title: 'Pressage en cuivre',
    caption: 'Machines entretenues quotidiennement',
    src: '/images/publication/gingembre-drit-of-studio-omggetty-images.avif',
    tall: true
  },
  {
    badge: 'Equipes',
    title: 'Cellule livraison',
    caption: 'Coordination logistique Yaounde et Douala',
    src: 'https://images.unsplash.com/photo-1488805990569-3c9e1d76d51c?auto=format&fit=crop&w=900&q=80'
  },
  {
    badge: 'Retail',
    title: 'Corners partenaires',
    caption: 'Presence dans les coffee shops et epiceries fines',
    src: '/images/publication/pineapple-ginger-juice.webp'
  }
])

const aboutStructuredData = computed(() => ({
  '@context': 'https://schema.org',
  '@type': 'Organization',
  name: 'Sandy Juice',
  address: 'Yaounde, Cameroun',
  founder: 'Sandra F.',
  description: props.description,
  image: '/images/publication/gingembre-drit-of-studio-omggetty-images.avif'
}))
</script>
