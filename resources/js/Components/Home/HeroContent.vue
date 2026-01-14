<template>
  <section class="relative overflow-hidden bg-[#0f1f1a] text-white">
    <div class="absolute inset-0">
      <div
        class="absolute inset-0 opacity-20 bg-cover bg-center"
        :style="backgroundStyle"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-br from-[#0f1f1a] via-[#112920] to-[#0f1f1a]"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-12 lg:py-20">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-sm font-semibold uppercase tracking-[0.3em]">
            <i class="bi bi-intersect"></i>
            {{ heroContent.eyebrow }}
          </span>

          <div class="space-y-4">
            <h1 class="text-4xl lg:text-5xl font-semibold leading-tight text-white">
              {{ heroContent.headline }}
            </h1>
            <p class="text-lg text-white/70">
              {{ heroContent.subheadline }}
            </p>
          </div>

          <div class="rounded-2xl bg-white/5 border border-white/10 p-4 text-sm text-white/80">
            <p class="font-semibold uppercase tracking-[0.3em] text-xs text-[#f9b872]">
              {{ heroContent.highlight_badge }}
            </p>
            <p class="mt-1 text-base text-white/90">
              {{ heroContent.highlight_text }}
            </p>
          </div>

          <div class="flex flex-wrap gap-4">
            <Link
              v-if="heroContent.primaryCta.label"
              :href="heroContent.primaryCta.url"
              class="inline-flex items-center gap-2 rounded-2xl bg-[#f49926] px-6 py-3 text-sm font-semibold text-[#1f1307] transition hover:bg-[#f28700]"
            >
              {{ heroContent.primaryCta.label }}
              <i class="bi bi-arrow-up-right"></i>
            </Link>
            <Link
              v-if="heroContent.secondaryCta.label"
              :href="heroContent.secondaryCta.url"
              class="inline-flex items-center gap-2 rounded-2xl border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/40"
            >
              {{ heroContent.secondaryCta.label }}
            </Link>
          </div>

          <div class="grid sm:grid-cols-3 gap-4">
            <div
              v-for="stat in heroContent.stats"
              :key="stat.label"
              class="rounded-2xl border border-white/10 bg-white/5 p-4"
            >
              <div class="flex items-center gap-2 text-xs uppercase tracking-[0.3em] text-white/60">
                <i :class="stat.icon || 'bi bi-droplet'"></i>
                {{ stat.label }}
              </div>
              <p class="mt-2 text-2xl font-bold text-white">{{ stat.value }}</p>
            </div>
          </div>
        </div>

        <div class="relative">
          <div class="relative rounded-[32px] border border-white/10 bg-white/5 p-6 overflow-hidden backdrop-blur-sm">
            <div class="aspect-[4/3] rounded-3xl overflow-hidden border border-white/5 shadow-2xl">
              <img
                :src="heroContent.media.image"
                alt="Pipeline Sandy"
                class="h-full w-full object-cover"
              />
            </div>
            <div class="absolute -bottom-6 inset-x-10 rounded-2xl bg-white text-[#254a29] shadow-xl p-4 flex items-center justify-between">
              <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Pipeline</p>
                <p class="text-base font-semibold">Cinq étapes synchronisées</p>
              </div>
              <div class="text-right">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Temps moyen</p>
                <p class="text-base font-semibold">-35% délai</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import { route } from 'ziggy-js'
import { Ziggy } from '@/ziggy'

const props = defineProps({
  hero: {
    type: Object,
    default: () => ({}),
  },
})

const heroContent = computed(() => {
  const content = props.hero ?? {}
  const primaryCta = content.primary_cta ?? {}
  const secondaryCta = content.secondary_cta ?? {}

  const resolveUrl = (cta) => {
    if (!cta) return '#'
    if (cta.url) return cta.url
    if (cta.route) {
      try {
        return route(cta.route, undefined, undefined, Ziggy)
      } catch (e) {
        return '#'
      }
    }
    return '#'
  }

  return {
    eyebrow: content.eyebrow || 'Circuit Sandy',
    headline: content.headline || 'Pilotez vos lots de jus naturels',
    subheadline: content.subheadline || 'Approvisionnement, stockage, production, commercialisation et livraison : un seul pipeline pour votre usine.',
    highlight_badge: content.highlight_badge || 'Pipeline 360°',
    highlight_text: content.highlight_text || 'Approvisionnement → Stockage → Production → Commercialisation → Livraison',
    media: {
      image: content.media?.image || '/images/hero/bottles.jpg',
    },
    stats: Array.isArray(content.stats) && content.stats.length
      ? content.stats
      : [
          { label: 'Lots/jour', value: '3 200', icon: 'bi bi-droplet' },
          { label: 'Réseau partenaires', value: '48+', icon: 'bi bi-diagram-3' },
          { label: 'Livraison urbaine', value: '-2h', icon: 'bi bi-lightning-charge' },
        ],
    primaryCta: {
      label: primaryCta.label || 'Voir le catalogue',
      url: resolveUrl(primaryCta),
    },
    secondaryCta: {
      label: secondaryCta.label || 'Découvrir le circuit',
      url: resolveUrl(secondaryCta),
    },
  }
})

const backgroundStyle = computed(() => {
  const image = heroContent.value.media.image
  return image
    ? {
        backgroundImage: `linear-gradient(135deg, rgba(15,31,26,0.9), rgba(15,31,26,0.6)), url(${image})`,
      }
    : {}
})
</script>
