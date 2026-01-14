<template>
  <AdminLayout title="Dashboard backoffice">
    <Head title="Dashboard" />

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-8">
      <article v-for="card in metricCards" :key="card.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-3xl font-semibold text-[#254a29]">{{ card.value }}</p>
        <p class="text-sm text-slate-500">{{ card.caption }}</p>
      </article>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
      <section class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">14 derniers jours</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Flux de commandes</h2>
          </div>
        </div>
        <div class="grid gap-3 md:grid-cols-2">
          <div v-for="point in timeline" :key="point.date" class="flex items-center justify-between rounded-2xl border border-slate-100 px-4 py-3">
            <span class="text-sm text-slate-500">{{ point.date }}</span>
            <span class="text-lg font-semibold text-[#254a29]">{{ point.total }}</span>
          </div>
        </div>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Status commandes</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Pipeline</h2>
          </div>
        </div>
        <ul class="space-y-3 text-sm">
          <li v-for="(value, key) in statuses" :key="key" class="flex items-center justify-between border-b border-slate-100 pb-3 last:border-none last:pb-0">
            <span class="capitalize text-slate-500">{{ statusLabels[key] || key }}</span>
            <span class="text-lg font-semibold text-[#254a29]">{{ value }}</span>
          </li>
        </ul>
      </section>
    </div>

    <section class="mt-8 grid gap-4 lg:grid-cols-5">
      <Link
        v-for="stage in supplyChainFlow"
        :key="stage.label"
        :href="route(stage.route)"
        class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:border-[#f49926]/50 transition"
      >
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ stage.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ stage.value }}</p>
        <p class="text-sm text-slate-500">{{ stage.caption }}</p>
      </Link>
    </section>

    <section class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Top recettes</p>
          <h2 class="text-xl font-semibold text-[#254a29]">Produits plébiscités</h2>
        </div>
        <Link :href="route('admin.products.index')" class="text-sm font-semibold text-[#f49926] hover:text-[#f28700]">
          Voir tout
        </Link>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-slate-400 uppercase text-xs tracking-widest border-b border-slate-100">
              <th class="py-3">Produit</th>
              <th class="py-3">Categorie</th>
              <th class="py-3 text-right">Quantite</th>
              <th class="py-3 text-right">Prix</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in topProducts" :key="product.id" class="border-b border-slate-100 last:border-none">
              <td class="py-3 font-semibold text-[#254a29]">
                {{ product.name }}
              </td>
              <td class="py-3 text-slate-500">{{ product.category }}</td>
              <td class="py-3 text-right font-semibold">{{ product.total_quantity ?? 0 }}</td>
              <td class="py-3 text-right text-slate-500">{{ formatPrice(product.price) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  metrics: {
    type: Object,
    default: () => ({})
  },
  ordersTimeline: {
    type: Array,
    default: () => []
  },
  topProducts: {
    type: Array,
    default: () => []
  },
  statuses: {
    type: Object,
    default: () => ({})
  },
  supplyPipeline: {
    type: Object,
    default: () => ({})
  },
  inventoryHealth: {
    type: Object,
    default: () => ({})
  },
  productionPipeline: {
    type: Object,
    default: () => ({})
  },
  deliveryPipeline: {
    type: Object,
    default: () => ({})
  }
})

const metricCards = computed(() => [
  {
    label: 'Commandes jour',
    value: props.metrics.orders_today ?? 0,
    caption: '24h glissantes'
  },
  {
    label: 'Revenu mois',
    value: formatPrice(props.metrics.revenue_month ?? 0),
    caption: 'Chiffre HT'
  },
  {
    label: 'Recettes actives',
    value: props.metrics.products_published ?? 0,
    caption: 'Produits publiés'
  },
  {
    label: 'Livraisons jour',
    value: props.metrics.deliveries_today ?? 0,
    caption: 'Slot planifiés'
  }
])

const statusLabels = {
  pending: 'En attente',
  production: 'En production',
  ready: 'Prêtes',
  completed: 'Terminées'
}

const timeline = computed(() =>
  props.ordersTimeline.map((row) => ({
    date: row.date,
    total: row.total
  })),
)

const supplyChainFlow = computed(() => [
  {
    label: 'Approvisionnement',
    value: `${(props.supplyPipeline?.ordered ?? 0) + (props.supplyPipeline?.in_transit ?? 0)} lots`,
    caption: 'Commandes en cours',
    route: 'admin.supply'
  },
  {
    label: 'Stockage',
    value: formatKg(props.inventoryHealth?.available ?? 0),
    caption: 'Matières disponibles',
    route: 'admin.inventory'
  },
  {
    label: 'Production',
    value: `${props.productionPipeline?.in_progress ?? 0} batchs`,
    caption: 'Atelier en action',
    route: 'admin.production'
  },
  {
    label: 'Commercialisation',
    value: `${props.statuses.production ?? 0} commandes`,
    caption: 'Ventes en préparation',
    route: 'admin.orders.index'
  },
  {
    label: 'Livraison',
    value: `${props.deliveryPipeline?.dispatched ?? 0} tournées`,
    caption: 'Vans en circulation',
    route: 'admin.deliveries.index'
  }
])

const formatPrice = (value) => {
  const number = Number(value) || 0
  return `${number.toFixed(0)} FCFA`
}

const formatKg = (value) => {
  const number = Number(value) || 0
  return `${number.toFixed(1)} kg`
}
</script>
