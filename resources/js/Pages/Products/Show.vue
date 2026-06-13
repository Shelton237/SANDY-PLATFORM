<template>
  <AppLayout>
    <SeoHead
      :title="product.name"
      :description="seoDescription"
      :keywords="seoKeywords"
      :image="seoImage"
      :structured-data="productStructuredData"
    />

    <!-- Hero produit -->
    <div class="container mx-auto px-4 py-6 lg:py-10">

      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
        <Link :href="route('products')" class="hover:text-[#f49926] transition">Catalogue</Link>
        <i class="bi bi-chevron-right text-xs"></i>
        <span class="text-[#254a29] font-medium">{{ product.name }}</span>
      </div>

      <!-- Split hero -->
      <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 items-start">

        <!-- Galerie image -->
        <div class="lg:sticky lg:top-24">
          <div class="rounded-3xl overflow-hidden bg-[#fef7ee] border border-orange-100 aspect-square">
            <img
              :src="activeImage"
              :alt="product.name"
              class="w-full h-full object-cover transition-opacity duration-200"
            />
          </div>
          <div v-if="gallery.length > 1" class="mt-3 flex gap-3 overflow-x-auto pb-1">
            <button
              v-for="image in gallery"
              :key="image.id || image.url"
              type="button"
              class="shrink-0 w-18 h-18 rounded-2xl border-2 overflow-hidden transition"
              :class="activeImage === image.url ? 'border-[#f49926]' : 'border-slate-200 hover:border-[#f49926]/50'"
              @click="setActiveImage(image.url)"
            >
              <img :src="image.url" :alt="image.alt || product.name" class="w-full h-full object-cover" />
            </button>
          </div>
        </div>

        <!-- Infos produit -->
        <div class="space-y-6">

          <!-- Badges -->
          <div class="flex flex-wrap items-center gap-2">
            <span v-if="category" class="text-xs font-semibold px-3 py-1 rounded-full bg-[#254a29]/10 text-[#254a29]">
              {{ category.name }}
            </span>
            <span v-if="product.is_new" class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">
              Nouveauté
            </span>
            <span v-if="product.is_limited" class="text-xs font-semibold px-3 py-1 rounded-full bg-rose-100 text-rose-700">
              Édition limitée
            </span>
            <span v-if="product.badge" class="text-xs font-semibold px-3 py-1 rounded-full bg-amber-100 text-amber-700">
              {{ product.badge }}
            </span>
          </div>

          <!-- Nom + tagline -->
          <div>
            <h1 class="text-3xl lg:text-4xl font-bold text-[#254a29] leading-tight">{{ product.name }}</h1>
            <p v-if="product.tagline" class="text-slate-500 mt-2 text-base leading-relaxed">{{ product.tagline }}</p>
          </div>

          <!-- Prix -->
          <div class="flex items-baseline gap-3">
            <span class="text-4xl font-bold text-[#E85A14]">{{ formatPrice(product.price) }}</span>
            <span v-if="product.size" class="text-sm text-slate-400 border border-slate-200 px-2 py-0.5 rounded-full">{{ product.size }}</span>
          </div>

          <!-- Stats rapides -->
          <div v-if="product.calories || product.energy_index || hasAvailability" class="flex flex-wrap gap-3">
            <div v-if="product.calories" class="flex items-center gap-1.5 text-sm text-slate-600 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-full">
              <i class="bi bi-fire text-orange-400"></i>
              {{ product.calories }} kcal
            </div>
            <div v-if="product.energy_index" class="flex items-center gap-1.5 text-sm text-slate-600 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-full">
              <i class="bi bi-lightning-charge text-[#f49926]"></i>
              Énergie {{ product.energy_index }}/10
            </div>
            <div v-if="hasAvailability" class="flex items-center gap-1.5 text-sm text-slate-600 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-full">
              <i class="bi bi-clock text-slate-400"></i>
              {{ product.availability }}
            </div>
          </div>

          <!-- Moments -->
          <div v-if="moments.length">
            <p class="text-xs uppercase tracking-widest text-slate-400 mb-2">Idéal pour</p>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="moment in moments"
                :key="moment"
                class="text-xs px-3 py-1.5 rounded-full bg-[#f2faf0] text-[#254a29] border border-[#254a29]/10 capitalize"
              >
                {{ moment }}
              </span>
            </div>
          </div>

          <!-- CTA -->
          <div class="pt-2 space-y-3">
            <div class="flex items-center gap-3">
              <!-- Quantité -->
              <div class="flex items-center rounded-2xl border border-slate-200 bg-white">
                <button
                  type="button"
                  class="w-11 h-11 flex items-center justify-center text-slate-400 hover:text-[#254a29] transition disabled:opacity-30"
                  :disabled="quantity <= 1 || cartLoading"
                  @click="adjustQuantity(-1)"
                >
                  <i class="bi bi-dash-lg"></i>
                </button>
                <span class="w-10 text-center font-bold text-[#254a29] text-lg select-none">{{ quantity }}</span>
                <button
                  type="button"
                  class="w-11 h-11 flex items-center justify-center text-slate-400 hover:text-[#254a29] transition disabled:opacity-30"
                  :disabled="quantity >= maxQuantity || cartLoading"
                  @click="adjustQuantity(1)"
                >
                  <i class="bi bi-plus-lg"></i>
                </button>
              </div>

              <!-- Bouton panier -->
              <button
                type="button"
                class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-[#E85A14] hover:bg-[#d45012] text-white font-bold py-3 px-5 transition disabled:opacity-50 text-base"
                :disabled="cartLoading || outOfStock"
                @click="addProductToCart"
              >
                <i v-if="cartLoading" class="bi bi-arrow-repeat animate-spin"></i>
                <i v-else class="bi bi-cart-plus"></i>
                {{ outOfStock ? 'Rupture de stock' : 'Ajouter au panier' }}
              </button>
            </div>

            <!-- Réassurance -->
            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
              <span class="flex items-center gap-1.5">
                <i class="bi bi-check-circle-fill text-emerald-500"></i>
                Paiement à la livraison
              </span>
              <span v-if="!outOfStock" class="flex items-center gap-1.5">
                <i class="bi bi-check-circle-fill text-emerald-500"></i>
                En stock
              </span>
            </div>
          </div>

          <!-- Description -->
          <div v-if="product.description" class="pt-2 border-t border-slate-100">
            <p class="text-sm text-slate-600 leading-relaxed">{{ product.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Détails produit : ingrédients + bienfaits -->
    <div v-if="hasIngredients || hasBenefits" class="bg-[#fef7ee]/50 border-y border-orange-100 mt-8">
      <div class="container mx-auto px-4 py-10">
        <div class="grid gap-8 md:grid-cols-2">

          <!-- Ingrédients -->
          <div v-if="hasIngredients">
            <h2 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-4">Ingrédients</h2>
            <ul class="space-y-2">
              <li
                v-for="item in product.ingredients"
                :key="item"
                class="flex items-center gap-3 text-sm text-[#254a29]"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-[#f49926] shrink-0"></span>
                {{ item }}
              </li>
            </ul>
          </div>

          <!-- Bienfaits -->
          <div v-if="hasBenefits">
            <h2 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-4">Bienfaits</h2>
            <ul class="space-y-2">
              <li
                v-for="benefit in product.benefits"
                :key="benefit"
                class="flex items-start gap-3 text-sm text-slate-600"
              >
                <i class="bi bi-sparkles text-[#f49926] mt-0.5 shrink-0"></i>
                {{ benefit }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Avis clients -->
    <div class="container mx-auto px-4 py-10 lg:py-14">
      <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400 mb-1">Avis clients</p>
          <div class="flex items-baseline gap-3">
            <h2 class="text-2xl font-bold text-[#254a29]">{{ formattedAverageRating }}<span class="text-base text-slate-400 font-normal"> / 5</span></h2>
            <div class="flex gap-0.5 text-[#f49926]">
              <i v-for="(icon, i) in starIconsFor(reviewStats.average ?? 0)" :key="i" :class="['bi', icon, 'text-sm']"></i>
            </div>
            <span class="text-sm text-slate-500">{{ reviewStats.count }} avis</span>
          </div>
        </div>
        <Link
          :href="reviewInviteLink"
          class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#254a29] border border-slate-200 px-4 py-2 rounded-2xl hover:border-[#f49926] hover:text-[#f49926] transition"
        >
          <i class="bi bi-chat-quote"></i>
          Laisser un avis
        </Link>
      </div>

      <div class="grid lg:grid-cols-2 gap-8">

        <!-- Liste des avis -->
        <div class="space-y-4">
          <div v-if="reviewList.length" class="space-y-4">
            <article
              v-for="review in reviewList"
              :key="review.id"
              class="p-5 rounded-2xl border border-slate-100 bg-white shadow-sm"
            >
              <div class="flex items-start justify-between gap-3">
                <div>
                  <p class="font-semibold text-[#254a29] text-sm">{{ review.author_name }}</p>
                  <p class="text-xs text-slate-400 mt-0.5">{{ formatReviewDate(review.created_at) }}</p>
                </div>
                <div class="flex gap-0.5 text-[#f49926] shrink-0">
                  <i v-for="(icon, i) in starIconsFor(review.rating)" :key="i" :class="['bi', icon, 'text-sm']"></i>
                </div>
              </div>
              <p class="mt-3 text-sm text-slate-600 leading-relaxed">{{ review.comment }}</p>
            </article>
          </div>
          <div v-else class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-400">
            <i class="bi bi-chat-heart text-3xl block mb-2 text-slate-300"></i>
            Aucun avis pour l'instant. Soyez le premier !
          </div>
        </div>

        <!-- Formulaire avis -->
        <article class="p-6 rounded-2xl border border-slate-100 bg-white shadow-sm h-fit">
          <h3 class="font-bold text-[#254a29] mb-1">Donner votre avis</h3>
          <p class="text-xs text-slate-500 mb-5">Partagez votre expérience gustative.</p>

          <div v-if="flash.success" class="mb-4 rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700">
            {{ flash.success }}
          </div>

          <form class="space-y-4" @submit.prevent="submitReview">
            <!-- Étoiles -->
            <div>
              <label class="text-xs uppercase text-slate-400 mb-2 block">Votre note</label>
              <div class="flex gap-1">
                <button
                  v-for="note in maxRating"
                  :key="note"
                  type="button"
                  class="text-2xl transition"
                  :class="note <= reviewForm.rating ? 'text-[#f49926]' : 'text-slate-200 hover:text-[#f49926]/50'"
                  @click="reviewForm.rating = note"
                >
                  <i :class="['bi', note <= reviewForm.rating ? 'bi-star-fill' : 'bi-star']"></i>
                </button>
              </div>
              <p v-if="reviewForm.errors.rating" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.rating }}</p>
            </div>

            <div>
              <label class="text-xs uppercase text-slate-400 mb-1 block">Commentaire</label>
              <textarea
                v-model="reviewForm.comment"
                rows="3"
                :class="fieldClasses"
                placeholder="Goût, texture, occasion..."
              ></textarea>
              <p v-if="reviewForm.errors.comment" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.comment }}</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-3">
              <div>
                <label class="text-xs uppercase text-slate-400 mb-1 block">Prénom / Nom</label>
                <input v-model="reviewForm.author_name" type="text" :class="fieldClasses" placeholder="Ex : Mireille S." />
                <p v-if="reviewForm.errors.author_name" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.author_name }}</p>
              </div>
              <div>
                <label class="text-xs uppercase text-slate-400 mb-1 block">Email (optionnel)</label>
                <input v-model="reviewForm.author_email" type="email" :class="fieldClasses" placeholder="Pour un suivi" />
              </div>
            </div>

            <button
              type="submit"
              class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-[#254a29] hover:bg-[#1f3d22] text-white font-semibold py-3 transition disabled:opacity-50"
              :disabled="reviewForm.processing"
            >
              <i class="bi bi-send"></i>
              Envoyer
            </button>
          </form>
        </article>
      </div>
    </div>

    <!-- Produits similaires -->
    <div v-if="related.length" class="bg-[#f2faf0]/60 border-t border-[#254a29]/10">
      <div class="container mx-auto px-4 py-10 lg:py-14">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-[#254a29]">Vous aimerez aussi</h2>
          <Link :href="route('products')" class="text-sm font-semibold text-[#f49926] hover:text-[#f28700] flex items-center gap-1">
            Voir tout <i class="bi bi-arrow-right"></i>
          </Link>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <Link
            v-for="other in related"
            :key="other.slug"
            :href="route('products.show', other.slug)"
            class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md hover:border-[#f49926]/30 transition"
          >
            <div class="aspect-[4/3] overflow-hidden bg-[#fef7ee]">
              <img
                :src="coverImage(other)"
                :alt="other.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
            </div>
            <div class="p-4">
              <p class="font-semibold text-[#254a29] text-sm">{{ other.name }}</p>
              <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ other.tagline }}</p>
              <div class="mt-3 flex items-center justify-between">
                <span class="font-bold text-[#E85A14]">{{ formatPrice(other.price) }}</span>
                <span class="text-xs text-[#254a29] font-semibold group-hover:text-[#f49926] transition">
                  Voir <i class="bi bi-arrow-right"></i>
                </span>
              </div>
            </div>
          </Link>
        </div>
      </div>
    </div>

  </AppLayout>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref, watch, onBeforeUnmount } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import useCart from '@/Composables/useCart'
