<template>
  <AdminLayout title="Dashboard">
    <Head title="Dashboard — Sandy Juice" />

    <!-- Métriques principales -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
      <article v-for="card in metricCards" :key="card.label"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex items-start gap-4">
        <div class="shrink-0 rounded-xl p-2.5" :class="card.iconBg">
          <i :class="['bi text-xl', card.icon, card.iconColor]"></i>
        </div>
        <div>
          <p class="text-xs uppercase tracking-widest text-slate-400">{{ card.label }}</p>
          <p class="mt-1 text-2xl font-bold text-[#254a29]">{{ card.value }}</p>
          <p class="text-xs text-slate-400 mt-0.5">{{ card.caption }}</p>
        </div>
      </article>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

      <!-- Commandes récentes -->
      <section class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <h2 class="font-semibold text-[#254a29]">Commandes récentes</h2>
          <Link :href="route('admin.orders.index')" class="text-xs font-semibold text-[#f49926] hover:text-[#c44010]">
            Voir toutes
          </Link>
        </div>
        <div class="divide-y divide-slate-100">
          <div v-if="!recentOrders.length" class="px-6 py-8 text-center text-sm text-slate-400">
            Aucune commande pour le moment.
          </div>
          <Link
            v-for="order in recentOrders"
            :key="order.id"
            :href="route('admin.orders.show', order.id)"
            class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 transition"
          >
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-[#254a29] truncate">#{{ order.number }}</p>
              <p class="text-xs text-slate-400 truncate">{{ order.customer_name }}</p>
            </div>
            <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full" :class="statusClass(order.status)">
              {{ statusLabel(order.status) }}
            </span>
            <span class="shrink-0 text-sm font-bold text-[#254a29]">{{ formatPrice(order.total) }}</span>
          </Link>
        </div>
      </section>

      <!-- Pipeline statuts -->
      <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
          <h2 class="font-semibold text-[#254a29]">Pipeline commandes</h2>
        </div>
        <ul class="divide-y divide-slate-100">
          <li v-for="(item, key) in pipelineItems" :key="key"
            class="flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full" :class="item.dot"></span>
              <span class="text-sm text-slate-600">{{ item.label }}</span>
            </div>
            <span class="text-lg font-bold text-[#254a29]">{{ item.count }}</span>
          </li>
        </ul>
        <div class="px-6 py-4 border-t border-slate-100 flex gap-3">
          <Link :href="route('admin.orders.index')" class="flex-1 text-center text-xs font-semibold py-2 rounded-xl bg-[#254a29] text-white hover:bg-[#1c3820] transition">
            Gérer les commandes
          </Link>
          <Link :href="route('admin.products.index')" class="flex-1 text-center text-xs font-semibold py-2 rounded-xl border border-slate-200 text-slate-600 hover:border-[#254a29] transition">
            Catalogue
          </Link>
        </div>
      </section>
    </div>

    <!-- Top produits + timeline côte à côte -->
    <div class="grid gap-6 lg:grid-cols-2 mt-6">

      <!-- Top produits -->
      <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <h2 class="font-semibold text-[#254a29]">Top produits</h2>
          <Link :href="route('admin.products.index')" class="text-xs font-semibold text-[#f49926] hover:text-[#c44010]">Gérer</Link>
        </div>
        <div class="divide-y divide-slate-100">
          <div v-if="!topProducts.length" class="px-6 py-8 text-center text-sm text-slate-400">
            Aucune vente enregistrée.
          </div>
          <div v-for="(product, i) in topProducts" :key="product.id"
            class="flex items-center gap-3 px-6 py-3">
            <span class="shrink-0 w-6 h-6 rounded-full bg-slate-100 text-xs font-bold text-slate-400 flex items-center justify-center">
              {{ i + 1 }}
            </span>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-[#254a29] truncate">{{ product.name }}</p>
              <p class="text-xs text-slate-400">{{ product.category }}</p>
            </div>
            <div class="text-right shrink-0">
              <p class="text-sm font-bold text-[#254a29]">{{ product.total_quantity ?? 0 }} <span class="text-xs font-normal text-slate-400">vendus</span></p>
              <p class="text-xs text-slate-400">{{ formatPrice(product.price) }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Timeline 14j -->
      <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
          <h2 class="font-semibold text-[#254a29]">Commandes — 14 derniers jours</h2>
        </div>
        <div class="px-6 py-4">
          <div v-if="!timeline.length" class="py-8 text-center text-sm text-slate-400">
            Aucune donnée disponible.
          </div>
          <div v-else class="space-y-2">
            <div v-for="point in timeline" :key="point.date" class="flex items-center gap-3">
              <span class="text-xs text-slate-400 w-20 shrink-0">{{ point.date }}</span>
              <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full bg-[#E85A14]" :style="{ width: barWidth(point.total) + '%' }"></div>
              </div>
              <span class="text-sm font-semibold text-[#254a29] w-6 text-right shrink-0">{{ point.total }}</span>
            </div>
          </div>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  metrics:         { type: Object, default: () => ({}) },
  ordersTimeline:  { type: Array,  default: () => [] },
  topProducts:     { type: Array,  default: () => [] },
  statuses:        { type: Object, default: () => ({}) },
  recentOrders:    { type: Array,  default: () => [] },
  deliveryPipeline:{ type: Object, default: () => ({}) },
})

const metricCards = computed(() => [
  {
    label: 'Commandes aujourd\'hui',
    value: props.metrics.orders_today ?? 0,
    caption: 'Depuis minuit',
    icon: 'bi-bag-check',
    iconBg: 'bg-orange-50',
    iconColor: 'text-[#E85A14]',
  },
  {
    label: 'En attente',
    value: props.metrics.orders_pending ?? 0,
    caption: 'À traiter',
    icon: 'bi-hourglass-split',
    iconBg: 'bg-amber-50',
    iconColor: 'text-amber-500',
  },
  {
    label: 'Revenus du mois',
    value: formatPrice(props.metrics.revenue_month ?? 0),
    caption: 'Hors annulations',
    icon: 'bi-cash-stack',
    iconBg: 'bg-green-50',
    iconColor: 'text-green-600',
  },
  {
    label: 'Produits publiés',
    value: props.metrics.products_published ?? 0,
    caption: 'Dans le catalogue',
    icon: 'bi-cup-straw',
    iconBg: 'bg-[#254a29]/10',
    iconColor: 'text-[#254a29]',
  },
])

const pipelineItems = computed(() => [
  { label: 'En attente',      count: props.statuses.pending    ?? 0, dot: 'bg-amber-400' },
  { label: 'En préparation',  count: props.statuses.production ?? 0, dot: 'bg-blue-400'  },
  { label: 'Prêtes',          count: props.statuses.ready      ?? 0, dot: 'bg-emerald-400' },
  { label: 'Terminées',       count: props.statuses.completed  ?? 0, dot: 'bg-slate-300'  },
  { label: 'Annulées',        count: props.statuses.cancelled  ?? 0, dot: 'bg-red-400'    },
])

const timeline = computed(() => props.ordersTimeline.map(r => ({ date: r.date, total: Number(r.total) })))
const maxOrders = computed(() => Math.max(...timeline.value.map(r => r.total), 1))
const barWidth  = (total) => Math.round((total / maxOrders.value) * 100)

const STATUS_LABELS = {
  pending:      'En attente',
  confirmed:    'Confirmée',
  in_production:'En préparation',
  ready:        'Prête',
  completed:    'Terminée',
  cancelled:    'Annulée',
}
const STATUS_CLASSES = {
  pending:      'bg-amber-50 text-amber-700',
  confirmed:    'bg-blue-50 text-blue-700',
  in_production:'bg-indigo-50 text-indigo-700',
  ready:        'bg-emerald-50 text-emerald-700',
  completed:    'bg-slate-100 text-slate-600',
  cancelled:    'bg-red-50 text-red-600',
}
const statusLabel = (s) => STATUS_LABELS[s] ?? s
const statusClass = (s) => STATUS_CLASSES[s] ?? 'bg-slate-100 text-slate-600'

const formatPrice = (value) => `${(Number(value) || 0).toFixed(0)} FCFA`
</script>
