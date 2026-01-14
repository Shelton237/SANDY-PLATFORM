<template>
  <AppLayout>
    <Head :title="`Commande ${order.number}`" />
    <section class="container mx-auto px-4 py-10 lg:py-16 space-y-8">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-[#f49926]">Commande</p>
          <h1 class="text-3xl font-semibold text-[#254a29] mt-2">Suivi #{{ order.number }}</h1>
          <p class="text-slate-500 mt-2">Paiement {{ paymentLabel }}</p>
        </div>
        <Link :href="route('account.orders')" class="inline-flex items-center text-sm font-semibold text-[#f49926] hover:text-[#f28700]">
          <i class="bi bi-arrow-left mr-2"></i>
          Retour
        </Link>
      </div>

      <div class="grid lg:grid-cols-3 gap-8">
        <article class="lg:col-span-2 rounded-3xl border border-slate-100 bg-white shadow-sm p-6 space-y-4">
          <div class="grid md:grid-cols-3 gap-4 text-sm text-slate-600">
            <div>
              <p class="text-xs uppercase text-slate-400">Statut</p>
              <span class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" :class="statusClass(order.status)">
                {{ statusLabel(order.status) }}
              </span>
            </div>
            <div>
              <p class="text-xs uppercase text-slate-400">Paiement</p>
              <p class="font-semibold text-[#254a29]">{{ paymentStatusLabel(order.payment_status) }}</p>
              <p class="text-xs text-slate-400">{{ paymentLabel }}</p>
            </div>
            <div>
              <p class="text-xs uppercase text-slate-400">Total</p>
              <p class="text-2xl font-bold text-[#f49926]">{{ formatPrice(order.total) }}</p>
            </div>
          </div>

          <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
            <p class="text-xs uppercase text-slate-400 mb-4">Produits</p>
            <ul class="space-y-4 text-sm text-slate-600">
              <li v-for="item in order.items" :key="item.id" class="flex items-center justify-between">
                <div>
                  <p class="font-semibold text-[#254a29]">{{ item.product_name }}</p>
                  <p class="text-xs text-slate-400">x{{ item.quantity }}</p>
                </div>
                <span class="font-semibold text-[#254a29]">{{ formatPrice(item.total_price) }}</span>
              </li>
            </ul>
          </div>

          <div class="rounded-2xl border border-slate-100 p-5 text-sm text-slate-600">
            <p class="text-xs uppercase text-slate-400">Notes</p>
            <p class="mt-2" v-if="order.notes">{{ order.notes }}</p>
            <p v-else class="text-slate-400">Aucune note.</p>
          </div>
        </article>

        <aside class="space-y-4">
          <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm text-sm text-slate-600">
            <p class="text-xs uppercase text-slate-400">Livraison</p>
            <p class="font-semibold text-[#254a29] mt-2">
              {{ order.delivery_type === 'pickup' ? 'Retrait atelier' : 'Livraison à domicile' }}
            </p>
            <p v-if="order.address_line1" class="mt-2">
              {{ order.address_line1 }}<br />
              {{ order.city }}
            </p>
            <p v-else class="text-slate-400 mt-2">Adresse fournie une fois la livraison confirmée.</p>
          </article>
          <article class="rounded-3xl border border-slate-100 bg-slate-900 text-white p-6 space-y-3 text-sm">
            <p class="text-xs uppercase tracking-[0.3em] text-orange-200">Sandy Process</p>
            <p>Approvisionnement → Stockage → Production → Commercialisation → Livraison.</p>
            <p class="text-white/70">Cette commande alimente automatiquement le backoffice pour orchestrer le circuit.</p>
          </article>
        </aside>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  order: {
    type: Object,
    required: true,
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

const order = props.order

const statusLabel = (status) => props.statusOptions?.[status] ?? status
const paymentStatusLabel = (status) => props.paymentStatuses?.[status] ?? status
const paymentLabel = props.paymentMethods?.[order.payment_method] ?? 'Paiement à la livraison'

const formatPrice = (amount) => `${(Number(amount) || 0).toFixed(0)} FCFA`

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
</script>
