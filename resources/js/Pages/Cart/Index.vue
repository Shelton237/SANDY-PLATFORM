<template>
  <AppLayout>
    <Head title="Mon panier" />

    <section class="bg-gradient-to-br from-[#fef7ee] via-white to-[#f2faf0] border-b border-orange-100">
      <div class="container mx-auto px-4 py-10 lg:py-16 space-y-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-[#f49926]">
              Panier
            </p>
            <h1 class="text-3xl lg:text-4xl font-semibold text-[#254a29] mt-2">
              Vos jus sélectionnés
            </h1>
            <p class="text-slate-500 mt-2">
              Retrouvez ici les recettes ajoutées depuis le catalogue Sandy Juice.
            </p>
            <p class="text-sm text-slate-500 mt-1">
              {{ cartCountLabel }}
            </p>
          </div>
          <div class="flex items-center gap-3 text-sm">
            <Link :href="route('products')" class="inline-flex items-center text-[#f49926] font-semibold hover:text-[#f28700]">
              <i class="bi bi-plus-circle mr-2"></i>
              Ajouter d'autres produits
            </Link>
            <button
              v-if="items.length"
              type="button"
              class="inline-flex items-center gap-2 text-slate-500 hover:text-rose-500"
              @click="clearCart()"
            >
              <i class="bi bi-trash"></i>
              Vider le panier
            </button>
          </div>
        </div>

        <div v-if="items.length" class="grid lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2 space-y-4">
            <article
              v-for="item in items"
              :key="item.product_id"
              class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm flex flex-col sm:flex-row gap-4"
            >
              <div class="w-full sm:w-32 shrink-0">
                <div class="rounded-2xl border border-slate-100 overflow-hidden bg-slate-50 aspect-square flex items-center justify-center">
                  <img
                    :src="item.image || placeholderImage"
                    :alt="item.name"
                    class="object-cover w-full h-full"
                  />
                </div>
              </div>
              <div class="flex-1 space-y-3">
                <div class="flex flex-wrap justify-between gap-3">
                  <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ item.size || 'Format standard' }}</p>
                    <h2 class="text-lg font-semibold text-[#254a29]">{{ item.name }}</h2>
                  </div>
                  <p class="text-lg font-semibold text-[#f49926]">{{ formatPrice(item.price) }}</p>
                </div>
                <div class="flex flex-wrap gap-3 items-center">
                  <div class="flex items-center border border-slate-200 rounded-2xl px-3 py-2">
                    <button
                      type="button"
                      class="text-lg text-slate-500 hover:text-[#254a29]"
                      @click="changeQuantity(item, -1)"
                      :disabled="quantities[item.product_id] <= 1 || loading"
                    >
                      <i class="bi bi-dash-lg"></i>
                    </button>
                    <input
                      v-model.number="quantities[item.product_id]"
                      type="number"
                      min="1"
                      :max="maxQuantity"
                      class="w-16 text-center border-0 focus:ring-0 font-semibold text-[#254a29]"
                      @change="manualUpdate(item)"
                    />
                    <button
                      type="button"
                      class="text-lg text-slate-500 hover:text-[#254a29]"
                      @click="changeQuantity(item, 1)"
                      :disabled="quantities[item.product_id] >= maxQuantity || loading"
                    >
                      <i class="bi bi-plus-lg"></i>
                    </button>
                  </div>
                  <p class="text-sm text-slate-500">
                    Total : <span class="font-semibold text-[#254a29]">{{ formatPrice(item.total) }}</span>
                  </p>
                </div>
                <div class="flex flex-wrap justify-between gap-3 text-sm text-slate-500">
                  <p class="flex items-center gap-2">
                    <i class="bi bi-lightning-charge text-[#f49926]"></i>
                    Livraison sous 2h à Libreville
                  </p>
                  <button
                    type="button"
                    class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-600"
                    @click="removeItem(item.product_id)"
                  >
                    <i class="bi bi-x-circle"></i>
                    Retirer
                  </button>
                </div>
              </div>
            </article>
          </div>
          <aside class="space-y-4">
            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
              <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400">Résumé</h3>
              <ul class="mt-4 space-y-3 text-sm text-slate-600">
                <li class="flex justify-between">
                  <span>Sous-total</span>
                  <span class="font-semibold text-[#254a29]">{{ formatPrice(cart.subtotal) }}</span>
                </li>
                <li class="flex justify-between">
                  <span>Livraison estimée</span>
                  <span>
                    <template v-if="cart.delivery_fee === 0">
                      <span class="font-semibold text-emerald-600">Offerte</span>
                    </template>
                    <template v-else>
                      <span class="font-semibold text-[#254a29]">{{ formatPrice(cart.delivery_fee) }}</span>
                    </template>
                  </span>
                </li>
              </ul>
              <div class="mt-4 border-t border-dashed border-slate-200 pt-4">
                <div class="flex justify-between text-base">
                  <span class="font-semibold text-[#254a29]">Total TTC</span>
                  <span class="text-2xl font-bold text-[#f49926]">{{ formatPrice(cart.total) }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-2">
                  Paiement à la livraison (espèces ou Mobile Money)
                </p>
              </div>
              <button
                type="button"
                class="mt-6 w-full inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-3 text-white font-semibold hover:bg-[#f28700] transition disabled:opacity-50"
                :disabled="!items.length || loading"
                @click="gotoCheckout()"
              >
                <i class="bi bi-bag-check mr-2"></i>
                Passer au checkout
              </button>
              <p v-if="cart.free_delivery_threshold" class="text-xs text-slate-500 mt-3">
                Livraison offerte dès {{ formatPrice(cart.free_delivery_threshold) }} d’achat.
              </p>
            </article>
            <article class="rounded-3xl border border-slate-100 bg-slate-900 text-white p-6 space-y-3">
              <p class="text-xs uppercase tracking-[0.3em] text-orange-200">Engagement</p>
              <h3 class="text-xl font-semibold">Production du matin</h3>
              <p class="text-sm text-white/70">
                Nos jus sont pressés chaque jour. Commandez avant 15h pour une livraison dans la journée.
              </p>
            </article>
          </aside>
        </div>

        <div v-else class="text-center py-16 border border-dashed border-slate-200 rounded-3xl bg-white">
          <div class="w-20 h-20 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-3xl text-slate-400">
            <i class="bi bi-bag"></i>
          </div>
          <h2 class="text-2xl font-semibold text-[#254a29] mt-4">Panier vide</h2>
          <p class="text-slate-500 mt-2">Ajoutez vos jus préférés depuis le catalogue pour commencer votre commande.</p>
          <Link
            :href="route('products')"
            class="mt-6 inline-flex items-center gap-2 bg-[#f49926] text-white px-6 py-3 rounded-2xl font-semibold hover:bg-[#f28700]"
          >
            Découvrir le catalogue
            <i class="bi bi-arrow-right"></i>
          </Link>
        </div>
      </div>
    </section>
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
    default: () => ({
      items: [],
      subtotal: 0,
      delivery_fee: 0,
      total: 0,
      currency: 'FCFA',
    }),
  },
})

