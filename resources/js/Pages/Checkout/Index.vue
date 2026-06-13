<template>
  <AppLayout>
    <Head title="Validation de commande" />

    <div class="container mx-auto px-4 py-6 lg:py-10 max-w-5xl">

      <!-- En-tête -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <Link :href="route('cart')" class="text-sm text-slate-400 hover:text-[#254a29] flex items-center gap-1.5 transition mb-1">
            <i class="bi bi-arrow-left text-xs"></i> Retour au panier
          </Link>
          <h1 class="text-2xl font-bold text-[#254a29]">Validation de commande</h1>
        </div>
        <!-- Étapes -->
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400">
          <span class="flex items-center gap-1.5">
            <span class="w-5 h-5 rounded-full bg-[#254a29] text-white flex items-center justify-center text-[10px] font-bold">1</span>
            Panier
          </span>
          <i class="bi bi-chevron-right text-slate-300"></i>
          <span class="flex items-center gap-1.5 text-[#254a29] font-semibold">
            <span class="w-5 h-5 rounded-full bg-[#f49926] text-white flex items-center justify-center text-[10px] font-bold">2</span>
            Commande
          </span>
          <i class="bi bi-chevron-right text-slate-300"></i>
          <span class="flex items-center gap-1.5">
            <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center text-[10px] font-bold">3</span>
            Confirmation
          </span>
        </div>
      </div>

      <div class="grid lg:grid-cols-5 gap-6 items-start">

        <!-- Formulaire -->
        <form class="lg:col-span-3 space-y-5" @submit.prevent="submit">

          <!-- Erreurs globales -->
          <div v-if="hasErrors" class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
            <i class="bi bi-exclamation-circle mr-2"></i>
            Merci de corriger les champs en erreur ci-dessous.
          </div>

          <!-- Section 1 : Coordonnées -->
          <section class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
            <h2 class="flex items-center gap-2.5 font-bold text-[#254a29]">
              <span class="w-6 h-6 rounded-full bg-[#254a29] text-white text-xs flex items-center justify-center font-bold shrink-0">1</span>
              Vos coordonnées
            </h2>

            <div>
              <label class="block text-xs font-semibold text-slate-500 mb-1">Nom complet <span class="text-rose-400">*</span></label>
              <input
                v-model="form.customer_name"
                type="text"
                placeholder="Ex : Mireille Nguema"
                :class="[fieldBase, form.errors.customer_name ? fieldError : fieldNormal]"
              />
              <p v-if="form.errors.customer_name" class="text-xs text-rose-500 mt-1">{{ form.errors.customer_name }}</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Téléphone <span class="text-rose-400">*</span></label>
                <input
                  v-model="form.customer_phone"
                  type="tel"
                  placeholder="+237 6XX XXX XXX"
                  :class="[fieldBase, form.errors.customer_phone ? fieldError : fieldNormal]"
                />
                <p v-if="form.errors.customer_phone" class="text-xs text-rose-500 mt-1">{{ form.errors.customer_phone }}</p>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Email <span class="text-rose-400">*</span></label>
                <input
                  v-model="form.customer_email"
                  type="email"
                  placeholder="vous@example.com"
                  :class="[fieldBase, form.errors.customer_email ? fieldError : fieldNormal]"
                />
                <p v-if="form.errors.customer_email" class="text-xs text-rose-500 mt-1">{{ form.errors.customer_email }}</p>
              </div>
            </div>
          </section>

          <!-- Section 2 : Livraison -->
          <section class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
            <h2 class="flex items-center gap-2.5 font-bold text-[#254a29]">
              <span class="w-6 h-6 rounded-full bg-[#254a29] text-white text-xs flex items-center justify-center font-bold shrink-0">2</span>
              Mode de réception
            </h2>

            <div class="grid sm:grid-cols-2 gap-3">
              <label
                v-for="option in deliveryOptions"
                :key="option.value"
                class="relative flex gap-3 rounded-xl border-2 p-4 cursor-pointer transition select-none"
                :class="form.delivery_type === option.value
                  ? 'border-[#f49926] bg-[#fef4e7]'
                  : 'border-slate-200 hover:border-slate-300'"
              >
                <input v-model="form.delivery_type" type="radio" class="sr-only" :value="option.value" />
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                  :class="form.delivery_type === option.value ? 'bg-[#f49926] text-white' : 'bg-slate-100 text-slate-400'">
                  <i :class="['bi', option.value === 'delivery' ? 'bi-bicycle' : 'bi-shop']"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-semibold text-[#254a29] text-sm">{{ option.label }}</p>
                  <p class="text-xs text-slate-500 mt-0.5 leading-snug">{{ option.description }}</p>
                  <p class="text-xs font-semibold mt-1" :class="option.fee === 0 ? 'text-emerald-600' : 'text-[#f49926]'">
                    {{ option.fee === 0 ? 'Gratuit' : formatPrice(option.fee) }}
                  </p>
                </div>
                <div v-if="form.delivery_type === option.value"
                  class="absolute top-2 right-2 w-4 h-4 rounded-full bg-[#f49926] flex items-center justify-center">
                  <i class="bi bi-check text-white text-[10px]"></i>
                </div>
              </label>
            </div>

            <!-- Adresse (livraison uniquement) -->
            <div v-if="form.delivery_type === 'delivery'" class="space-y-3 pt-1">
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Adresse / Quartier <span class="text-rose-400">*</span></label>
                <input
                  v-model="form.address_line1"
                  type="text"
                  placeholder="Ex : Quartier Bastos, Rue des Manguiers"
                  :class="[fieldBase, form.errors.address_line1 ? fieldError : fieldNormal]"
                />
                <p v-if="form.errors.address_line1" class="text-xs text-rose-500 mt-1">{{ form.errors.address_line1 }}</p>
              </div>
              <div class="grid sm:grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Complément</label>
                  <input
                    v-model="form.address_line2"
                    type="text"
                    placeholder="Bâtiment, étage..."
                    :class="[fieldBase, fieldNormal]"
                  />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Ville</label>
                  <input
                    v-model="form.city"
                    type="text"
                    :class="[fieldBase, fieldNormal]"
                  />
                </div>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-500 mb-1">Instructions (optionnel)</label>
              <textarea
                v-model="form.notes"
                rows="2"
                :class="[fieldBase, fieldNormal, 'resize-none']"
                placeholder="Précisions sur l'accès, horaires préférés..."
              ></textarea>
            </div>
          </section>

          <!-- Section 3 : Paiement -->
          <section class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
            <h2 class="flex items-center gap-2.5 font-bold text-[#254a29]">
              <span class="w-6 h-6 rounded-full bg-[#254a29] text-white text-xs flex items-center justify-center font-bold shrink-0">3</span>
              Mode de paiement
            </h2>

            <div class="space-y-2">
              <label
                v-for="(label, key) in paymentOptions"
                :key="key"
                class="relative flex gap-3 rounded-xl border-2 p-4 cursor-pointer transition select-none"
                :class="form.payment_method === key
                  ? 'border-[#254a29] bg-[#f2faf0]'
                  : 'border-slate-200 hover:border-slate-300'"
              >
                <input v-model="form.payment_method" type="radio" class="sr-only" :value="key" />
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                  :class="form.payment_method === key ? 'bg-[#254a29] text-white' : 'bg-slate-100 text-slate-400'">
                  <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                  <p class="font-semibold text-[#254a29] text-sm">{{ label }}</p>
                  <p class="text-xs text-slate-500 mt-0.5">Espèces ou Mobile Money (MTN/Orange)</p>
                </div>
                <div v-if="form.payment_method === key"
                  class="absolute top-2 right-2 w-4 h-4 rounded-full bg-[#254a29] flex items-center justify-center">
                  <i class="bi bi-check text-white text-[10px]"></i>
                </div>
              </label>
            </div>

            <div class="flex items-start gap-2.5 rounded-xl bg-amber-50 border border-amber-100 px-4 py-3 text-xs text-amber-800">
              <i class="bi bi-shield-check text-amber-500 text-base shrink-0 mt-0.5"></i>
              <span>Aucun paiement en ligne — vous réglez uniquement à la livraison ou au retrait.</span>
            </div>
          </section>

          <!-- Bouton submit desktop -->
          <button
            type="submit"
            class="hidden lg:flex w-full items-center justify-center gap-2 rounded-xl bg-[#E85A14] hover:bg-[#d45012] text-white font-bold py-4 text-base transition disabled:opacity-50"
            :disabled="form.processing"
          >
            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
            <i v-else class="bi bi-bag-check"></i>
            Confirmer la commande
          </button>
        </form>

        <!-- Récapitulatif commande -->
        <aside class="lg:col-span-2 lg:sticky lg:top-24 space-y-4">
          <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
            <h3 class="font-bold text-[#254a29]">Votre commande</h3>

            <!-- Articles -->
            <ul class="space-y-3">
              <li
                v-for="item in cart.items"
                :key="item.product_id"
                class="flex items-center gap-3"
              >
                <div class="w-10 h-10 rounded-lg bg-[#fef7ee] border border-orange-100 overflow-hidden shrink-0">
                  <img v-if="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
                  <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                    <i class="bi bi-image text-xs"></i>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-[#254a29] truncate">{{ item.name }}</p>
                  <p class="text-xs text-slate-400">x{{ item.quantity }}</p>
                </div>
                <span class="text-sm font-semibold text-slate-700 shrink-0">{{ formatPrice(item.total) }}</span>
              </li>
            </ul>

            <!-- Totaux -->
            <div class="border-t border-dashed border-slate-200 pt-3 space-y-2 text-sm">
              <div class="flex justify-between text-slate-500">
                <span>Sous-total</span>
                <span class="font-medium text-slate-700">{{ formatPrice(cart.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-slate-500">
                <span>Livraison</span>
                <span v-if="summary.delivery_fee === 0" class="font-medium text-emerald-600">Offerte</span>
                <span v-else class="font-medium text-slate-700">{{ formatPrice(summary.delivery_fee) }}</span>
              </div>
            </div>

            <div class="border-t border-slate-200 pt-3 flex items-center justify-between">
              <span class="font-bold text-[#254a29]">Total à régler</span>
              <span class="text-2xl font-bold text-[#E85A14]">{{ formatPrice(summary.total) }}</span>
            </div>

            <!-- Bouton desktop dans le sidebar aussi -->
            <button
              type="button"
              class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#E85A14] hover:bg-[#d45012] text-white font-bold py-3.5 transition disabled:opacity-50"
              :disabled="form.processing"
              @click="submit"
            >
              <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
              <i v-else class="bi bi-bag-check"></i>
              Confirmer la commande
            </button>

            <p class="text-center text-xs text-slate-400 flex items-center justify-center gap-1.5">
              <i class="bi bi-lock-fill text-slate-300"></i>
              Paiement à la livraison — aucun prélèvement en ligne
            </p>
          </div>

          <!-- Trust badges -->
          <div class="grid grid-cols-3 gap-2 text-center text-xs text-slate-500">
            <div class="bg-white rounded-xl border border-slate-100 p-3">
              <i class="bi bi-lightning-charge text-[#f49926] text-lg block mb-1"></i>
              Livraison<br>express 2h
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-3">
              <i class="bi bi-leaf text-emerald-500 text-lg block mb-1"></i>
              Pressé<br>du matin
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-3">
              <i class="bi bi-telephone text-[#254a29] text-lg block mb-1"></i>
              On vous<br>rappelle
            </div>
          </div>
        </aside>
      </div>
    </div>

    <!-- Barre sticky mobile -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-3 z-40 flex items-center justify-between gap-4">
      <div>
        <p class="text-xs text-slate-400">Total</p>
        <p class="font-bold text-[#E85A14] text-lg leading-none">{{ formatPrice(summary.total) }}</p>
      </div>
      <button
        type="button"
        class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-[#E85A14] hover:bg-[#d45012] text-white font-bold py-3 transition disabled:opacity-50"
        :disabled="form.processing"
        @click="submit"
      >
        <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
        <i v-else class="bi bi-bag-check"></i>
        Confirmer
      </button>
    </div>

    <!-- Espace mobile pour la barre sticky -->
    <div class="lg:hidden h-20"></div>

  </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  cart:            { type: Object, required: true },
  deliveryOptions: { type: Array,  default: () => [] },
  paymentOptions:  { type: Object, default: () => ({}) },
  prefill:         { type: Object, default: () => ({}) },
})

const cart            = computed(() => props.cart ?? { items: [] })
const deliveryOptions = computed(() => props.deliveryOptions ?? [])
const paymentOptions  = computed(() => props.paymentOptions ?? {})

const form = useForm({
  customer_name:  props.prefill.customer_name  || '',
  customer_email: props.prefill.customer_email || '',
  customer_phone: props.prefill.customer_phone || '',
  address_line1:  '',
  address_line2:  '',
  city:           'Yaoundé',
  notes:          '',
  delivery_type:  'delivery',
  payment_method: Object.keys(paymentOptions.value)[0] || 'cod',
})

const hasErrors = computed(() => Object.keys(form.errors).length > 0)

const summary = computed(() => {
  const fee = form.delivery_type === 'pickup' ? 0 : (cart.value.delivery_fee ?? 0)
  return {
    delivery_fee: fee,
    total: (cart.value.subtotal || 0) + fee,
  }
})

const submit = () => form.post(route('checkout.process'))

const formatPrice = (amount) => `${(Number(amount) || 0).toFixed(0)} ${cart.value.currency || 'FCFA'}`

const fieldBase   = 'w-full rounded-xl border px-4 py-2.5 text-sm text-[#254a29] outline-none transition placeholder:text-slate-300'
const fieldNormal = 'border-slate-200 bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20'
const fieldError  = 'border-rose-300 bg-rose-50 focus:border-rose-400 focus:ring-2 focus:ring-rose-400/20'
</script>
