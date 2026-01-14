<template>
  <AppLayout>
    <SeoHead
      :title="title"
      :description="subtitle"
      image="https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=1200&q=80"
      :structured-data="deliveryStructuredData"
    />

    <section class="bg-gradient-to-br from-[#0f1f1a] via-[#162f26] to-[#0f1f1a] text-white">
      <div class="container mx-auto px-4 py-16 lg:py-24 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
          <p class="text-xs uppercase tracking-[0.4em] text-amber-300">Logistique</p>
          <h1 class="text-4xl lg:text-5xl font-semibold leading-tight">
            {{ title }}
          </h1>
          <p class="text-lg text-white/80">
            {{ subtitle }}
          </p>
          <div class="grid sm:grid-cols-3 gap-4">
            <article
              v-for="metric in metrics"
              :key="metric.label"
              class="rounded-2xl border border-white/10 bg-white/10 p-4"
            >
              <p class="text-xs uppercase tracking-[0.4em] text-white/70">{{ metric.label }}</p>
              <p class="text-2xl font-semibold text-white mt-1">{{ metric.value }}</p>
              <p class="text-xs text-white/70">{{ metric.caption }}</p>
            </article>
          </div>
        </div>
        <div class="relative rounded-[32px] overflow-hidden border border-white/10 shadow-2xl">
          <img
            src="https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=1200&q=80"
            alt="Camion frigorifique"
            class="h-full w-full object-cover"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-white/60">Cold chain</p>
            <p class="text-2xl font-semibold">Fleet frigorifique & suivi GPS</p>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-white py-16">
      <div class="container mx-auto px-4 space-y-10">
        <div class="grid lg:grid-cols-2 gap-6">
          <article class="rounded-3xl border border-slate-100 bg-white p-8 shadow-lg">
            <h2 class="text-2xl font-semibold text-[#254a29] mb-4">CrAneaux quotidiens</h2>
            <p class="text-slate-500 mb-6">Chaque lot est pressA, conditionnA puis expAdiA sur des plages fixes pour garantir la fraAcheur.</p>
            <ul class="space-y-4">
              <li
                v-for="slot in slots"
                :key="slot.time"
                class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3"
              >
                <div>
                  <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ slot.badge }}</p>
                  <p class="text-lg font-semibold text-[#254a29]">{{ slot.label }}</p>
                </div>
                <p class="text-sm font-semibold text-[#f49926]">{{ slot.time }}</p>
              </li>
            </ul>
          </article>
          <article class="rounded-3xl border border-slate-100 bg-white p-8 shadow-lg">
            <h2 class="text-2xl font-semibold text-[#254a29] mb-4">Zones desservies</h2>
            <p class="text-slate-500 mb-6">Livraison express urbaines et tournAes hebdomadaires pour la cA te.</p>
            <div class="space-y-4">
              <div
                v-for="zone in zones"
                :key="zone.name"
                class="rounded-2xl border border-slate-100 px-4 py-4"
              >
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-lg font-semibold text-[#254a29]">{{ zone.name }}</p>
                    <p class="text-sm text-slate-500">{{ zone.note }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-xs uppercase text-slate-400">DAlai</p>
                    <p class="font-semibold text-[#254a29]">{{ zone.delay }}</p>
                  </div>
                </div>
                <p class="mt-2 text-sm font-semibold text-[#f49926]">{{ zone.fee }}</p>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <ImageMosaic
      eyebrow="Terrain"
      title="La livraison Sandy en images"
      subtitle="De l'atelier aux points relais, nous documentons chaque Atape."
      :items="gallery"
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
    default: 'Livraison & logistique Sandy'
  },
  subtitle: {
    type: String,
    default: ''
  },
  metrics: {
    type: Array,
    default: () => []
  },
  slots: {
    type: Array,
    default: () => []
  },
  zones: {
    type: Array,
    default: () => []
  },
  gallery: {
    type: Array,
    default: () => []
  }
})

const deliveryStructuredData = computed(() => ({
  '@context': 'https://schema.org',
  '@type': 'Service',
  name: 'Sandy Juice - livraison',
  areaServed: props.zones.map((zone) => zone.name),
  hasDeliveryMethod: 'Cold chain',
  description: props.subtitle
}))
</script>
