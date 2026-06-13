<template>
  <AppLayout>
    <Head title="Mon panier" />

    <div class="container mx-auto px-4 py-6 lg:py-10 max-w-5xl">

      <!-- En-tête -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-2xl font-bold text-[#254a29]">Mon panier</h1>
          <p class="text-sm text-slate-400 mt-0.5">{{ cartCountLabel }}</p>
        </div>
        <Link :href="route('products')" class="text-sm text-[#f49926] hover:text-[#f28700] font-semibold flex items-center gap-1.5 transition">
          <i class="bi bi-plus-circle"></i>
          Ajouter des produits
        </Link>
      </div>

      <!-- Panier non vide -->
      <div v-if="items.length" class="grid lg:grid-cols-3 gap-6 items-start">

        <!-- Liste des articles -->
        <div class="lg:col-span-2 space-y-3">
          <article
            v-for="item in items"
            :key="item.product_id"
            class="flex gap-4 bg-white rounded-2xl border border-slate-100 shadow-sm p-4 transition hover:border-slate-200"
          >
            <!-- Image -->
            <Link :href="route('products.show', item.slug)" class="shrink-0 w-20 h-20 rounded-xl overflow-hidden bg-[#fef7ee] border border-orange-100">
              <img
                :src="item.image || placeholder"
                :alt="item.name"
                class="w-full h-full object-cover"
              />
            </Link>

            <!-- Infos -->
            <div class="flex-1 min-w-0 flex flex-col justify-between gap-2">
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <Link :href="route('products.show', item.slug)" class="font-semibold text-[#254a29] hover:text-[#f49926] transition text-sm leading-tight truncate block">
                    {{ item.name }}
                  </Link>
                  <p v-if="item.size" class="text-xs text-slate-400 mt-0.5">{{ item.size }}</p>
                </div>
                <span class="font-bold text-[#E85A14] shrink-0 text-sm">{{ formatPrice(item.price) }}</span>
              </div>

              <div class="flex items-center justify-between gap-3">
                <!-- Quantité -->
                <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50">
                  <button
                    type="button"
                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-[#254a29] transition disabled:opacity-30"
                    :disabled="quantities[item.product_id] <= 1 || loading"
                    @click="changeQuantity(item, -1)"
                  >
                    <i class="bi bi-dash text-sm"></i>
                  </button>
                  <span class="w-8 text-center font-semibold text-[#254a29] text-sm select-none">
                    {{ quantities[item.product_id] ?? item.quantity }}
                  </span>
                  <button
                    type="button"
                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-[#254a29] transition disabled:opacity-30"
                    :disabled="quantities[item.product_id] >= maxQuantity || loading"
                    @click="changeQuantity(item, 1)"
                  >
                    <i class="bi bi-plus text-sm"></i>
                  </button>
                </div>

                <div class="flex items-center gap-3">
                  <span class="text-sm font-semibold text-slate-600">
                    {{ formatPrice(item.total) }}
                  </span>
                  <button
                    type="button"
                    class="text-slate-300 hover:text-rose-400 transition"
                    :disabled="loading"
                    @click="removeItem(item.product_id)"
                    title="Retirer"
                  >
                    <i class="bi bi-trash text-sm"></i>
                  </button>
                </div>
              </div>
            </div>
          </article>

          <!-- Vider le panier -->
          <div class="flex justify-end pt-1">
            <button
              type="button"
              class="text-xs text-slate-400 hover:text-rose-500 transition flex items-center gap-1.5"
              @click="clearCart()"
            >
              <i class="bi bi-trash"></i>
              Vider le panier
            </button>
          </div>
        </div>

        <!-- Résumé commande -->
        <aside class="lg:col-span-1 space-y-4 lg:sticky lg:top-24">
          <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
            <h2 class="font-bold text-[#254a29]">Résumé</h2>

            <div class="space-y-2 text-sm">
              <div class="flex justify-between text-slate-500">
                <span>Sous-total ({{ cart.count }} article{{ cart.count > 1 ? 's' : '' }})</span>
                <span class="font-medium text-slate-700">{{ formatPrice(cart.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-slate-500">
                <span>Livraison</span>
                <span v-if="cart.delivery_fee === 0" class="font-medium text-emerald-600">Offerte</span>
                <span v-else class="font-medium text-slate-700">{{ formatPrice(cart.delivery_fee) }}</span>
              </div>
            </div>

            <!-- Seuil livraison gratuite -->
            <div v-if="cart.free_delivery_threshold > 0 && cart.delivery_fee > 0" class="rounded-xl bg-[#f2faf0] border border-[#254a29]/10 px-3 py-2.5 text-xs text-[#254a29]">
              <i class="bi bi-gift text-[#f49926] mr-1"></i>
              Livraison offerte dès {{ formatPrice(cart.free_delivery_threshold) }} d'achat
              <span class="block mt-0.5 text-slate-400">Il vous manque {{ formatPrice(cart.free_delivery_threshold - cart.subtotal) }}</span>
            </div>

            <div class="border-t border-dashed border-slate-200 pt-3 flex items-center justify-between">
              <span class="font-semibold text-[#254a29]">Total</span>
              <span class="text-2xl font-bold text-[#E85A14]">{{ formatPrice(cart.total) }}</span>
            </div>

            <button
              type="button"
              class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#E85A14] hover:bg-[#d45012] text-white font-bold py-3.5 transition disabled:opacity-50 text-base"
              :disabled="loading"
              @click="gotoCheckout()"
            >
              <i class="bi bi-bag-check"></i>
              Passer commande
            </button>

            <p class="text-center text-xs text-slate-400 flex items-center justify-center gap-1.5">
              <i class="bi bi-shield-check text-emerald-500"></i>
              Paiement à la livraison uniquement
            </p>
          </div>

          <!-- Réassurance -->
          <div class="bg-[#254a29] rounded-2xl p-5 text-white space-y-2">
            <div class="flex items-center gap-2 text-sm font-semibold">
              <i class="bi bi-lightning-charge text-[#f49926]"></i>
              Livraison express
            </div>
            <p class="text-xs text-white/70 leading-relaxed">
              Jus pressés le matin, livrés en moins de 2h à Yaoundé. Commandez avant 15h.
            </p>
          </div>
        </aside>
      </div>

      <!-- Panier vide -->
      <div v-else class="py-20 text-center">
        <div class="w-20 h-20 mx-auto rounded-full bg-[#fef7ee] flex items-center justify-center text-3xl text-[#f49926] mb-5">
          <i class="bi bi-bag"></i>
        </div>
        <h2 class="text-xl font-bold text-[#254a29]">Votre panier est vide</h2>
        <p class="text-slate-400 text-sm mt-2 mb-6">Ajoutez vos jus préférés depuis le catalogue.</p>
        <Link
          :href="route('products')"
          class="inline-flex items-center gap-2 bg-[#E85A14] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#d45012] transition"
        >
          <i class="bi bi-grid"></i>
          Voir le catalogue
        </Link>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed, reactive, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import useCart from '@/Composables/useCart'

const props = defineProps({
  cart: {
    type: Object,
    default: () => ({ items: [], subtotal: 0, delivery_fee: 0, total: 0, count: 0, currency: 'FCFA', free_delivery_threshold: 0 }),
  },
})

const page        = usePage()
const shop        = computed(() => page.props.shop || {})
const currency    = computed(() => props.cart?.currency || shop.value.currency || 'FCFA')
const maxQuantity = computed(() => Number(shop.value.max_quantity || 12))
const placeholder = '/images/catalog/placeholder.jpg'

const { updateItem, removeItem, clearCart, gotoCheckout, loading } = useCart()
const items = computed(() => props.cart.items ?? [])
const cart  = computed(() => props.cart ?? {})

const quantities = reactive({})
watch(items, (value) => {
  value.forEach(item => { quantities[item.product_id] = item.quantity })
}, { immediate: true })

const changeQuantity = (item, delta) => {
  const current = quantities[item.product_id] ?? item.quantity
  const next = Math.min(Math.max(current + delta, 1), maxQuantity.value)
  quantities[item.product_id] = next
  updateItem(item.product_id, next)
}

const cartCountLabel = computed(() => {
  const n = Number(cart.value.count ?? items.value.length ?? 0)
  if (!n) return 'Aucun article'
  return `${n} article${n > 1 ? 's' : ''}`
})

const formatPrice = (amount) => `${(Number(amount) || 0).toFixed(0)} ${currency.value}`
</script>
