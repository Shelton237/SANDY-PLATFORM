<template>
  <AdminLayout title="Nouvelle recette">
    <Head title="Créer un produit" />

    <section class="mb-8 rounded-3xl border border-slate-200 bg-gradient-to-r from-[#fef7ee] via-white to-[#f0faf2] p-6 shadow-sm">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="space-y-3">
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Pipeline innovation</p>
          <h1 class="text-3xl font-semibold text-[#254a29]">Composer une nouvelle recette Sandy</h1>
          <p class="text-sm text-slate-500 max-w-2xl">
            Définissez les informations de base, affinez l’histoire produit, puis chargez les visuels en haute résolution.
            Un récapitulatif s’actualise à droite pour vous guider.
          </p>
        </div>
        <div class="flex flex-wrap gap-2">
          <span v-for="(step, index) in steps" :key="step" class="px-4 py-2 rounded-full text-xs font-semibold"
            :class="index === 0 ? 'bg-[#f49926] text-white' : 'bg-white border border-slate-200 text-slate-500'">
            {{ index + 1 }}. {{ step }}
          </span>
        </div>
      </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-3">
      <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
        <ProductForm :form="form" :statuses="statuses" :categories="categories" submit-label="Créer" :on-submit="submit" />
      </div>

      <aside class="space-y-4">
        <article class="rounded-3xl border border-slate-200 bg-white shadow-sm p-5 space-y-3">
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Checklist</p>
          <h2 class="text-lg font-semibold text-[#254a29]">Avant publication</h2>
          <ul class="space-y-2 text-sm text-slate-600">
            <li v-for="item in checklist" :key="item" class="flex items-start gap-2">
              <i class="bi bi-check-circle-fill text-[#f49926] mt-0.5"></i>
              <span>{{ item }}</span>
            </li>
          </ul>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white shadow-sm p-5">
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400 mb-3">Récapitulatif live</p>
          <dl class="space-y-2 text-sm text-slate-600">
            <div class="flex justify-between">
              <dt>Statut</dt>
              <dd class="font-semibold text-[#254a29]">{{ statuses[form.status] || 'Brouillon' }}</dd>
            </div>
            <div class="flex justify-between">
              <dt>Catégorie</dt>
              <dd class="font-semibold text-[#254a29]">{{ displayCategory }}</dd>
            </div>
            <div class="flex justify-between">
              <dt>Prix indicatif</dt>
              <dd class="font-semibold text-[#f49926]">{{ formatPrice(form.price) }}</dd>
            </div>
            <div class="flex justify-between">
              <dt>Images</dt>
              <dd class="font-semibold text-[#254a29]">{{ (form.images ?? []).length }}</dd>
            </div>
          </dl>
        </article>
      </aside>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ProductForm from './Partials/ProductForm.vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  statuses: {
    type: Object,
    default: () => ({})
  },
  categories: {
    type: Array,
    default: () => []
  }
})

const form = useForm({ ...props.product })

const steps = ['Base', 'Commerce', 'Storytelling', 'Galerie']
const checklist = [
  'Renseigner description + tagline',
  'Ajouter au moins une image HD',
  'Vérifier disponibilité / stock',
  'Valider la catégorie marketing'
]

const displayCategory = computed(() => {
  const found = props.categories.find((cat) => cat.slug === form.category)
  return found ? found.name : 'Non catégorisé'
})

const formatPrice = (value) => {
  const number = Number(value) || 0
  return `${number.toFixed(0)} FCFA`
}

const submit = () => {
  form.post(route('admin.products.store'))
}
</script>
