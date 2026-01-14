<template>
  <AdminLayout title="Approvisionnement">
    <Head title="Approvisionnement" />

    <div class="grid gap-4 md:grid-cols-4 mb-6">
      <article v-for="card in statCards" :key="card.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ card.value }}</p>
        <p class="text-sm text-slate-500">{{ card.caption }}</p>
      </article>
    </div>

    <section class="grid gap-4 lg:grid-cols-2 mb-6">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Nouveau fournisseur</h2>
        <form class="grid gap-4" @submit.prevent="submitSupplier">
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="supplierForm.name" type="text" placeholder="Nom" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <input v-model="supplierForm.contact_name" type="text" placeholder="Contact" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="supplierForm.email" type="email" placeholder="Email" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
            <input v-model="supplierForm.phone" type="text" placeholder="Téléphone" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="supplierForm.city" type="text" placeholder="Ville" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
            <input v-model.number="supplierForm.lead_time_days" type="number" min="1" placeholder="Lead time (jours)" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <textarea v-model="supplierForm.notes" rows="2" placeholder="Notes" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
          <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#254a29] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1d3c22]" :disabled="supplierForm.processing">
            Ajouter
          </button>
        </form>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Commande d’ingrédients</h2>
        <form class="grid gap-4" @submit.prevent="submitSupplyOrder">
          <select v-model="supplyOrderForm.supplier_id" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <option value="">Sélectionner un fournisseur</option>
            <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
              {{ supplier.name }}
            </option>
          </select>
          <input v-model="supplyOrderForm.ingredient" type="text" placeholder="Ingrédient" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model.number="supplyOrderForm.quantity" type="number" min="1" step="0.1" placeholder="Quantité" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <input v-model="supplyOrderForm.unit" type="text" placeholder="Unité (kg, L…)" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
          </div>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="supplyOrderForm.expected_at" type="date" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
            <input v-model="supplyOrderForm.transport_mode" type="text" placeholder="Mode transport" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#f28700]" :disabled="supplyOrderForm.processing">
            Créer la commande
          </button>
        </form>
      </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-4 mb-8">
      <article v-for="column in pipelineColumns" :key="column.key" class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ column.label }}</p>
          <span class="text-xs px-3 py-1 rounded-full" :class="column.badgeClass">{{ column.value }}</span>
        </div>
        <p class="text-sm text-slate-500">{{ column.caption }}</p>
      </article>
    </section>

    <div class="grid gap-6 lg:grid-cols-2">
      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Partenaires</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Fournisseurs prioritaires</h2>
          </div>
        </div>
        <ul class="divide-y divide-slate-100">
          <li v-for="supplier in suppliers" :key="supplier.id" class="py-3 flex items-center justify-between">
            <div>
              <p class="font-semibold text-[#254a29]">{{ supplier.name }}</p>
              <p class="text-xs text-slate-500">{{ supplier.city }}, {{ supplier.country }}</p>
            </div>
            <div class="text-right">
              <p class="text-sm text-slate-500">Fiabilité {{ supplier.reliability_score }}%</p>
              <p class="text-xs text-slate-400">Lead {{ supplier.lead_time_days }} j • {{ supplier.open_orders_count }} lots</p>
            </div>
          </li>
        </ul>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Flux</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Commandes d’ingrédients</h2>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-left text-slate-400 uppercase tracking-widest text-xs border-b border-slate-100">
                <th class="py-2">Référence</th>
                <th class="py-2">Ingrédient</th>
                <th class="py-2">Quantité</th>
                <th class="py-2">Statut</th>
                <th class="py-2 text-right">ETA</th>
                <th class="py-2 text-right">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in orders" :key="order.id" class="border-b border-slate-50 last:border-none">
                <td class="py-3 font-semibold text-[#254a29]">{{ order.reference }}</td>
                <td class="py-3">
                  <p class="font-medium text-slate-700">{{ order.ingredient }}</p>
                  <p class="text-xs text-slate-500">{{ order.supplier?.name }}</p>
                </td>
                <td class="py-3 text-slate-600">{{ formatQuantity(order.quantity, order.unit) }}</td>
                <td class="py-3">
                  <span class="px-3 py-1 text-xs rounded-full" :class="statusBadge(order.status)">
                    {{ order.status }}
                  </span>
                </td>
                <td class="py-3 text-right text-slate-500">{{ formatDate(order.expected_at) }}</td>
                <td class="py-3 text-right">
                  <button
                    v-if="order.status !== 'received'"
                    class="text-sm font-semibold text-[#f49926]"
                    @click="receiveOrder(order.id)"
                  >
                    Réceptionner
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  suppliers: {
    type: Array,
    default: () => []
  },
  orders: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({})
  },
  pipeline: {
    type: Object,
    default: () => ({})
  }
})

