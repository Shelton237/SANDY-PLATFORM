<template>
  <AppLayout>
    <Head title="Validation de commande" />

    <section class="bg-gradient-to-br from-[#f2faf0] via-white to-[#fef7ee] border-b border-orange-50">
      <div class="container mx-auto px-4 py-10 lg:py-16 space-y-8">
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-[#f49926]">Checkout</p>
          <h1 class="text-3xl lg:text-4xl font-semibold text-[#254a29] mt-2">Confirmez votre commande</h1>
          <p class="text-slate-500 mt-2">
            Paiement à la livraison – nous vous contacterons pour confirmer l’horaire.
          </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
          <form class="lg:col-span-2 space-y-6" @submit.prevent="submit">
            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm space-y-4">
              <h2 class="text-sm uppercase tracking-[0.3em] text-slate-400">Coordonnées</h2>
              <div class="grid md:grid-cols-2 gap-4">
                <div>
                  <label class="text-xs uppercase text-slate-500">Nom complet</label>
                  <input v-model="form.customer_name" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30" />
                  <p v-if="form.errors.customer_name" class="text-xs text-rose-500 mt-1">{{ form.errors.customer_name }}</p>
                </div>
                <div>
                  <label class="text-xs uppercase text-slate-500">Téléphone</label>
                  <input v-model="form.customer_phone" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30" />
                  <p v-if="form.errors.customer_phone" class="text-xs text-rose-500 mt-1">{{ form.errors.customer_phone }}</p>
                </div>
              </div>
              <div>
                <label class="text-xs uppercase text-slate-500">Email</label>
                <input v-model="form.customer_email" type="email" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30" />
                <p v-if="form.errors.customer_email" class="text-xs text-rose-500 mt-1">{{ form.errors.customer_email }}</p>
              </div>
            </article>

            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm space-y-4">
              <h2 class="text-sm uppercase tracking-[0.3em] text-slate-400">Livraison</h2>
              <div class="grid md:grid-cols-2 gap-4">
                <label
                  v-for="option in deliveryOptions"
                  :key="option.value"
                  class="flex gap-3 rounded-2xl border px-4 py-3 cursor-pointer transition"
                  :class="form.delivery_type === option.value ? 'border-[#f49926] bg-[#fef4e7]' : 'border-slate-200'"
                >
                  <input v-model="form.delivery_type" type="radio" class="mt-1" :value="option.value" />
                  <div>
                    <p class="font-semibold text-[#254a29]">{{ option.label }}</p>
                    <p class="text-xs text-slate-500">{{ option.description }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                      Frais : <strong>{{ option.fee === 0 ? 'Offert' : formatPrice(option.fee) }}</strong>
                    </p>
                  </div>
                </label>
              </div>
              <div v-if="form.delivery_type === 'delivery'" class="space-y-4">
                <div>
                  <label class="text-xs uppercase text-slate-500">Adresse</label>
                  <input v-model="form.address_line1" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30" />
                  <p v-if="form.errors.address_line1" class="text-xs text-rose-500 mt-1">{{ form.errors.address_line1 }}</p>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="text-xs uppercase text-slate-500">Complément</label>
                    <input v-model="form.address_line2" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30" />
                  </div>
                  <div>
                    <label class="text-xs uppercase text-slate-500">Ville</label>
                    <input v-model="form.city" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30" />
                  </div>
                </div>
              </div>
              <div>
                <label class="text-xs uppercase text-slate-500">Instructions</label>
                <textarea v-model="form.notes" rows="3" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30" placeholder="Digicode, étage, préférences..."></textarea>
              </div>
            </article>

            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm space-y-4">
              <h2 class="text-sm uppercase tracking-[0.3em] text-slate-400">Paiement</h2>
              <div class="grid md:grid-cols-2 gap-4">
                <label
                  v-for="(label, key) in paymentOptions"
                  :key="key"
                  class="flex gap-3 rounded-2xl border px-4 py-3 cursor-pointer transition"
                  :class="form.payment_method === key ? 'border-[#254a29] bg-[#f2faf0]' : 'border-slate-200'"
                >
                  <input v-model="form.payment_method" type="radio" class="mt-1" :value="key" />
                  <div>
                    <p class="font-semibold text-[#254a29]">{{ label }}</p>
                    <p class="text-xs text-slate-500">Espèces ou Mobile Money.</p>
                  </div>
                </label>
              </div>
              <div class="rounded-2xl bg-[#fef4e7] border border-[#f49926]/40 px-4 py-3 text-sm text-[#b45309]">
                <i class="bi bi-shield-check mr-2"></i>
                Aucun paiement en ligne – règlement uniquement à la livraison ou au retrait.
              </div>
            </article>

            <div class="flex justify-end">
              <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-2xl bg-[#f49926] px-6 py-3 font-semibold text-white hover:bg-[#f28700] transition disabled:opacity-50"
                :disabled="form.processing"
              >
                <i class="bi bi-lightning-charge"></i>
                Confirmer la commande
              </button>
            </div>
          </form>

          <aside class="space-y-5">
            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
              <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400">Panier</h3>
              <ul class="divide-y divide-slate-100 mt-4">
                <li v-for="item in cart.items" :key="item.product_id" class="py-3 flex items-center justify-between text-sm text-slate-600">
                  <div>
                    <p class="font-semibold text-[#254a29]">{{ item.name }}</p>
                    <p class="text-xs text-slate-400">x{{ item.quantity }} • {{ item.size || 'Format' }}</p>
                  </div>
                  <span class="font-semibold text-[#254a29]">{{ formatPrice(item.total) }}</span>
                </li>
              </ul>
              <div class="mt-4 space-y-2 text-sm text-slate-500">
                <div class="flex justify-between">
                  <span>Sous-total</span>
                  <span class="font-semibold text-[#254a29]">{{ formatPrice(cart.subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Livraison</span>
                  <span class="font-semibold text-[#254a29]">{{ summary.delivery_fee === 0 ? 'Offert' : formatPrice(summary.delivery_fee) }}</span>
                </div>
              </div>
              <div class="mt-4 border-t border-dashed border-slate-200 pt-4">
                <div class="flex justify-between text-base">
                  <span class="font-semibold text-[#254a29]">Total à régler</span>
                  <span class="text-2xl font-bold text-[#f49926]">{{ formatPrice(summary.total) }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-2">
                  Paiement {{ paymentOptions[form.payment_method] || 'à la livraison' }}.
                </p>
              </div>
            </article>
            <article class="rounded-3xl border border-slate-100 bg-slate-900 text-white p-6 space-y-3">
              <p class="text-xs uppercase tracking-[0.3em] text-orange-200">Traçabilité</p>
              <h3 class="text-xl font-semibold">Circuit complet</h3>
              <p class="text-sm text-white/70">
                Nous suivons chaque commande à travers l’approvisionnement, la production, le stockage et la livraison afin de piloter l’usine en temps réel.
              </p>
            </article>
          </aside>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  cart: {
    type: Object,
    required: true,
  },
  deliveryOptions: {
    type: Array,
    default: () => [],
  },
  paymentOptions: {
    type: Object,
    default: () => ({}),
  },
  prefill: {
    type: Object,
    default: () => ({}),
  },
})

const cart = computed(() => props.cart ?? { items: [] })
const deliveryOptions = computed(() => props.deliveryOptions ?? [])
const paymentOptions = computed(() => props.paymentOptions ?? {})

const form = useForm({
  customer_name: props.prefill.customer_name || '',
  customer_email: props.prefill.customer_email || '',
  customer_phone: props.prefill.customer_phone || '',
  address_line1: '',
  address_line2: '',
  city: 'Libreville',
  notes: '',
  delivery_type: 'delivery',
  payment_method: Object.keys(paymentOptions.value)[0] || 'cod',
})

const summary = computed(() => {
  const deliveryFee = form.delivery_type === 'pickup' ? 0 : cart.value.delivery_fee
  return {
    delivery_fee: deliveryFee,
    total: (cart.value.subtotal || 0) + deliveryFee,
  }
})

const submit = () => {
  form.post(route('checkout.process'))
}

const formatPrice = (amount) => {
  const number = Number(amount) || 0
  return `${number.toFixed(0)} ${cart.value.currency || 'FCFA'}`
}
</script>
