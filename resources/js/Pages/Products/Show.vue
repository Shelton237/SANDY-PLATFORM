<template>
  <AppLayout>
    <Head :title="`${product.name} - Sandy Juice`" />

    <section class="bg-gradient-to-br from-[#fef7ee] via-white to-[#f2faf0] border-b border-orange-100">
      <div class="container mx-auto px-4 py-12 lg:py-16 space-y-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-orange-500">
              Fiche jus
            </p>
            <h1 class="text-4xl font-semibold text-[#254a29] mt-3">
              {{ product.name }}
            </h1>
            <p class="text-slate-500 mt-2 max-w-2xl">
              {{ product.description }}
            </p>
          </div>
          <Link :href="route('products')" class="inline-flex items-center text-sm font-semibold text-[#f49926] hover:text-[#f28700]">
            <i class="bi bi-arrow-left mr-2"></i>
            Retour au catalogue
          </Link>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
          <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm lg:col-span-2">
            <div class="mb-6">
              <div class="rounded-3xl border border-slate-100 overflow-hidden bg-slate-50">
                <img :src="activeImage" :alt="product.name" class="w-full h-80 object-cover" />
              </div>
              <div class="mt-4 flex flex-wrap gap-3">
                <button
                  v-for="image in gallery"
                  :key="image.id || image.url"
                  type="button"
                  class="rounded-2xl border border-slate-200 p-1 hover:border-[#f49926]"
                  :class="activeImage === image.url ? 'border-[#f49926]' : ''"
                  @click="setActiveImage(image.url)"
                >
                  <img :src="image.url" :alt="image.alt || product.name" class="w-20 h-20 object-cover rounded-xl" />
                </button>
              </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-4">
              <span class="text-xs font-semibold px-3 py-1 rounded-full" :class="accentClass(product.accent, 'badge')">
                {{ category?.name || 'Collection' }}
              </span>
              <span class="text-sm text-slate-500">{{ product.availability }}</span>
            </div>
            <h2 class="text-2xl font-semibold text-[#254a29] mt-4">{{ product.tagline }}</h2>

            <div class="mt-6 grid sm:grid-cols-3 gap-4">
              <div class="p-4 rounded-2xl border border-slate-100 bg-[#fef4e7]">
                <p class="text-xs uppercase text-[#f49926]">Prix</p>
                <p class="text-2xl font-semibold text-[#f49926]">{{ formatPrice(product.price) }}</p>
                <p class="text-xs text-slate-500">{{ product.size }}</p>
              </div>
              <div class="p-4 rounded-2xl border border-slate-100">
                <p class="text-xs uppercase text-slate-400">Calories</p>
                <p class="text-2xl font-semibold text-[#254a29]">{{ product.calories }} kcal</p>
                <p class="text-xs text-slate-500">Sucres {{ product.sugars }} g</p>
              </div>
              <div class="p-4 rounded-2xl border border-slate-100">
                <p class="text-xs uppercase text-slate-400">Moments</p>
                <div class="mt-2 flex flex-wrap gap-2">
                  <span v-for="moment in product.moments" :key="moment" class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 capitalize">
                    {{ moment }}
                  </span>
                </div>
              </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-4">
              <div class="flex items-center rounded-2xl border border-slate-200">
                <button
                  type="button"
                  class="px-4 py-2 text-lg text-slate-500 hover:text-[#254a29]"
                  @click="adjustQuantity(-1)"
                  :disabled="quantity <= 1 || cartLoading"
                >
                  <i class="bi bi-dash-lg"></i>
                </button>
                <input
                  v-model.number="quantity"
                  type="number"
                  min="1"
                  :max="maxQuantity"
                  class="w-16 text-center border-0 font-semibold text-[#254a29] focus:ring-0"
                  @change="normalizeQuantity"
                />
                <button
                  type="button"
                  class="px-4 py-2 text-lg text-slate-500 hover:text-[#254a29]"
                  @click="adjustQuantity(1)"
                  :disabled="quantity >= maxQuantity || cartLoading"
                >
                  <i class="bi bi-plus-lg"></i>
                </button>
              </div>
              <button
                type="button"
                class="inline-flex items-center gap-2 rounded-2xl bg-[#f49926] px-5 py-3 font-semibold text-white hover:bg-[#f28700] transition disabled:opacity-50"
                :disabled="cartLoading"
                @click="addProductToCart"
              >
                <i class="bi bi-cart-plus"></i>
                Ajouter au panier
              </button>
              <p class="text-xs text-slate-500">
                Paiement en FCFA à la livraison
              </p>
            </div>

            <div class="mt-8 grid md:grid-cols-2 gap-6">
              <div>
                <h3 class="text-sm uppercase tracking-wide text-slate-500">Ingredients</h3>
                <ul class="mt-3 space-y-3">
                  <li v-for="ingredient in product.ingredients" :key="ingredient.name" class="flex items-center justify-between text-sm text-[#254a29]">
                    <span class="font-medium">{{ ingredient.name }}</span>
                    <span class="text-slate-400">{{ ingredient.percentage }}%</span>
                  </li>
                </ul>
              </div>
              <div>
                <h3 class="text-sm uppercase tracking-wide text-slate-500">Bienfaits</h3>
                <ul class="mt-3 space-y-3">
                  <li v-for="benefit in product.benefits" :key="benefit" class="flex items-start gap-3 text-sm text-slate-600">
                    <i class="bi bi-sparkles text-[#f49926] mt-1"></i>
                    <span>{{ benefit }}</span>
                  </li>
                </ul>
              </div>
            </div>

            <div class="mt-8 border-t border-dashed border-slate-200 pt-6">
              <h3 class="text-sm uppercase tracking-wide text-slate-500">Vitamines</h3>
              <div class="mt-3 grid sm:grid-cols-3 gap-3">
                <div v-for="vitamin in product.vitamins" :key="vitamin.label" class="p-4 rounded-2xl border border-slate-100 bg-slate-50">
                  <p class="text-xs uppercase text-slate-400">{{ vitamin.label }}</p>
                  <p class="text-lg font-semibold text-[#254a29]">{{ vitamin.value }}</p>
                </div>
              </div>
            </div>
          </article>

          <aside class="space-y-5">
            <article class="p-6 rounded-3xl border border-slate-100 bg-white shadow-sm">
              <h3 class="text-sm uppercase tracking-wide text-slate-500">Infos express</h3>
              <dl class="mt-4 space-y-3 text-sm text-slate-600">
                <div class="flex justify-between">
                  <dt>Badge</dt>
                  <dd class="font-semibold text-[#254a29]">{{ product.badge }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt>Disponibilite</dt>
                  <dd class="font-semibold text-[#254a29]">{{ product.season }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt>Edition limitee</dt>
                  <dd class="font-semibold text-[#254a29]">
                    {{ product.is_limited ? 'Oui' : 'Non' }}
                  </dd>
                </div>
              </dl>
            </article>
            <article class="p-6 rounded-3xl border border-slate-100 bg-white shadow-sm">
              <h3 class="text-sm uppercase tracking-wide text-slate-500">Process Sandy</h3>
              <ul class="mt-4 space-y-3 text-sm text-slate-600">
                <li v-for="focus in nutritionFocus" :key="focus.title" class="border-b border-slate-100 pb-3 last:pb-0 last:border-none">
                  <p class="text-xs uppercase text-slate-400">{{ focus.title }}</p>
                  <p class="text-base font-semibold text-[#254a29]">{{ focus.value }}</p>
                  <p class="text-xs text-slate-500">{{ focus.description }}</p>
                </li>
              </ul>
            </article>
          </aside>
        </div>
      </div>
    </section>

    <section v-if="related.length" class="container mx-auto px-4 py-12 lg:py-16">
      <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-orange-500">Suggestions</p>
          <h2 class="text-3xl font-semibold text-[#254a29]">Autres jus de la collection</h2>
        </div>
        <Link :href="route('products')" class="text-sm font-semibold text-[#f49926] hover:text-[#f28700]">
          Voir tout le catalogue
        </Link>
      </div>

      <div class="grid gap-6 md:grid-cols-3">
        <article v-for="other in related" :key="other.slug" class="p-6 rounded-3xl border border-slate-100 bg-white shadow-sm">
          <p class="text-xs uppercase text-slate-400">{{ category?.name }}</p>
          <h3 class="mt-2 text-xl font-semibold text-[#254a29]">{{ other.name }}</h3>
          <p class="text-sm text-slate-500 mt-1">{{ other.tagline }}</p>
          <div class="mt-3 rounded-2xl overflow-hidden border border-slate-100">
            <img :src="coverImage(other)" :alt="other.name" class="w-full h-40 object-cover" />
          </div>
          <div class="mt-4 flex items-center justify-between">
            <p class="text-lg font-semibold text-[#f49926]">{{ formatPrice(other.price) }}</p>
            <Link :href="route('products.show', other.slug)" class="inline-flex items-center text-sm font-semibold text-[#254a29] hover:text-[#f49926]">
              Details
              <i class="bi bi-arrow-up-right ml-1"></i>
            </Link>
          </div>
        </article>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import useCart from '@/Composables/useCart'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  category: {
    type: Object,
    default: null
  },
  related: {
    type: Array,
    default: () => []
  },
  nutritionFocus: {
    type: Array,
    default: () => []
  },
  moments: {
    type: Array,
    default: () => []
  }
})

