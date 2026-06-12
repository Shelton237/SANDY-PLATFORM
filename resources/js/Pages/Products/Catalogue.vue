<template>
  <AppLayout>
    <SeoHead
      :title="catalogueTitle"
      :description="seoDescription"
      :keywords="seoKeywords"
      :image="seoImage"
      :structured-data="catalogueStructuredData"
    />

    <!-- ── En-tête + Filtres ─────────────────────────────────────────────── -->
    <div class="bg-white border-b border-slate-100 sticky top-0 z-20">
      <div class="container mx-auto px-4">

        <!-- Titre + compteur -->
        <div class="pt-5 pb-3 flex items-end justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-[#254a29]">
              {{ categoryContext?.name ?? 'Nos jus' }}
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">
              {{ categoryContext?.tagline ?? 'Pressés à froid chaque matin — livrés chez vous' }}
            </p>
          </div>
          <span class="shrink-0 text-sm font-semibold text-slate-400">
            {{ juices.length }} produit{{ juices.length > 1 ? 's' : '' }}
          </span>
        </div>

        <!-- Recherche + Tri -->
        <div class="flex gap-3 pb-3">
          <div class="relative flex-1">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input
              v-model="search"
              type="search"
              placeholder="Gingembre, détox, ananas..."
              class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20 transition outline-none"
              @input="handleSearchInput"
              @focus="handleSearchFocus"
              @blur="handleSearchBlur"
            >
            <!-- Suggestions -->
            <div
              v-if="showSuggestionPanel"
              class="absolute left-0 right-0 top-[calc(100%+6px)] z-30 rounded-2xl border border-slate-100 bg-white shadow-2xl overflow-hidden"
            >
              <div v-if="suggestionLoading" class="px-4 py-3 flex items-center gap-2 text-sm text-slate-500">
                <i class="bi bi-arrow-repeat animate-spin text-[#f49926]"></i>
                Recherche...
              </div>
              <template v-else>
                <Link
                  v-for="item in suggestions"
                  :key="item.slug"
                  :href="item.url"
                  class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition"
                  @click="handleSuggestionNavigate"
                >
                  <img
                    :src="item.image || '/images/catalog/placeholder.jpg'"
                    :alt="item.name"
                    class="w-10 h-10 rounded-lg object-cover flex-shrink-0 border border-slate-100"
                  >
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-[#254a29] truncate">{{ item.name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ item.tagline }}</p>
                  </div>
                  <span class="text-xs font-semibold text-[#f49926] bg-[#fef4e7] px-2 py-0.5 rounded-full shrink-0">
                    {{ item.category_label }}
                  </span>
                </Link>
                <div v-if="!suggestions.length" class="px-4 py-3 text-sm text-slate-400">
                  Aucun résultat
                </div>
              </template>
            </div>
          </div>
          <select
            v-model="sort"
            class="rounded-xl border border-slate-200 py-2.5 px-3 text-sm text-slate-600 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20 outline-none transition"
            @change="handleSortChange"
          >
            <option v-for="option in sortOptions" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </select>
        </div>

        <!-- Catégories + filtre actif -->
        <div class="flex items-center gap-2 pb-3 overflow-x-auto scrollbar-none">
          <button
            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium border transition"
            :class="!selectedCategory
              ? 'bg-[#254a29] text-white border-[#254a29]'
              : 'border-slate-200 text-slate-500 hover:border-[#254a29]/40'"
            @click="toggleCategory(null)"
          >
            Tous
          </button>
          <button
            v-for="cat in categories"
            :key="cat.slug"
            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium border transition"
            :class="selectedCategory === cat.slug
              ? 'bg-[#254a29] text-white border-[#254a29]'
              : 'border-slate-200 text-slate-500 hover:border-[#254a29]/40'"
            @click="toggleCategory(cat.slug)"
          >
            {{ cat.name }}
          </button>
          <!-- Chip filtre prix actif -->
          <span
            v-if="hasPriceFilter"
            class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"
          >
            <i class="bi bi-cash-stack"></i>
            ≤ {{ formatPrice(effectivePriceMax) }}
            <button @click="clearPriceFilter" class="ml-0.5">
              <i class="bi bi-x"></i>
            </button>
          </span>
          <!-- Chip filtre ingrédient actif -->
          <span
            v-if="hasIngredientFilter"
            class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#fef4e7] text-[#f49926] border border-[#f49926]/30"
          >
            <i class="bi bi-filter"></i>
            {{ ingredientMap[selectedIngredient] || selectedIngredient }}
            <button @click="clearIngredientFilter" class="ml-0.5">
              <i class="bi bi-x"></i>
            </button>
          </span>
          <button
            v-if="isFiltering || hasIngredientFilter || hasPriceFilter || activeMoment || trimmedSearch.length"
            class="shrink-0 text-xs text-slate-400 hover:text-[#f49926] ml-auto transition"
            @click="clearFilters"
          >
            Réinitialiser
          </button>
        </div>
      </div>
    </div>
    <!-- ──────────────────────────────────────────────────────────────────── -->

    <div class="container mx-auto px-4 py-6">

      <!-- Spinner global -->
      <div v-if="isFiltering" class="flex items-center justify-center py-12">
        <i class="bi bi-arrow-repeat animate-spin text-[#f49926] text-3xl"></i>
      </div>

      <!-- Grille produits -->
      <div v-else-if="juices.length" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <article
          v-for="juice in juices"
          :key="juice.slug"
          class="group flex flex-col rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden"
        >
          <!-- Image -->
          <Link :href="route('products.show', juice.slug)" class="block relative aspect-[4/3] overflow-hidden bg-slate-50">
            <img
              :src="coverImage(juice)"
              :alt="juice.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            >
            <!-- Badge catégorie -->
            <span class="absolute top-3 left-3 text-[11px] font-semibold px-2.5 py-1 rounded-full backdrop-blur-sm bg-white/80 text-[#254a29] border border-slate-200">
              {{ categoryMap[juice.category]?.name || juice.category_name || 'Jus' }}
            </span>
            <!-- Badge nouveau / promo -->
            <span
              v-if="juice.badge"
              class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#E85A14] text-white"
            >
              {{ juice.badge }}
            </span>
          </Link>

          <!-- Infos -->
          <div class="flex flex-col flex-1 p-4">
            <Link :href="route('products.show', juice.slug)" class="block">
              <h2 class="font-semibold text-[#254a29] text-base leading-snug">{{ juice.name }}</h2>
              <p v-if="juice.tagline" class="text-xs text-slate-500 mt-1 line-clamp-2">{{ juice.tagline }}</p>
            </Link>

            <!-- Prix -->
            <div class="mt-3 flex items-center justify-between">
              <span class="text-lg font-bold text-[#f49926]">{{ formatPrice(juice.price) }}</span>
              <span v-if="juice.size" class="text-xs text-slate-400">{{ juice.size }}</span>
            </div>

            <!-- CTA -->
            <div class="mt-3 flex gap-2">
              <button
                type="button"
                class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#E85A14] hover:bg-[#c44010] text-white text-sm font-semibold py-2.5 transition disabled:opacity-50"
                :disabled="cartLoading"
                @click="addJuiceToCart(juice)"
              >
                <i class="bi bi-cart-plus text-base"></i>
                Ajouter
              </button>
              <Link
                :href="route('products.show', juice.slug)"
                class="inline-flex items-center justify-center w-10 rounded-xl border border-slate-200 hover:border-[#254a29] text-slate-500 hover:text-[#254a29] transition"
                title="Voir le détail"
              >
                <i class="bi bi-eye text-base"></i>
              </Link>
            </div>
          </div>
        </article>
      </div>

      <!-- État vide -->
      <div v-else class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
          <i class="bi bi-search text-2xl text-slate-400"></i>
        </div>
        <p class="text-lg font-semibold text-[#254a29]">Aucun produit trouvé</p>
        <p class="text-sm text-slate-500 mt-1 mb-4">Essayez d'autres filtres ou consultez tout le catalogue.</p>
        <button
          class="px-5 py-2 rounded-xl bg-[#254a29] text-white text-sm font-semibold hover:bg-[#1c3820] transition"
          @click="clearFilters"
        >
          Voir tous les produits
        </button>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { computed, onUnmounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import useCart from '@/Composables/useCart'
import SeoHead from '@/Components/Common/SeoHead.vue'

const props = defineProps({
  juices:          { type: Array,  default: () => [] },
  categories:      { type: Array,  default: () => [] },
  collections:     { type: Array,  default: () => [] },
  nutritionFocus:  { type: Array,  default: () => [] },
  filters:         { type: Object, default: () => ({}) },
  categoryContext: { type: Object, default: null },
  featured:        { type: Object, default: () => ({}) },
  metrics:         { type: Object, default: () => ({}) },
  moments:         { type: Array,  default: () => [] },
  sortOptions:     { type: Array,  default: () => [] },
  ingredientOptions: { type: Array, default: () => [] },
  priceRange:      { type: Object, default: () => ({ min: 0, max: 0 }) },
})

const page = usePage()
const juices = computed(() => props.juices ?? [])
const siteName = computed(() => page.props.seo?.site_name || 'Sandy Juice')
const { categoryContext, categories } = props

const resolveAssetUrl = (value) => {
  if (!value) return null
  if (/^https?:\/\//i.test(value)) return value
  const base = page.props.seo?.base_url
  if (base) return base.replace(/\/$/, '') + (value.startsWith('/') ? value : `/${value}`)
  return value
}

// ── Filtres ──────────────────────────────────────────────────────────────
const search           = ref(props.filters?.search ?? '')
const selectedCategory = ref(props.filters?.category ?? props.categoryContext?.slug ?? null)
const activeMoment     = ref(props.filters?.moment ?? null)
const sort             = ref(props.filters?.sort ?? 'popularity')
const isFiltering      = ref(false)

const ingredientOptionsList = computed(() => props.ingredientOptions ?? [])
const ingredientMap = computed(() => {
  const map = {}
  ingredientOptionsList.value.forEach(o => { map[o.value] = o.label })
  return map
})
const selectedIngredient = ref(props.filters?.ingredient ?? null)

const priceLimits = computed(() => ({
  min: Number.isFinite(Number(props.priceRange?.min)) ? Number(props.priceRange.min) : 0,
  max: Number.isFinite(Number(props.priceRange?.max)) ? Number(props.priceRange.max) : 0,
}))
const priceRangeAvailable = computed(() => priceLimits.value.max > 0)
const priceMax = ref(props.filters?.price_max ?? (priceRangeAvailable.value ? priceLimits.value.max : null))
const effectivePriceMax = computed(() => {
  if (!priceRangeAvailable.value) return 0
  const v = Number(priceMax.value ?? priceLimits.value.max)
  return Number.isNaN(v) ? priceLimits.value.max : Math.min(Math.max(v, priceLimits.value.min), priceLimits.value.max)
})
const hasPriceFilter     = computed(() => priceRangeAvailable.value && effectivePriceMax.value < priceLimits.value.max)
const hasIngredientFilter = computed(() => Boolean(selectedIngredient.value))
const trimmedSearch      = computed(() => (search.value ?? '').trim())

// ── Suggestions ──────────────────────────────────────────────────────────
const suggestions       = ref([])
const suggestionLoading = ref(false)
const suggestionsVisible = ref(false)
const showSuggestionPanel = computed(() => suggestionsVisible.value && trimmedSearch.value.length >= 2)
let suggestionAbortController = null

// ── Panier ───────────────────────────────────────────────────────────────
const { addToCart, loading: cartLoading } = useCart()
const categoryMap = computed(() => {
  const map = {}
  ;(props.categories ?? []).forEach(c => { map[c.slug] = c })
  return map
})

const addJuiceToCart = (juice) => { if (juice?.id) addToCart(juice.id) }

// ── SEO ──────────────────────────────────────────────────────────────────
const catalogueTitle = computed(() =>
  props.categoryContext?.name ? `Catalogue ${props.categoryContext.name}` : 'Catalogue Sandy Juice'
)
const seoDescription = computed(() => {
  const base = props.categoryContext?.tagline
    ?? (juices.value.length ? `Découvrez ${juices.value.length} recettes pressées à froid, prêtes pour la livraison express.` : 'Découvrez les cures pressées à froid Sandy Juice.')
  return base.includes(siteName.value) ? base : `${base} ${siteName.value}`
})
const seoKeywords = computed(() => {
  const kw = ['catalogue sandy juice', 'jus naturels Cameroun', 'pressage a froid']
  ;(props.categories ?? []).forEach(c => kw.push(c?.name))
  juices.value.slice(0, 6).forEach(j => { kw.push(j?.name); kw.push(j?.tagline) })
  const seen = new Set()
  return kw.filter(k => { const v = (k || '').toString().trim().toLowerCase(); if (!v || seen.has(v)) return false; seen.add(v); return true }).join(', ')
})
const heroVisual = computed(() => {
  const c = (props.featured?.hero ?? juices.value[0]) ?? null
  return c ? coverImage(c) : '/images/catalog/placeholder.jpg'
})
const seoImage = computed(() => resolveAssetUrl(heroVisual.value))
const catalogueStructuredData = computed(() => ({
  '@context': 'https://schema.org',
  '@type': 'ItemList',
  name: catalogueTitle.value,
  description: seoDescription.value,
  numberOfItems: juices.value.length,
  itemListElement: juices.value.slice(0, 12).map((j, i) => ({
    '@type': 'ListItem',
    position: i + 1,
    url: route('products.show', j.slug),
    name: j.name,
    description: j.tagline,
    image: resolveAssetUrl(coverImage(j)),
  })),
}))

// ── Helpers ──────────────────────────────────────────────────────────────
function coverImage(item) {
  if (!item) return '/images/catalog/placeholder.jpg'
  return item.images?.[0]?.url || item.image || '/images/catalog/placeholder.jpg'
}

const formatPrice = (value) =>
  typeof value === 'number' ? `${value.toFixed(0)} FCFA` : 'N/A'

// ── Navigation avec filtres ───────────────────────────────────────────────
let searchTimer = null
let priceTimer  = null
let suggestionTimer = null

const visitWithFilters = () => {
  isFiltering.value = true
  const query = {}
  if (trimmedSearch.value.length) query.q = trimmedSearch.value
  if (sort.value && sort.value !== 'popularity') query.sort = sort.value
  if (activeMoment.value) query.moment = activeMoment.value
  if (selectedIngredient.value) query.ingredient = selectedIngredient.value
  if (hasPriceFilter.value) query.price_max = Math.round(effectivePriceMax.value)

  const options = {
    preserveScroll: true, preserveState: true, replace: true,
    only: ['juices', 'filters', 'categoryContext'],
    onFinish: () => { isFiltering.value = false },
  }

  if (selectedCategory.value) {
    router.get(route('products.category', { category: selectedCategory.value }), query, options)
  } else {
    router.get(route('products'), query, options)
  }
}

const fetchSuggestions = async () => {
  const term = trimmedSearch.value
  if (term.length < 2) {
    suggestionAbortController?.abort(); suggestionAbortController = null
    suggestions.value = []; suggestionLoading.value = false; suggestionsVisible.value = false
    return
  }
  suggestionAbortController?.abort()
  suggestionAbortController = new AbortController()
  suggestionLoading.value = true; suggestionsVisible.value = true
  try {
    const res = await axios.get(route('products.suggestions'), { params: { q: term }, signal: suggestionAbortController.signal })
    suggestions.value = Array.isArray(res.data) ? res.data : []
  } catch (e) {
    if (e?.code !== 'ERR_CANCELED' && e?.name !== 'CanceledError') console.error(e)
  } finally {
    suggestionLoading.value = false
  }
}

const scheduleSuggestionFetch = () => {
  if (suggestionTimer) clearTimeout(suggestionTimer)
  suggestionTimer = setTimeout(fetchSuggestions, 250)
}

const handleSearchInput = () => {
  if (searchTimer) clearTimeout(searchTimer)
  searchTimer = setTimeout(visitWithFilters, 350)
  if (trimmedSearch.value.length < 2) {
    suggestionAbortController?.abort(); suggestionAbortController = null
    suggestions.value = []; suggestionsVisible.value = false; suggestionLoading.value = false
    return
  }
  scheduleSuggestionFetch()
}
const handleSearchFocus = () => {
  if (trimmedSearch.value.length >= 2) {
    suggestionsVisible.value = suggestions.value.length > 0 || suggestionLoading.value
    if (!suggestions.value.length && !suggestionLoading.value) scheduleSuggestionFetch()
  }
}
const handleSearchBlur = () => { setTimeout(() => { suggestionsVisible.value = false }, 120) }
const handleSuggestionNavigate = () => { suggestionsVisible.value = false }
const handleSortChange = () => { visitWithFilters() }

const toggleCategory = (slug) => {
  selectedCategory.value = slug === selectedCategory.value ? null : slug
  visitWithFilters()
}
const toggleIngredient = (value) => {
  selectedIngredient.value = selectedIngredient.value === value ? null : value
  visitWithFilters()
}
const clearIngredientFilter = () => { if (!selectedIngredient.value) return; selectedIngredient.value = null; visitWithFilters() }
const handlePriceInput = () => {
  if (!priceRangeAvailable.value) return
  if (priceTimer) clearTimeout(priceTimer)
  priceTimer = setTimeout(visitWithFilters, 350)
}
const clearPriceFilter = () => { if (!priceRangeAvailable.value) return; priceMax.value = priceLimits.value.max; visitWithFilters() }
const clearFilters = () => {
  search.value = ''; activeMoment.value = null; sort.value = 'popularity'
  selectedCategory.value = null; selectedIngredient.value = null
  priceMax.value = priceRangeAvailable.value ? priceLimits.value.max : null
  suggestions.value = []; suggestionsVisible.value = false
  suggestionAbortController?.abort(); suggestionAbortController = null
  visitWithFilters()
}

onUnmounted(() => {
  if (searchTimer) clearTimeout(searchTimer)
  if (suggestionTimer) clearTimeout(suggestionTimer)
  if (priceTimer) clearTimeout(priceTimer)
  suggestionAbortController?.abort()
})
</script>
