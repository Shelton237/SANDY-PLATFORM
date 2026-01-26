<template>
  <AppLayout>
    <SeoHead
      :title="product.name"
      :description="seoDescription"
      :keywords="seoKeywords"
      :image="seoImage"
      :structured-data="productStructuredData"
    />

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
                <img :src="activeImage" :alt="product.name" class="w-full h-full object-cover" />
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

    <section class="bg-[#fef7ee]/60 border-y border-orange-50">
      <div class="container mx-auto px-4 py-12 lg:py-16 space-y-10">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-orange-500">Avis clients</p>
            <h2 class="text-3xl font-semibold text-[#254a29] mt-2">Vos retours d'expérience</h2>
            <p class="text-slate-500 mt-2 max-w-2xl">
              Partagez comment ce jus vous accompagne au quotidien. Chaque avis aide la communauté à choisir le mix parfait.
            </p>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-10">
          <div class="space-y-6">
            <article class="p-6 rounded-3xl border border-slate-100 bg-white shadow-sm space-y-5">
              <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                  <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Note moyenne</p>
                  <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-4xl font-semibold text-[#254a29]">
                      {{ formattedAverageRating }}
                      <span class="text-base text-slate-400">/ {{ maxRating }}</span>
                    </p>
                  </div>
                  <p class="text-sm text-slate-500 mt-1">
                    {{ reviewStats.count }} {{ reviewStats.count > 1 ? 'avis partagés' : 'avis' }}
                  </p>
                </div>
                <div class="flex gap-1 text-2xl text-[#f49926]">
                  <i
                    v-for="(icon, index) in starIconsFor(reviewStats.average ?? 0)"
                    :key="`summary-${index}`"
                    :class="['bi', icon]"
                  ></i>
                </div>
              </div>

              <div class="space-y-3">
                <div
                  v-for="item in reviewDistribution"
                  :key="item.rating"
                  class="flex items-center gap-3"
                >
                  <span class="text-sm font-semibold text-[#254a29] w-10">{{ item.rating }} étoile{{ item.rating > 1 ? 's' : '' }}</span>
                  <div class="flex-1 h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div
                      class="h-2 rounded-full bg-[#f49926]"
                      :style="{ width: distributionWidth(item.total) }"
                    ></div>
                  </div>
                  <span class="text-xs text-slate-500 w-8 text-right">{{ item.total }}</span>
                </div>
              </div>
            </article>

            <div v-if="reviewList.length" class="space-y-4">
              <article
                v-for="review in reviewList"
                :key="review.id"
                class="p-6 rounded-3xl border border-slate-100 bg-white shadow-sm"
              >
                <div class="flex flex-wrap items-center justify-between gap-3">
                  <div>
                    <p class="text-base font-semibold text-[#254a29]">{{ review.author_name }}</p>
                    <p class="text-xs text-slate-400">{{ formatReviewDate(review.created_at) }}</p>
                  </div>
                  <div class="flex gap-1 text-[#f49926] text-lg">
                    <i
                      v-for="(icon, index) in starIconsFor(review.rating)"
                      :key="`review-${review.id}-${index}`"
                      :class="['bi', icon]"
                    ></i>
                  </div>
                </div>
                <p class="mt-4 text-sm text-slate-600 leading-relaxed">
                  {{ review.comment }}
                </p>
              </article>
            </div>
            <div v-else class="rounded-3xl border border-dashed border-slate-200 bg-white/70 p-6 text-sm text-slate-500">
              Aucun avis pour l'instant. Soyez le premier à partager votre expérience gustative !
            </div>
          </div>

          <article class="p-6 rounded-3xl border border-slate-100 bg-white shadow-lg">
            <h3 class="text-xl font-semibold text-[#254a29]">Laisser un avis</h3>
            <p class="text-sm text-slate-500 mt-1">Dites-nous comment vous consommez ce jus, votre moment préféré ou une astuce mixologie.</p>

            <div
              v-if="flash.success"
              class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
            >
              {{ flash.success }}
            </div>

            <form class="mt-5 space-y-4" @submit.prevent="submitReview">
              <div>
                <label class="text-xs uppercase text-slate-500">Votre note</label>
                <div class="flex items-center gap-2 mt-2">
                  <button
                    v-for="note in maxRating"
                    :key="note"
                    type="button"
                    class="text-2xl transition"
                    :class="note <= reviewForm.rating ? 'text-[#f49926]' : 'text-slate-300'"
                    @click="reviewForm.rating = note"
                  >
                    <i :class="['bi', note <= reviewForm.rating ? 'bi-star-fill' : 'bi-star']"></i>
                    <span class="sr-only">Choisir {{ note }} {{ note > 1 ? 'étoiles' : 'étoile' }}</span>
                  </button>
                </div>
                <p v-if="reviewForm.errors.rating" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.rating }}</p>
              </div>

              <div>
                <label class="text-xs uppercase text-slate-500">Commentaire</label>
                <textarea
                  v-model="reviewForm.comment"
                  rows="4"
                  :class="textareaClasses"
                  placeholder="Texture, goût, occasion de consommation..."
                ></textarea>
                <p v-if="reviewForm.errors.comment" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.comment }}</p>
              </div>

              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="text-xs uppercase text-slate-500">Prénom / Nom</label>
                  <input v-model="reviewForm.author_name" type="text" :class="inputClasses" placeholder="Ex : Mireille S." />
                  <p v-if="reviewForm.errors.author_name" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.author_name }}</p>
                </div>
                <div>
                  <label class="text-xs uppercase text-slate-500">Email (optionnel)</label>
                  <input v-model="reviewForm.author_email" type="email" :class="inputClasses" placeholder="Pour un suivi si besoin" />
                  <p v-if="reviewForm.errors.author_email" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.author_email }}</p>
                </div>
              </div>

              <button
                type="submit"
                class="w-full inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-3 font-semibold text-white hover:bg-[#f28700] transition disabled:opacity-50"
                :disabled="reviewForm.processing"
              >
                <i class="bi bi-chat-heart mr-2"></i>
                Envoyer mon expérience
              </button>
            </form>
          </article>
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
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import useCart from '@/Composables/useCart'
import SeoHead from '@/Components/Common/SeoHead.vue'

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
  },
  reviews: {
    type: Array,
    default: () => []
  },
  reviewSummary: {
    type: Object,
    default: () => ({
      count: 0,
      average: null,
      distribution: []
    })
  }
})

