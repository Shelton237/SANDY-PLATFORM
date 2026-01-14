<template>
  <AdminLayout title="Production">
    <Head title="Production" />

    <div class="grid gap-4 md:grid-cols-4 mb-6">
      <article v-for="card in statCards" :key="card.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ card.value }}</p>
        <p class="text-sm text-slate-500">{{ card.caption }}</p>
      </article>
    </div>

    <section class="grid gap-4 lg:grid-cols-2 mb-6">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Nouveau batch</h2>
        <form class="grid gap-4" @submit.prevent="submitProduction">
          <select v-model="productionForm.product_id" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <option value="">Sélectionner un produit</option>
            <option v-for="product in products" :key="product.id" :value="product.id">
              {{ product.name }}
            </option>
          </select>
          <input v-model.number="productionForm.planned_liters" type="number" min="1" placeholder="Litres planifiés" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="productionForm.shift" type="text" placeholder="Shift (matin...)" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
            <input v-model="productionForm.team_lead" type="text" placeholder="Chef d’équipe" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <textarea v-model="productionForm.notes" rows="2" placeholder="Notes" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
          <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#254a29] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1d3c22]" :disabled="productionForm.processing">
            Planifier
          </button>
        </form>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Pipeline</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div v-for="item in pipeline" :key="item.label" class="p-4 rounded-2xl border border-slate-100 bg-slate-50">
            <p class="text-xs uppercase text-slate-400">{{ item.label }}</p>
            <p class="text-xl font-semibold text-[#254a29]">{{ item.value }}</p>
            <p class="text-xs text-slate-500">{{ item.caption }}</p>
          </div>
        </div>
      </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Lots récents</p>
          <h2 class="text-xl font-semibold text-[#254a29]">Suivi ateliers</h2>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-slate-400 uppercase tracking-widest text-xs border-b border-slate-100">
              <th class="py-2">Batch</th>
              <th class="py-2">Produit</th>
              <th class="py-2">Statut</th>
              <th class="py-2">Volume</th>
              <th class="py-2">Début</th>
              <th class="py-2 text-right">Responsable</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="batch in batches" :key="batch.id" class="border-b border-slate-50 last:border-none">
              <td class="py-3 font-semibold text-[#254a29]">{{ batch.code }}</td>
              <td class="py-3">
                <p class="font-medium text-slate-700">{{ batch.product?.name }}</p>
                <p class="text-xs text-slate-500">{{ batch.product?.category }}</p>
              </td>
              <td class="py-3">
                <select :value="batch.status" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" @change="updateBatch(batch.id, $event.target.value)">
                  <option value="planned">Planifié</option>
                  <option value="in_progress">En cours</option>
                  <option value="quality_check">Qualité</option>
                  <option value="packaged">Packagé</option>
                </select>
              </td>
              <td class="py-3 text-slate-600">{{ formatLiters(batch.planned_liters) }}</td>
              <td class="py-3 text-slate-500">{{ formatDate(batch.starts_at) }}</td>
              <td class="py-3 text-right text-slate-500">{{ batch.team_lead || '—' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  batches: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({})
  },
  products: {
    type: Array,
    default: () => []
  }
})

const productionForm = useForm({
  product_id: '',
  planned_liters: 150,
  team_lead: '',
  shift: 'matin',
  notes: ''
})

const submitProduction = () => {
  productionForm.post(route('admin.production-batches.store'), {
    preserveScroll: true,
    onSuccess: () => productionForm.reset('product_id', 'planned_liters', 'team_lead', 'notes')
  })
}

const updateBatch = (batchId, status) => {
  router.patch(route('admin.production-batches.update', batchId), { status }, { preserveScroll: true })
}

const statCards = computed(() => [
  { label: 'Batchs planifiés', value: props.stats.planned ?? 0, caption: 'Dans le planning' },
  { label: 'En cours', value: props.stats.inProgress ?? 0, caption: 'Brigade atelier' },
  { label: 'Contrôle qualité', value: props.stats.quality ?? 0, caption: 'Laboratoire' },
  { label: 'Conditionné', value: props.stats.packaged ?? 0, caption: 'Prêt à livrer' }
])

const pipeline = computed(() => [
  { label: 'Planifié', value: props.stats.planned ?? 0, caption: 'slots disponibles' },
  { label: 'En cours', value: props.stats.inProgress ?? 0, caption: 'mixage / embouteillage' },
  { label: 'QC', value: props.stats.quality ?? 0, caption: 'analyses en cours' },
  { label: 'Packagé', value: props.stats.packaged ?? 0, caption: 'en chambre froide' }
])

const formatLiters = (value) => {
  const liters = Number(value) || 0
  return `${liters.toFixed(0)} L`
}

const formatDate = (value) => {
  if (!value) return '—'
  return new Date(value).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' })
}
</script>

