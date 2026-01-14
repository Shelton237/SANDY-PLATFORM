<template>
  <AdminLayout title="Stockage">
    <Head title="Stockage" />

    <div class="grid gap-4 md:grid-cols-4 mb-6">
      <article v-for="card in statCards" :key="card.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ card.value }}</p>
        <p class="text-sm text-slate-500">{{ card.caption }}</p>
      </article>
    </div>

    <section class="grid gap-4 lg:grid-cols-2 mb-6">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Ajouter un lot</h2>
        <form class="grid gap-4" @submit.prevent="submitBatch">
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="batchForm.ingredient" type="text" placeholder="Ingrédient" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <input v-model.number="batchForm.quantity_available" type="number" min="1" placeholder="Quantité" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
          </div>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="batchForm.unit" type="text" placeholder="Unité" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <input v-model="batchForm.location" type="text" placeholder="Emplacement" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <input v-model="batchForm.expires_at" type="date" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#254a29] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1d3c22]" :disabled="batchForm.processing">
            Ajouter en stock
          </button>
        </form>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Réserver pour production</h2>
        <form class="grid gap-4" @submit.prevent="submitReservation">
          <select v-model="reserveForm.batch_id" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <option value="">Choisir un lot</option>
            <option v-for="batch in batches" :key="batch.id" :value="batch.id">
              {{ batch.batch_code }} • {{ batch.ingredient }}
            </option>
          </select>
          <select v-model="reserveForm.product_id" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <option value="">Produit cible</option>
            <option v-for="product in products" :key="product.id" :value="product.id">
              {{ product.name }}
            </option>
          </select>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model.number="reserveForm.planned_liters" type="number" min="1" placeholder="Litres planifiés" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <input v-model="reserveForm.shift" type="text" placeholder="Shift (matin…)" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <input v-model="reserveForm.team_lead" type="text" placeholder="Chef d’équipe" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#f28700]" :disabled="reserveForm.processing">
            Réserver & planifier
          </button>
        </form>
      </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-3 mb-6">
      <article v-for="status in statusCards" :key="status.label" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ status.label }}</p>
        <p class="text-3xl font-semibold text-[#254a29]">{{ status.value }}</p>
        <p class="text-sm text-slate-500">{{ status.caption }}</p>
      </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-[#254a29] mb-4">Lots disponibles</h2>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-left text-slate-400 uppercase tracking-widest text-xs border-b border-slate-100">
                <th class="py-2">Lot</th>
                <th class="py-2">Ingrédient</th>
                <th class="py-2">Quantité</th>
                <th class="py-2">Lieu</th>
                <th class="py-2">Statut</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="batch in batches" :key="batch.id" class="border-b border-slate-50 last:border-none">
                <td class="py-3 font-semibold text-[#254a29]">{{ batch.batch_code }}</td>
                <td class="py-3">
                  <p class="font-medium text-slate-700">{{ batch.ingredient }}</p>
                  <p class="text-xs text-slate-500">{{ batch.supplier?.name }}</p>
                </td>
                <td class="py-3 text-slate-600">{{ formatQuantity(batch.quantity_available, batch.unit) }}</td>
                <td class="py-3 text-slate-500">{{ batch.location || '—' }}</td>
                <td class="py-3">
                  <select :value="batch.status" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" @change="updateBatch(batch.id, $event.target.value)">
                    <option value="available">Disponible</option>
                    <option value="reserved">Réservé</option>
                    <option value="low">Low</option>
                    <option value="quality_hold">Qualité</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-[#254a29] mb-4">Répartition par zone</h2>
        <ul class="space-y-3 text-sm">
          <li v-for="location in locations" :key="location.location" class="flex items-center justify-between">
            <span class="text-slate-600">{{ location.location || 'Non défini' }}</span>
            <span class="font-semibold text-[#254a29]">{{ formatQuantity(location.total, 'kg') }}</span>
          </li>
        </ul>
      </article>
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
  statusBreakdown: {
    type: Object,
    default: () => ({})
  },
  locations: {
    type: Array,
    default: () => []
  },
  products: {
    type: Array,
    default: () => []
  }
})

const batchForm = useForm({
  ingredient: '',
  quantity_available: 10,
  unit: 'kg',
  location: 'Chambre froide A',
  expires_at: ''
})

const reserveForm = useForm({
  batch_id: '',
  product_id: '',
  planned_liters: 120,
  team_lead: '',
  shift: 'matin'
})

const submitBatch = () => {
  batchForm.post(route('admin.inventory-batches.store'), {
    preserveScroll: true,
    onSuccess: () => batchForm.reset('ingredient', 'quantity_available')
  })
}

const submitReservation = () => {
  if (!reserveForm.batch_id) return
  reserveForm.post(route('admin.inventory-batches.reserve', reserveForm.batch_id), {
    preserveScroll: true,
    onSuccess: () => reserveForm.reset()
  })
}

const updateBatch = (batchId, status) => {
  router.patch(route('admin.inventory-batches.update', batchId), { status }, { preserveScroll: true })
}

const statCards = computed(() => [
  { label: 'Volume total', value: `${(props.stats.totalKg ?? 0).toFixed(1)} kg`, caption: 'Disponible en atelier' },
  { label: 'Lots réservés', value: props.stats.reserved ?? 0, caption: 'Pour prochains batchs' },
  { label: 'Stocks bas', value: props.stats.lowStock ?? 0, caption: 'Plan d’action requis' },
  { label: 'Péremption <5j', value: props.stats.expiringSoon ?? 0, caption: 'À consommer rapidement' }
])

const statusCards = computed(() => [
  {
    label: 'Disponible',
    value: props.statusBreakdown?.available ?? 0,
    caption: 'Lots prêts à produire'
  },
  {
    label: 'Réservé',
    value: props.statusBreakdown?.reserved ?? 0,
    caption: 'Assigné à des batchs'
  },
  {
    label: 'Qualité / Low',
    value: (props.statusBreakdown?.quality_hold ?? 0) + (props.statusBreakdown?.low ?? 0),
    caption: 'Sous surveillance'
  }
])

const formatQuantity = (quantity, unit) => {
  const val = Number(quantity) || 0
  return `${val.toFixed(1)} ${unit}`
}
</script>