import SeoHead from '@/Components/Common/SeoHead.vue'

const props = defineProps({
  product:       { type: Object, required: true },
  category:      { type: Object, default: null },
  related:       { type: Array,  default: () => [] },
  nutritionFocus:{ type: Array,  default: () => [] },
  moments:       { type: Array,  default: () => [] },
  reviews:       { type: Array,  default: () => [] },
  reviewSummary: { type: Object, default: () => ({ count: 0, average: null, distribution: [] }) },
})

const page         = usePage()
const flash        = computed(() => page.props.flash ?? {})
const siteName     = computed(() => page.props.seo?.site_name || 'Sandy Juice')
const defaultDesc  = computed(() => page.props.seo?.description || `${siteName.value} - Jus naturels`)
const defaultKw    = computed(() => page.props.seo?.keywords || 'sandy juice,jus naturels')
const maxQuantity  = computed(() => Number(page.props.shop?.max_quantity ?? 12))
const quantity     = ref(1)
const { addToCart, loading: cartLoading } = useCart()

const gallery      = computed(() => props.product.images ?? [])
const placeholder  = '/images/catalog/placeholder.jpg'
const coverFallback = computed(() => props.product.image_path || props.product.image || placeholder)

const resolveUrl = (value) => {
  if (!value) return null
  if (/^https?:\/\//i.test(value)) return value
  const base = page.props.seo?.base_url
  if (base) return `${base.replace(/\/$/, '')}${value.startsWith('/') ? value : `/${value}`}`
  return value
}

const seoImage    = computed(() => resolveUrl(gallery.value[0]?.url || coverFallback.value))
const activeImage = ref(gallery.value[0]?.url || coverFallback.value)

watch([gallery, () => props.product.image_path], ([images]) => {
  activeImage.value = images?.[0]?.url || coverFallback.value
})

const setActiveImage = (url) => { activeImage.value = url || coverFallback.value }

const coverImage = (item) => {
  if (!item) return placeholder
  return item.images?.[0]?.url || item.image_path || item.image || placeholder
}

const hasIngredients  = computed(() => (props.product.ingredients ?? []).length > 0)
const hasBenefits     = computed(() => (props.product.benefits ?? []).length > 0)
const hasAvailability = computed(() => Boolean(props.product.availability))
const moments         = computed(() => props.product.moments ?? [])
const outOfStock      = computed(() => (props.product.stock ?? 1) <= 0)

const formatPrice = (value) => {
  const n = Number(value)
  return isNaN(n) ? 'N/A' : `${n.toFixed(0)} FCFA`
}

const seoDescription = computed(() => props.product.description || props.product.tagline || defaultDesc.value)
const seoKeywords    = computed(() => {
  const kw = [props.product.name, props.category?.name, props.product.tagline]
    .concat(defaultKw.value.split(','))
    .map(k => (k || '').toString().trim())
    .filter(Boolean)
  return [...new Set(kw)].join(', ')
})

const productStructuredData = computed(() => {
  const data = {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: props.product.name,
    description: seoDescription.value,
    sku: props.product.slug,
    brand: siteName.value,
  }
  if (seoImage.value) data.image = [seoImage.value]
  if (typeof props.product.price === 'number') {
    data.offers = {
      '@type': 'Offer',
      priceCurrency: page.props.shop?.currency ?? 'FCFA',
      price: props.product.price,
      availability: outOfStock.value ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
    }
  }
  return data
})

const adjustQuantity = (delta) => {
  const next = Math.min(Math.max(quantity.value + delta, 1), maxQuantity.value)
  quantity.value = next
}

const addProductToCart = () => addToCart(props.product.id, quantity.value)

// Reviews
const maxRating    = 5
const reviewForm   = useForm({ author_name: '', author_email: '', rating: maxRating, comment: '' })
const reviewList   = computed(() => props.reviews ?? [])
const reviewStats  = computed(() => ({
  count:    props.reviewSummary?.count ?? 0,
  average:  typeof props.reviewSummary?.average === 'number' ? props.reviewSummary.average : null,
  distribution: props.reviewSummary?.distribution ?? [],
}))

const formattedAverageRating = computed(() => {
  const avg = reviewStats.value.average
  return avg === null || isNaN(avg) ? '—' : Number(avg).toFixed(1)
})

const starIconsFor = (value) => {
  const rating = Number(value ?? 0)
  return Array.from({ length: maxRating }, (_, i) => {
    const score = i + 1
    if (rating >= score) return 'bi-star-fill'
    if (rating >= score - 0.5) return 'bi-star-half'
    return 'bi-star'
  })
}

const formatReviewDate = (value) => {
  if (!value) return ''
  return new Date(value).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}

const submitReview = () => {
  reviewForm.post(route('products.reviews.store', props.product.slug), {
    preserveScroll: true,
    onSuccess: () => { reviewForm.reset('comment'); reviewForm.rating = maxRating },
  })
}

const reviewInviteLink = computed(() => route('products.reviews.create', props.product.slug))

const fieldClasses = 'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-[#254a29] bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20 placeholder:text-slate-400 outline-none resize-none'
</script>