const supplierForm = useForm({
  name: '',
  contact_name: '',
  email: '',
  phone: '',
  city: '',
  lead_time_days: 2,
  notes: ''
})

const supplyOrderForm = useForm({
  supplier_id: '',
  ingredient: '',
  quantity: 50,
  unit: 'kg',
  expected_at: '',
  transport_mode: 'courier'
})

const submitSupplier = () => {
  supplierForm.post(route('admin.suppliers.store'), {
    preserveScroll: true,
    onSuccess: () => supplierForm.reset()
  })
}

const submitSupplyOrder = () => {
  supplyOrderForm.post(route('admin.supply-orders.store'), {
    preserveScroll: true,
    onSuccess: () => supplyOrderForm.reset()
  })
}

const receiveOrder = (orderId) => {
  router.post(route('admin.supply-orders.receive', orderId), {}, { preserveScroll: true })
}

const statCards = computed(() => [
  { label: 'Fournisseurs actifs', value: props.stats.activeSuppliers ?? 0, caption: 'Réseau validé' },
  { label: 'Lots en transit', value: props.stats.inTransit ?? 0, caption: 'En route vers l’atelier' },
  { label: 'Alertes qualité', value: props.stats.qualityAlerts ?? 0, caption: 'Contrôles renforcés' },
  { label: 'Arrivages du jour', value: props.stats.arrivalsToday ?? 0, caption: 'ETA < 24h' }
])

const pipelineColumns = computed(() => [
  {
    key: 'ordered',
    label: 'Commandé',
    value: props.pipeline?.ordered ?? 0,
    caption: 'Lots en attente de départ',
    badgeClass: 'bg-slate-100 text-slate-600'
  },
  {
    key: 'in_transit',
    label: 'Transit',
    value: props.pipeline?.in_transit ?? 0,
    caption: 'Chargements suivis',
    badgeClass: 'bg-blue-100 text-blue-600'
  },
  {
    key: 'received',
    label: 'Réception',
    value: props.pipeline?.received ?? 0,
    caption: 'Lots en cours de contrôle',
    badgeClass: 'bg-emerald-100 text-emerald-600'
  },
  {
    key: 'quality_check',
    label: 'Qualité',
    value: props.pipeline?.quality_check ?? 0,
    caption: 'Validation laboratoire',
    badgeClass: 'bg-amber-100 text-amber-600'
  }
])

const statusBadge = (status) => {
  switch (status) {
    case 'ordered':
      return 'bg-slate-100 text-slate-600'
    case 'in_transit':
      return 'bg-sky-100 text-sky-600'
    case 'received':
      return 'bg-emerald-100 text-emerald-600'
    case 'quality_check':
      return 'bg-amber-100 text-amber-600'
    default:
      return 'bg-slate-100 text-slate-500'
  }
}

const formatDate = (value) => {
  if (!value) return '—'
  return new Date(value).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' })
}

const formatQuantity = (quantity, unit) => {
  const val = Number(quantity) || 0
  return `${val.toFixed(1)} ${unit}`
}
</script>

