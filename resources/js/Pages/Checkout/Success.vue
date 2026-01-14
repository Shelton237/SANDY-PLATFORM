<template>
  <AppLayout>
    <Head title="Commande confirmée" />

    <section class="bg-gradient-to-br from-[#f2faf0] via-white to-[#fef7ee] border-b border-orange-50 min-h-[70vh]">
      <div class="container mx-auto px-4 py-12 lg:py-16 space-y-8">
        <div class="text-center">
          <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl">
            <i class="bi bi-check2-circle"></i>
          </div>
          <p class="text-xs uppercase tracking-[0.4em] text-[#f49926] mt-6">Merci !</p>
          <h1 class="text-3xl lg:text-4xl font-semibold text-[#254a29] mt-2">
            Commande {{ order.number }} enregistrée
          </h1>
          <p class="text-slate-500 mt-2 max-w-2xl mx-auto">
            Notre équipe vous contactera pour confirmer la livraison. Suivez votre commande depuis votre espace client.
          </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
          <article class="lg:col-span-2 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm space-y-5">
            <div class="grid md:grid-cols-3 gap-4 text-sm text-slate-600">
              <div>
                <p class="text-xs uppercase text-slate-400">Client</p>
                <p class="font-semibold text-[#254a29]">{{ order.customer_name }}</p>
                <p class="text-xs">{{ order.customer_phone }}</p>
              </div>
              <div>
                <p class="text-xs uppercase text-slate-400">Mode</p>
                <p class="font-semibold text-[#254a29]">
                  {{ order.delivery_type === 'pickup' ? 'Retrait atelier' : 'Livraison à domicile' }}
                </p>
                <p class="text-xs">{{ paymentLabel }}</p>
              </div>
              <div>
                <p class="text-xs uppercase text-slate-400">Montant à régler</p>
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

            <div class="grid md:grid-cols-2 gap-4">
              <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Statut commande</p>
                <p class="text-lg font-semibold text-[#254a29] mt-1">{{ statusLabel(order.status) }}</p>
                <p class="text-xs text-slate-500">Mise à jour en temps réel par le backoffice.</p>
              </div>
              <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Paiement</p>
                <p class="text-lg font-semibold text-[#254a29] mt-1">{{ paymentStatusLabel(order.payment_status) }}</p>
                <p class="text-xs text-slate-500">Règlement {{ order.delivery_type === 'pickup' ? 'au comptoir.' : 'à la livraison.' }}</p>
              </div>
            </div>
          </article>

          <aside class="space-y-4">
            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm space-y-3 text-sm text-slate-600">
              <p class="text-xs uppercase text-slate-400">Adresse</p>
              <p v-if="order.address_line1" class="font-semibold text-[#254a29]">
                {{ order.address_line1 }}
              </p>
              <p v-if="order.city">{{ order.city }}</p>
              <p v-if="order.notes" class="text-xs text-slate-400 mt-2">{{ order.notes }}</p>
              <p v-else class="text-xs text-slate-400">Vous pouvez ajouter des instructions depuis votre compte.</p>
            </article>
            <article class="rounded-3xl border border-slate-100 bg-slate-900 text-white p-6 space-y-3">
              <p class="text-xs uppercase tracking-[0.3em] text-orange-200">Pipeline</p>
              <h3 class="text-xl font-semibold">Suivi industriel</h3>
              <p class="text-sm text-white/70">
                Chaque commande alimente les modules Supply → Inventory → Production → Commercialisation → Livraison afin d’orchestrer votre usine de jus.
              </p>
            </article>
            <div class="flex flex-col gap-3">
              <Link :href="route('products')" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 font-semibold text-[#254a29] hover:border-[#f49926] hover:text-[#f49926] transition">
                Retourner au catalogue
              </Link>
              <Link
                v-if="$page.props.auth?.user"
                :href="route('account.orders.show', order.id)"
                class="inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-3 font-semibold text-white hover:bg-[#f28700]"
              >
                Voir dans mon espace
              </Link>
            </div>
          </aside>
        </div>
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
  paymentOptions: {
    type: Object,
    default: () => ({}),
  },
})

const order = props.order

const statusLabel = (status) => {
  const map = {
    pending: 'En attente',
    confirmed: 'Confirmée',
    in_production: 'En préparation',
    ready: 'Prête',
    completed: 'Terminée',
    cancelled: 'Annulée',
  }
  return map[status] ?? status
}

const paymentStatusLabel = (status) => {
  const map = {
    pending: 'À collecter',
    paid: 'Payée',
    refunded: 'Remboursée',
  }
  return map[status] ?? status
}

const paymentLabel = props.paymentOptions?.[order.payment_method] ?? 'Paiement à la livraison'

const formatPrice = (amount) => {
  const number = Number(amount) || 0
  return `${number.toFixed(0)} FCFA`
}
</script>
