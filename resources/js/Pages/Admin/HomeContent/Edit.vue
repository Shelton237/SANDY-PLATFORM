<template>
  <AdminLayout title="Accueil">
    <Head title="Accueil - Hero" />

    <section class="grid gap-6 lg:grid-cols-[2fr,1fr]">
      <article class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6 space-y-6">
        <header>
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Section Hero</p>
          <h1 class="text-2xl font-semibold text-[#254a29]">Éditer la vitrine</h1>
          <p class="text-sm text-slate-500">Personnalisez le contenu visible sur la page d’accueil (hero, CTA, statistiques).</p>
        </header>

        <form class="space-y-6" @submit.prevent="submit">
          <div class="grid md:grid-cols-2 gap-4">
            <label class="block text-sm font-medium text-[#254a29]">
              Badge supérieur
              <input v-model="form.eyebrow" type="text" :class="inputClasses" placeholder="Pipeline Sandy" />
            </label>
            <label class="block text-sm font-medium text-[#254a29]">
              Badge highlight
              <input v-model="form.highlight_badge" type="text" :class="inputClasses" placeholder="Circuit 360°" />
            </label>
          </div>

          <label class="block text-sm font-medium text-[#254a29]">
            Titre principal
            <input v-model="form.headline" type="text" :class="inputClasses" required />
          </label>

          <label class="block text-sm font-medium text-[#254a29]">
            Sous-titre
            <textarea v-model="form.subheadline" rows="3" :class="textareaClasses"></textarea>
          </label>

          <label class="block text-sm font-medium text-[#254a29]">
            Texte highlight
            <textarea v-model="form.highlight_text" rows="2" :class="textareaClasses" placeholder="Approvisionnement → Stockage → Production → ..."></textarea>
          </label>

          <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-3">
              <p class="text-sm font-semibold text-[#254a29]">CTA principal</p>
              <label class="block text-sm text-[#254a29]">
                Label
                <input v-model="form.primary_cta.label" type="text" :class="inputClasses" />
              </label>
              <label class="block text-sm text-[#254a29]">
                Route Inertia
                <input v-model="form.primary_cta.route" type="text" :class="inputClasses" placeholder="products" />
              </label>
              <label class="block text-sm text-[#254a29]">
                URL absolue
                <input v-model="form.primary_cta.url" type="url" :class="inputClasses" placeholder="https://..." />
              </label>
            </div>
            <div class="space-y-3">
              <p class="text-sm font-semibold text-[#254a29]">CTA secondaire</p>
              <label class="block text-sm text-[#254a29]">
                Label
                <input v-model="form.secondary_cta.label" type="text" :class="inputClasses" />
              </label>
              <label class="block text-sm text-[#254a29]">
                Route Inertia
                <input v-model="form.secondary_cta.route" type="text" :class="inputClasses" placeholder="about" />
              </label>
              <label class="block text-sm text-[#254a29]">
                URL absolue
                <input v-model="form.secondary_cta.url" type="url" :class="inputClasses" placeholder="https://..." />
              </label>
            </div>
          </div>

          <label class="block text-sm font-medium text-[#254a29]">
            Image principale
            <input v-model="form.media.image" type="text" :class="inputClasses" placeholder="/images/hero/bottles.jpg" />
          </label>

          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold text-[#254a29]">Statistiques</p>
                <p class="text-xs text-slate-500">4 cartes maximum. Chaque statistique apparaît sous le hero.</p>
              </div>
              <button type="button" :class="secondaryButtonClasses" @click="addStat" :disabled="form.stats.length >= 4">
                + Ajouter
              </button>
            </div>
            <div class="grid gap-4">
              <div v-for="(stat, index) in form.stats" :key="index" class="rounded-2xl border border-slate-200 p-4 grid md:grid-cols-3 gap-3">
                <label class="text-sm font-medium text-[#254a29]">
                  Label
                  <input v-model="stat.label" type="text" :class="inputClasses" />
                </label>
                <label class="text-sm font-medium text-[#254a29]">
                  Valeur
                  <input v-model="stat.value" type="text" :class="inputClasses" />
                </label>
                <div>
                  <div class="flex items-center justify-between">
                    <label class="text-xs uppercase text-slate-400">Icône (Bootstrap)</label>
                    <button type="button" class="text-xs text-rose-500" @click="removeStat(index)">Supprimer</button>
                  </div>
                  <input v-model="stat.icon" type="text" :class="inputClasses" placeholder="bi bi-droplet" />
                </div>
              </div>
              <p v-if="form.stats.length === 0" class="text-xs text-slate-500">Aucune statistique. Cliquez sur “Ajouter”.</p>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit" class="rounded-2xl bg-[#254a29] px-6 py-3 text-white font-semibold hover:bg-[#1f3b30]" :disabled="form.processing">
              Sauvegarder
            </button>
          </div>
        </form>
      </article>

      <article class="rounded-3xl border border-dashed border-slate-300 p-6 bg-slate-50 space-y-4">
        <h2 class="text-sm font-semibold text-[#254a29] uppercase tracking-wide">Prévisualisation</h2>
        <p class="text-sm text-slate-500">Aperçu textuel du hero après mise à jour.</p>
        <div class="rounded-2xl bg-white shadow p-4 space-y-2">
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ form.eyebrow || 'Pipeline' }}</p>
          <p class="text-xl font-semibold text-[#254a29]">{{ form.headline || 'Titre principal' }}</p>
          <p class="text-sm text-slate-500">{{ form.subheadline || 'Description...' }}</p>
          <ul class="text-xs text-slate-500 list-disc pl-4">
            <li v-for="stat in form.stats" :key="stat.label">{{ stat.value }} – {{ stat.label }}</li>
          </ul>
        </div>
      </article>
    </section>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  hero: {
    type: Object,
    default: () => ({}),
  },
})

const initialStats = Array.isArray(props.hero?.stats) && props.hero.stats.length ? props.hero.stats : []

const inputClasses =
  'mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-[#254a29] bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30'
const textareaClasses = `${inputClasses} resize-none`
const secondaryButtonClasses =
  'rounded-2xl border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-600 hover:text-[#254a29]'

const form = useForm({
  eyebrow: props.hero?.eyebrow ?? '',
  headline: props.hero?.headline ?? '',
  subheadline: props.hero?.subheadline ?? '',
  highlight_badge: props.hero?.highlight_badge ?? '',
  highlight_text: props.hero?.highlight_text ?? '',
  primary_cta: {
    label: props.hero?.primary_cta?.label ?? '',
    route: props.hero?.primary_cta?.route ?? '',
    url: props.hero?.primary_cta?.url ?? '',
  },
  secondary_cta: {
    label: props.hero?.secondary_cta?.label ?? '',
    route: props.hero?.secondary_cta?.route ?? '',
    url: props.hero?.secondary_cta?.url ?? '',
  },
  media: {
    image: props.hero?.media?.image ?? '/images/hero/bottles.jpg',
  },
  stats: initialStats.length
    ? initialStats.map((stat) => ({
        label: stat.label ?? '',
        value: stat.value ?? '',
        icon: stat.icon ?? 'bi bi-droplet',
      }))
    : [
        { label: 'Lots/jour', value: '3 200', icon: 'bi bi-droplet' },
        { label: 'Réseau partenaires', value: '48+', icon: 'bi bi-diagram-3' },
      ],
})

const addStat = () => {
  if (form.stats.length >= 4) return
  form.stats.push({ label: '', value: '', icon: 'bi bi-droplet' })
}

const removeStat = (index) => {
  form.stats.splice(index, 1)
}

const submit = () => {
  form.put(route('admin.home-content.update'))
}
</script>