const { product, category, related, nutritionFocus } = props
const page = usePage()
const flash = computed(() => page.props.flash ?? {})
const siteName = computed(() => page.props.seo?.site_name || 'Sandy Juice')
const defaultSeoDescription = computed(() => page.props.seo?.description || `${siteName.value} - Jus naturels`)
const defaultSeoKeywords = computed(() => page.props.seo?.keywords || 'sandy juice,jus naturels')
const maxQuantity = computed(() => Number(page.props.shop?.max_quantity ?? 12))
const quantity = ref(1)
const { addToCart, loading: cartLoading } = useCart()

const gallery = computed(() => props.product.images ?? [])
const placeholderImage = '/images/catalog/placeholder.jpg'
const coverFallback = computed(() => props.product.image || placeholderImage)
const resolveAssetUrl = (value) => {
  if (!value) return null
  if (/^https?:\/\//i.test(value)) {
    return value
  }
  const baseUrl = page.props.seo?.base_url
  if (baseUrl) {
    const normalizedBase = baseUrl.replace(/\/$/, '')
    const normalizedPath = value.startsWith('/') ? value : `/${value}`
    return `${normalizedBase}${normalizedPath}`
  }
  return value
}
const seoImage = computed(() => resolveAssetUrl(gallery.value[0]?.url || coverFallback.value))
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

const seoDescription = computed(() => product.description || product.tagline || defaultSeoDescription.value)
const seoKeywords = computed(() => {
  const keywords = [
    product.name,
    category?.name,
    product.tagline,
    ...(product.ingredients ?? []).map((ingredient) => ingredient?.name)
  ]
    .concat(defaultSeoKeywords.value.split(','))
    .map((keyword) => (keyword || '').toString().trim())
    .filter(Boolean)

  return Array.from(new Set(keywords)).join(', ')
})

const productStructuredData = computed(() => {
  const data = {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: product.name,
    description: seoDescription.value,
    sku: product.slug,
    brand: siteName.value
  }

  if (seoImage.value) {
    data.image = [seoImage.value]
  }

  if (typeof product.price === 'number' && !Number.isNaN(product.price)) {
    data.offers = {
      '@type': 'Offer',
      priceCurrency: page.props.shop?.currency ?? 'FCFA',
      price: product.price,
      availability: (product.stock ?? 0) > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
    }
  }

  return data
})

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

const maxRating = 5
const reviewForm = useForm({
  author_name: '',
  author_email: '',
  rating: maxRating,
  comment: ''
})

const reviewList = computed(() => props.reviews ?? [])
const reviewStats = computed(() => {
  const distribution = props.reviewSummary?.distribution ?? []

  return {
    count: props.reviewSummary?.count ?? 0,
    average: typeof props.reviewSummary?.average === 'number' ? props.reviewSummary.average : null,
    distribution: distribution.length
      ? distribution
      : Array.from({ length: maxRating }, (_, index) => ({
          rating: maxRating - index,
          total: 0
        }))
  }
})

const reviewDistribution = computed(() =>
  [...reviewStats.value.distribution].sort((a, b) => b.rating - a.rating)
)

const formattedAverageRating = computed(() => {
  if (reviewStats.value.average === null || Number.isNaN(reviewStats.value.average)) {
    return '—'
  }

  return Number(reviewStats.value.average).toFixed(1)
})

const starIconsFor = (value) => {
  const rating = Number(value ?? 0)

  return Array.from({ length: maxRating }, (_, index) => {
    const score = index + 1
    if (rating >= score) {
      return 'bi-star-fill'
    }
    if (rating >= score - 0.5) {
      return 'bi-star-half'
    }
    return 'bi-star'
  })
}

const distributionWidth = (total) => {
  if (!reviewStats.value.count || !total) {
    return '0%'
  }

  const percent = Math.round((total / reviewStats.value.count) * 100)
  return `${percent}%`
}

const formatReviewDate = (value) => {
  if (!value) {
    return ''
  }

  const date = new Date(value)

  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const inputClasses =
  'w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-[#254a29] bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20 placeholder:text-slate-400'
const textareaClasses = `${inputClasses} resize-none`

const submitReview = () => {
  reviewForm.post(route('products.reviews.store', product.slug), {
    preserveScroll: true,
    onSuccess: () => {
      reviewForm.reset('comment')
      reviewForm.rating = maxRating
    }
  })
}
</script>
