<template>
  <AdminLayout :title="`Commande ${order.number}`">
    <Head :title="`Commande ${order.number}`" />

    <div class="grid gap-6 lg:grid-cols-3">
      <section class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Commande</p>
            <h1 class="text-2xl font-semibold text-[#254a29]">{{ order.number }}</h1>
            <p class="text-sm text-slate-500">{{ order.customer_name }} • {{ order.customer_email }}</p>
          </div>
          <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
            {{ statusOptions[order.status] }}
          </span>
        </div>

        <div class="border border-slate-100 rounded-2xl p-4 mb-4">
          <h2 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-2">Produit(s)</h2>
          <ul class="divide-y divide-slate-100">
            <li v-for="item in order.items" :key="item.id" class="py-3 flex items-center justify-between">
              <div>
                <p class="font-semibold text-[#254a29]">{{ item.product_name }}</p>
                <p class="text-xs text-slate-500">{{ item.quantity }} x {{ formatPrice(item.unit_price) }}</p>
              </div>
              <p class="text-sm font-semibold text-[#f49926]">{{ formatPrice(item.total_price) }}</p>
            </li>
          </ul>
        </div>

        <div class="border border-slate-100 rounded-2xl p-4 text-sm text-slate-600 space-y-2">
          <div class="flex items-center justify-between">
            <span>Sous-total</span>
            <span class="font-semibold">{{ formatPrice(order.subtotal) }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span>Livraison</span>
            <span class="font-semibold">{{ formatPrice(order.delivery_fee) }}</span>
          </div>
          <div class="flex items-center justify-between text-lg">
            <span>Total</span>
            <span class="font-semibold text-[#f49926]">{{ formatPrice(order.total) }}</span>
          </div>
        </div>
      </section>

      <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Statut commande</label>
            <select v-model="form.status" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
              <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Statut paiement</label>
            <select v-model="form.payment_status" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
              <option v-for="(label, value) in paymentOptions" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Mode de paiement</label>
            <select v-model="form.payment_method" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
              <option v-for="(label, value) in paymentMethods" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Statut livraison</label>
            <select v-model="form.delivery_status" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
              <option value="">—</option>
              <option v-for="(label, value) in deliveryStatuses" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Notes internes</label>
            <textarea v-model="form.notes" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
          </div>
          <button type="submit" class="w-full rounded-2xl bg-[#254a29] text-white py-3 font-semibold flex items-center justify-center gap-2">
            <i class="bi bi-save"></i>
            Mettre à jour
          </button>
        </form>

        <div v-if="order.delivery" class="border border-slate-100 rounded-2xl p-4 text-sm text-slate-600 space-y-1">
          <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-2">Livraison</h3>
          <p>{{ order.delivery.address_line1 }} {{ order.delivery.city }}</p>
          <p>Créneau: {{ order.delivery.time_window || '—' }}</p>
          <p>Coursier: {{ order.delivery.courier_name || '—' }}</p>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  order: {
    type: Object,
    required: true
  },
  statusOptions: {
    type: Object,
    default: () => ({})
  },
  paymentOptions: {
    type: Object,
    default: () => ({})
  },
  paymentMethods: {
    type: Object,
    default: () => ({})
  },
  deliveryStatuses: {
    type: Object,
    default: () => ({})
  }
})

const form = useForm({
  status: props.order.status,
  payment_status: props.order.payment_status,
  payment_method: props.order.payment_method,
  delivery_status: props.order.delivery?.status || '',
  notes: props.order.notes || ''
})

const submit = () => {
  form.put(route('admin.orders.update', props.order.id))
}

const formatPrice = (value) => {
  const number = Number(value) || 0
  return `${number.toFixed(0)} FCFA`
}
</script>