const page = usePage()
const shop = computed(() => page.props.shop || {})
const currency = computed(() => shop.value.currency || 'FCFA')
const maxQuantity = computed(() => Number(shop.value.max_quantity || 12))

const { updateItem, removeItem, clearCart, gotoCheckout, loading } = useCart()
const items = computed(() => props.cart.items ?? [])
const cart = computed(() => props.cart ?? {})
const quantities = reactive({})
const placeholderImage = '/images/catalog/placeholder.jpg'

watch(
  items,
  (value) => {
    value.forEach((item) => {
      quantities[item.product_id] = item.quantity
    })
  },
  { immediate: true },
)

const changeQuantity = (item, delta) => {
  const current = quantities[item.product_id] ?? item.quantity
  let next = current + delta
  if (next < 1) next = 1
  if (next > maxQuantity.value) next = maxQuantity.value
  quantities[item.product_id] = next
  updateItem(item.product_id, next)
}

const manualUpdate = (item) => {
  let value = Number(quantities[item.product_id])
  if (!value || value < 1) value = 1
  if (value > maxQuantity.value) value = maxQuantity.value
  quantities[item.product_id] = value
  updateItem(item.product_id, value)
}

const cartCountLabel = computed(() => {
  const count = Number(cart.value.count ?? items.value.length ?? 0)
  if (count <= 0) {
    return 'Aucun produit pour le moment'
  }
  return `${count} ${count > 1 ? 'produits' : 'produit'} dans votre panier`
})

const formatPrice = (amount) => {
  const number = Number(amount) || 0
  return `${number.toFixed(0)} ${currency.value}`
}
</script>
