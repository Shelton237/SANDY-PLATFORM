<template>
  <section class="container mx-auto px-4 py-12">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.4em] text-amber-500 mb-2">{{ eyebrow }}</p>
        <h2 class="text-3xl font-semibold text-[#1b3a2a]">{{ title }}</h2>
        <p class="text-sm text-slate-500 max-w-2xl">{{ subtitle }}</p>
      </div>
      <p v-if="$slots.cta" class="text-sm text-slate-500">
        <slot name="cta" />
      </p>
    </div>

    <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-4">
      <article
        v-for="item in parsedItems"
        :key="item.src"
        :class="[
          'relative overflow-hidden rounded-3xl border border-white/40 shadow-lg',
          item.size === 'large' ? 'md:col-span-2 md:row-span-2' : 'md:col-span-1',
          item.tall ? 'row-span-2' : ''
        ]"
      >
        <img
          :src="item.src"
          :alt="item.title"
          class="h-full w-full object-cover transition duration-500 hover:scale-105"
          loading="lazy"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
          <p class="text-xs uppercase tracking-[0.3em] text-white/70">{{ item.badge }}</p>
          <h3 class="text-lg font-semibold">{{ item.title }}</h3>
          <p class="text-sm text-white/80">{{ item.caption }}</p>
        </div>
      </article>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  eyebrow: {
    type: String,
    default: 'Coulisses'
  },
  title: {
    type: String,
    default: 'Images de nos ateliers'
  },
  subtitle: {
    type: String,
    default: 'Chaque lot est suivi et photographiA pour documenter la qualitA de nos jus.'
  },
  items: {
    type: Array,
    default: () => []
  }
})

const fallbackImages = [
  {
    badge: 'Approvisionnement',
    title: 'Verger partenaire',
    caption: 'SAlection des fruits dans la pAriphArie de YaoundA',
    src: '/images/publication/gingembre.jpg',
    size: 'large'
  },
  {
    badge: 'Production',
    title: 'Pressage du matin',
    caption: 'Lots 100% naturels pressAs A  froid',
    src: '/images/publication/bissap-bienfaits-infusion-hibiscus.jpg',
    tall: true
  },
  {
    badge: 'Conditionnement',
    title: 'ContrA le qualitA',
    caption: 'Chaque bouteille est scellAe et photographiAe',
    src: '/images/publication/pineapple-ginger-juice.webp'
  },
  {
    badge: 'Livraison',
    title: 'DApart coursier',
    caption: 'Livraison express en centre-ville',
    src: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80'
  }
]

const parsedItems = computed(() => {
  if (props.items.length) {
    return props.items
  }
  return fallbackImages
})
</script>