const { product, category, related, nutritionFocus } = props
const page = usePage()
const maxQuantity = computed(() => Number(page.props.shop?.max_quantity ?? 12))
const quantity = ref(1)
const { addToCart, loading: cartLoading } = useCart()

const gallery = computed(() => props.product.images ?? [])
const placeholderImage = '/images/catalog/placeholder.jpg'
const coverFallback = computed(() => props.product.image || placeholderImage)
const activeImage = ref(gallery.value[0]?.url || coverFallback.value)

watch([gallery, () => props.product.image], ([images]) => {
  activeImage.value = images?.[0]?.url || coverFallback.value
})

const setActiveImage = (url) => {
  activeImage.value = url || coverFallback.value
}

const accentVariants = {
  emerald: { badge: 'bg-emerald-50 text-emerald-700' },
  orange: { badge: 'bg-orange-50 text-orange-700' },
  rose: { badge: 'bg-rose-50 text-rose-700' },
  amber: { badge: 'bg-amber-50 text-amber-700' },
  indigo: { badge: 'bg-indigo-50 text-indigo-700' },
  default: { badge: 'bg-slate-100 text-slate-700' }
}

const accentClass = (accent, type) => {
  const variant = accentVariants[accent] ?? accentVariants.default
  return variant[type] ?? accentVariants.default[type]
}

const coverImage = (item) => {
  if (!item) return placeholderImage
  return item.images?.[0]?.url || item.image || placeholderImage
}

const formatPrice = (value) => {
  if (typeof value !== 'number') {
    return 'N/A'
  }

  return `${value.toFixed(0)} FCFA`
}

const adjustQuantity = (delta) => {
  let next = quantity.value + delta
  if (next < 1) next = 1
  if (next > maxQuantity.value) next = maxQuantity.value
  quantity.value = next
}

const normalizeQuantity = () => {
  if (!quantity.value || quantity.value < 1) {
    quantity.value = 1
  }
  if (quantity.value > maxQuantity.value) {
    quantity.value = maxQuantity.value
  }
}

const addProductToCart = () => {
  addToCart(product.id, quantity.value)
}
</script>
