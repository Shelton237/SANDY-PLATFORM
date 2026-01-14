<template>
  <AppLayout>
    <Head title="Mes commandes" />
    <section class="container mx-auto px-4 py-10 lg:py-16 space-y-8">
      <div>
        <p class="text-xs uppercase tracking-[0.4em] text-[#f49926]">Espace client</p>
        <h1 class="text-3xl font-semibold text-[#254a29] mt-2">Historique des commandes</h1>
        <p class="text-slate-500 mt-2">Suivez vos commandes Sandy Juice (paiement en FCFA à la livraison).</p>
      </div>

      <div class="rounded-3xl border border-slate-100 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-widest">
            <tr>
              <th class="py-3 px-4 text-left">Numéro</th>
              <th class="py-3 px-4 text-left">Statut</th>
              <th class="py-3 px-4 text-left">Paiement</th>
              <th class="py-3 px-4 text-right">Total</th>
              <th class="py-3 px-4 text-right">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in orders.data" :key="order.id" class="border-b border-slate-50">
              <td class="py-4 px-4">
                <p class="font-semibold text-[#254a29]">{{ order.number }}</p>
                <p class="text-xs text-slate-400">Placé le {{ formatDate(order.placed_at) }}</p>
              </td>
              <td class="py-4 px-4">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" :class="statusClass(order.status)">
                  {{ statusLabel(order.status) }}
                </span>
              </td>
              <td class="py-4 px-4">
                <p class="font-semibold text-[#254a29]">{{ paymentStatusLabel(order.payment_status) }}</p>
                <p class="text-xs text-slate-400">{{ paymentLabel(order.payment_method) }}</p>
              </td>
              <td class="py-4 px-4 text-right font-semibold text-[#f49926]">{{ formatPrice(order.total) }}</td>
              <td class="py-4 px-4 text-right">
                <Link :href="route('account.orders.show', order.id)" class="inline-flex items-center text-sm font-semibold text-[#f49926] hover:text-[#f28700]">
                  Détails
                  <i class="bi bi-arrow-up-right ml-1"></i>
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="orders.data.length === 0" class="text-center py-10">
          <p class="text-lg font-semibold text-[#254a29]">Aucune commande</p>
          <p class="text-slate-500">Commandez un jus depuis le catalogue pour commencer.</p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  orders: {
    type: Object,
    default: () => ({ data: [] }),
  },
  statusOptions: {
    type: Object,
    default: () => ({}),
  },
  paymentStatuses: {
    type: Object,
    default: () => ({}),
  },
  paymentMethods: {
    type: Object,
    default: () => ({}),
  },
})

const orders = props.orders

const statusLabel = (status) => props.statusOptions?.[status] ?? status
const paymentStatusLabel = (status) => props.paymentStatuses?.[status] ?? status
const paymentLabel = (method) => props.paymentMethods?.[method] ?? 'Paiement à la livraison'

const statusClass = (status) => {
  switch (status) {
    case 'completed':
      return 'bg-emerald-100 text-emerald-700'
    case 'confirmed':
    case 'in_production':
      return 'bg-amber-100 text-amber-700'
    default:
      return 'bg-slate-100 text-slate-600'
  }
}

const formatPrice = (amount) => `${(Number(amount) || 0).toFixed(0)} FCFA`
const formatDate = (value) => (value ? new Date(value).toLocaleDateString('fr-FR') : '—')
</script>
