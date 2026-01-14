<template>
  <AppLayout>
    <Head title="Catalogue Sandy Juice" />

    <section class="bg-gradient-to-br from-[#fef7ee] via-white to-[#f2faf0] border-b border-orange-100">
      <div class="container mx-auto px-4 py-12 lg:py-16">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
          <div>
            <p class="inline-flex items-center text-xs uppercase tracking-[0.3em] text-orange-500 mb-4">
              Catalogue signature
            </p>
            <h1 class="text-4xl lg:text-5xl font-bold text-[#254a29] leading-tight">
              Nos jus artisanaux presses a froid, dessines pour chaque moment.
            </h1>
            <p class="mt-6 text-base text-slate-600 max-w-xl">
              Selectionnez une cure detox, un boost energie ou un snack kids.
              Toutes les recettes sont presses le matin meme dans notre atelier Sandy.
            </p>
            <div class="mt-8 grid sm:grid-cols-3 gap-4">
              <div v-for="item in heroMetrics" :key="item.label" class="p-4 rounded-2xl bg-white shadow-sm border border-slate-100">
                <p class="text-2xl font-semibold text-[#f49926]">{{ item.value }}</p>
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ item.label }}</p>
              </div>
            </div>
          </div>
          <div class="space-y-4">
            <div v-if="heroJuice" class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100">
              <div class="flex items-center justify-between">
                <span class="text-xs font-semibold px-3 py-1 rounded-full" :class="accentClass(heroJuice.accent, 'badge')">
                  Hero pressing
                </span>
                <span class="text-xs text-slate-400">{{ heroJuice.size }}</span>
              </div>
              <h2 class="mt-4 text-2xl font-semibold text-[#254a29]">{{ heroJuice.name }}</h2>
              <p class="text-sm text-slate-500 mt-1">{{ heroJuice.tagline }}</p>
              <div class="mt-4 rounded-2xl overflow-hidden border border-slate-100">
                <img :src="coverImage(heroJuice)" :alt="heroJuice.name" class="w-full h-48 object-cover" />
              </div>
              <div class="mt-6 flex items-center justify-between">
                <div>
                  <p class="text-xs text-slate-400 uppercase">Prix unitaire</p>
                  <p class="text-2xl font-semibold text-[#f49926]">{{ formatPrice(heroJuice.price) }}</p>
                </div>
                <div class="text-right">
                  <p class="text-xs text-slate-400 uppercase">Calories</p>
                  <p class="text-2xl font-semibold text-[#254a29]">{{ heroJuice.calories }} kcal</p>
                </div>
              </div>
              <div class="mt-6 flex flex-wrap gap-2">
                <span v-for="note in heroJuice.taste" :key="note" class="text-xs px-3 py-1 rounded-full border border-slate-200 text-slate-500">
                  {{ note }}
                </span>
              </div>
              <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-slate-500">
                  <p class="font-semibold text-[#254a29]">Batch du jour</p>
                  <p>{{ heroJuice.availability }}</p>
                </div>
                <Link :href="route('products.show', heroJuice.slug)" class="text-sm font-semibold text-[#f49926] hover:text-[#f28700]">
                  Voir la fiche
                </Link>
              </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
              <div v-if="newJuice" class="bg-white rounded-2xl p-4 border border-slate-100">
                <p class="text-xs uppercase text-[#f49926] font-semibold mb-2">Nouveaute</p>
                <p class="font-semibold text-[#254a29]">{{ newJuice.name }}</p>
                <p class="text-xs text-slate-500 mb-3">{{ newJuice.tagline }}</p>
                <Link :href="route('products.show', newJuice.slug)" class="text-sm font-medium text-[#f49926] hover:text-[#f28700]">
                  Decrypter
                </Link>
              </div>
              <div v-if="limitedJuice" class="bg-slate-900 text-white rounded-2xl p-4">
                <p class="text-xs uppercase tracking-[0.3em] text-orange-200 mb-2">Edition limitee</p>
                <p class="font-semibold">{{ limitedJuice.name }}</p>
                <p class="text-xs opacity-80 mb-3">{{ limitedJuice.tagline }}</p>
                <Link :href="route('products.show', limitedJuice.slug)" class="text-sm font-medium text-orange-200 hover:text-orange-100">
                  200 bouteilles
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="container mx-auto px-4 py-12 lg:py-16 space-y-10">
      <div class="grid gap-4 md:grid-cols-4">
        <div v-for="metric in metricCards" :key="metric.label" class="p-5 rounded-2xl border border-slate-100 bg-white shadow-sm">
          <p class="text-3xl font-semibold text-[#254a29]">{{ metric.value }}</p>
          <p class="text-xs uppercase tracking-wide text-slate-400">{{ metric.label }}</p>
          <p class="mt-1 text-sm text-slate-500">{{ metric.caption }}</p>
        </div>
      </div>

      <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 lg:p-8 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
          <div class="w-full lg:w-1/2">
            <label class="text-xs uppercase text-slate-500 tracking-wide">Recherche par ingredient ou mood</label>
            <div class="mt-2 relative">
              <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input
                v-model="search"
                type="search"
                placeholder="Gingembre, ananas, detox..."
                class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20 transition"
                @input="handleSearchInput"
              >
            </div>
          </div>
          <div class="w-full lg:w-auto">
            <label class="text-xs uppercase text-slate-500 tracking-wide">Trier</label>
            <div class="mt-2">
              <select v-model="sort" class="w-full lg:w-56 rounded-2xl border border-slate-200 py-3 px-4 focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20" @change="handleSortChange">
                <option v-for="option in sortOptions" :key="option.value" :value="option.value">
                  {{ option.label }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap gap-2">
          <button
            v-for="momentOption in props.moments"
            :key="momentOption.value"
            class="px-4 py-2 rounded-full border text-sm transition"
            :class="[
              activeMoment === momentOption.value
                ? 'border-[#f49926] bg-[#fef4e7] text-[#f49926]'
                : 'border-slate-200 text-slate-500 hover:border-[#f49926]/40'
            ]"
            @click="toggleMoment(momentOption.value)"
          >
            <i :class="momentOption.icon" class="mr-2"></i>
            {{ momentOption.label }}
          </button>
        </div>

        <div class="border-t border-slate-100 pt-6">
          <p class="text-xs uppercase text-slate-500 tracking-wide mb-3">Categories</p>
          <div class="flex flex-wrap gap-3">
            <button
              v-for="category in props.categories"
              :key="category.slug"
              class="px-4 py-3 rounded-2xl border transition text-left"
              :class="[
                selectedCategory === category.slug
                  ? 'border-[#f49926] bg-[#fef4e7]'
                  : 'border-slate-200 hover:border-[#f49926]/50'
              ]"
              @click="toggleCategory(category.slug)"
            >
              <div class="font-semibold text-[#254a29]">{{ category.name }}</div>
              <div class="text-xs text-slate-500">{{ category.description }}</div>
            </button>
          </div>
          <div class="mt-4 flex flex-wrap gap-3 text-sm">
            <button class="text-[#f49926] font-semibold hover:underline" @click="clearFilters">
              Reinitialiser les filtres
            </button>
            <span v-if="isFiltering" class="text-slate-400 flex items-center gap-2">
              <i class="bi bi-arrow-repeat animate-spin"></i> Mise a jour
            </span>
          </div>
        </div>
      </div>

      <div>
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-6">
          <div>
            <p class="text-xs uppercase text-slate-500 tracking-[0.3em]">Nos recettes</p>
            <h2 class="text-3xl font-semibold text-[#254a29]">Catalogue Sandy Juice</h2>
            <p v-if="categoryContext" class="text-sm text-slate-500 mt-1">
              {{ categoryContext.tagline }}
            </p>
          </div>
          <div class="text-sm text-slate-500">
            {{ juices.length }} recettes selectionnees
          </div>
        </div>

        <div v-if="juices.length" class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="juice in juices" :key="juice.slug" class="h-full flex flex-col rounded-3xl border border-slate-100 bg-white shadow-sm hover:shadow-lg transition">
            <div class="p-6 flex-1 flex flex-col">
              <div class="flex items-center justify-between">
                <span class="text-xs font-semibold px-3 py-1 rounded-full" :class="accentClass(juice.accent, 'badge')">
                  {{ categoryMap[juice.category]?.name || 'Collection' }}
                </span>
                <span v-if="juice.badge" class="text-xs uppercase text-slate-400">
                  {{ juice.badge }}
                </span>
              </div>

              <h3 class="mt-4 text-xl font-semibold text-[#254a29]">{{ juice.name }}</h3>
              <p class="text-sm text-slate-500 mt-1">
                {{ juice.tagline }}
              </p>

              <div class="mt-4 rounded-2xl overflow-hidden border border-slate-100">
                <img :src="coverImage(juice)" :alt="juice.name" class="w-full h-40 object-cover" />
              </div>

              <div class="mt-4 flex flex-wrap gap-2">
                <span v-for="note in juice.taste" :key="note" class="text-xs px-3 py-1 rounded-full" :class="accentClass(juice.accent, 'pill')">
                  {{ note }}
                </span>
              </div>

              <div class="mt-5 grid grid-cols-2 gap-4 text-sm">
                <div>
                  <p class="text-xs uppercase text-slate-400">Prix</p>
                  <p class="text-lg font-semibold text-[#f49926]">{{ formatPrice(juice.price) }}</p>
                </div>
                <div>
                  <p class="text-xs uppercase text-slate-400">Calories</p>
                  <p class="text-lg font-semibold text-[#254a29]">{{ juice.calories }} kcal</p>
                </div>
              </div>

              <div class="mt-5 border-t border-dashed border-slate-200 pt-4">
                <p class="text-xs uppercase text-slate-400 mb-2">Ingredients stars</p>
                <div class="flex flex-wrap gap-2">
                  <span v-for="ingredient in juice.ingredients?.slice(0, 3)" :key="ingredient.name" class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                    {{ ingredient.name }}
                  </span>
                </div>
              </div>

              <ul class="mt-4 space-y-1 text-sm text-slate-500">
                <li v-for="benefit in juice.benefits?.slice(0, 2)" :key="benefit" class="flex items-start gap-2">
                  <i class="bi bi-check-circle text-[#f49926] mt-1"></i>
                  <span>{{ benefit }}</span>
                </li>
              </ul>

              <div class="mt-auto pt-5 flex items-center justify-between">
                <div>
                  <p class="text-xs uppercase text-slate-400">Batch</p>
                  <p class="text-sm font-semibold text-[#254a29]">{{ juice.availability }}</p>
                </div>
                <div class="flex items-center gap-3">
                  <span class="text-sm text-slate-500">{{ juice.size }}</span>
                  <Link :href="route('products.show', juice.slug)" class="inline-flex items-center text-[#f49926] font-semibold hover:text-[#f28700]">
                    Details
                    <i class="bi bi-arrow-up-right ml-1"></i>
                  </Link>
                </div>
              </div>
              <button
                type="button"
                class="mt-4 inline-flex items-center justify-center gap-2 rounded-2xl border border-[#f49926] px-4 py-2 text-sm font-semibold text-[#f49926] hover:bg-[#fef4e7] transition disabled:opacity-50"
                :disabled="cartLoading"
                @click="addJuiceToCart(juice)"
              >
                <i class="bi bi-cart-plus"></i>
                Ajouter au panier
              </button>
            </div>
          </article>
        </div>
        <div v-else class="text-center py-16 border border-dashed border-slate-200 rounded-3xl bg-white">
          <p class="text-2xl font-semibold text-[#254a29]">Aucune recette ne correspond</p>
          <p class="text-slate-500 mt-2">Modifiez vos filtres ou contactez-nous pour une creation sur-mesure.</p>
        </div>
      </div>

      <div class="grid gap-6 lg:grid-cols-3">
        <article v-for="collection in props.collections" :key="collection.slug" class="p-6 rounded-3xl border border-slate-100 bg-slate-900 text-white">
          <div class="flex items-center justify-between">
            <p class="text-xs uppercase tracking-[0.3em] text-orange-200">{{ collection.tag }}</p>
            <span class="text-sm">{{ collection.duration }}</span>
          </div>
          <h3 class="mt-3 text-2xl font-semibold">{{ collection.title }}</h3>
          <p class="mt-2 text-sm text-white/70">{{ collection.description }}</p>
          <div class="mt-4 flex flex-wrap gap-2">
            <span v-for="item in collection.items" :key="item.slug" class="text-xs px-3 py-1 rounded-full bg-white/10 border border-white/20">
              {{ item.name }}
            </span>
          </div>
          <div class="mt-6 flex items-center justify-between">
            <div>
              <p class="text-xs uppercase text-white/50">Pack</p>
              <p class="text-2xl font-semibold text-white">{{ formatPrice(collection.price) }}</p>
            </div>
            <Link :href="route('contact')" class="inline-flex items-center gap-2 text-sm font-semibold text-orange-200 hover:text-orange-100">
              Demander
              <i class="bi bi-arrow-up-right"></i>
            </Link>
          </div>
        </article>
      </div>

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <article v-for="focus in props.nutritionFocus" :key="focus.title" class="p-6 rounded-2xl border border-slate-100 bg-white shadow-sm">
          <p class="text-xs uppercase text-slate-400 tracking-[0.3em]">{{ focus.title }}</p>
          <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ focus.value }}</p>
          <p class="text-sm text-slate-500 mt-2">{{ focus.description }}</p>
        </article>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, onUnmounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import useCart from '@/Composables/useCart'

const props = defineProps({
  juices: {
    type: Array,
    default: () => []
  },
  categories: {
    type: Array,
    default: () => []
  },
  collections: {
    type: Array,
    default: () => []
  },
  nutritionFocus: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  categoryContext: {
    type: Object,
    default: null
  },
  featured: {
    type: Object,
    default: () => ({})
  },
  metrics: {
    type: Object,
    default: () => ({})
  },
  moments: {
    type: Array,
    default: () => []
  },
  sortOptions: {
    type: Array,
    default: () => []
  }
})

const juices = computed(() => props.juices ?? [])
const search = ref(props.filters?.search ?? '')
const selectedCategory = ref(props.filters?.category ?? props.categoryContext?.slug ?? null)
const activeMoment = ref(props.filters?.moment ?? null)
const sort = ref(props.filters?.sort ?? 'popularity')
const isFiltering = ref(false)

let searchTimer = null

const { addToCart, loading: cartLoading } = useCart()

const categoryMap = computed(() => {
  const map = {}
  ;(props.categories ?? []).forEach((category) => {
    map[category.slug] = category
  })
  return map
})

const addJuiceToCart = (juice) => {
  if (!juice?.id) {
    return
  }
  addToCart(juice.id)
}

const heroJuice = computed(() => props.featured?.hero ?? null)
const newJuice = computed(() => props.featured?.new ?? null)
const limitedJuice = computed(() => props.featured?.limited ?? null)

const heroMetrics = computed(() => [
  { label: 'Cures actives', value: `${props.metrics?.recipes || juices.value.length}+` },
  { label: 'Categories', value: props.metrics?.categories || props.categories.length },
  { label: 'Edition limitee', value: props.metrics?.limited || 0 }
])

const metricCards = computed(() => [
  {
    label: 'Recettes actives',
    value: `${props.metrics?.recipes || juices.value.length}`,
    caption: 'Pressage chaque matin'
  },
  {
    label: 'Calories moyennes',
    value: `${props.metrics?.averageCalories || 0} kcal`,
    caption: 'Equilibre nutritionnel'
  },
  {
    label: 'Cures detox',
    value: `${categoryMap.value.detox ? '3 jours' : 'Disponible'}`,
    caption: 'Programmes sur-mesure'
  },
  {
    label: 'Editions limitees',
    value: props.metrics?.limited || 0,
    caption: 'Batches hebdo'
  }
])

const accentVariants = {
  emerald: { badge: 'bg-emerald-50 text-emerald-700', pill: 'bg-emerald-100 text-emerald-700' },
  orange: { badge: 'bg-orange-50 text-orange-700', pill: 'bg-orange-100 text-orange-700' },
  rose: { badge: 'bg-rose-50 text-rose-700', pill: 'bg-rose-100 text-rose-700' },
  amber: { badge: 'bg-amber-50 text-amber-700', pill: 'bg-amber-100 text-amber-700' },
  indigo: { badge: 'bg-indigo-50 text-indigo-700', pill: 'bg-indigo-100 text-indigo-700' },
  default: { badge: 'bg-slate-100 text-slate-700', pill: 'bg-slate-100 text-slate-700' }
}

const accentClass = (accent, type) => {
  const variant = accentVariants[accent] ?? accentVariants.default
  return variant[type] ?? accentVariants.default[type]
}

const coverImage = (item) => {
  if (!item) return '/images/catalog/placeholder.jpg'
  return item.images?.[0]?.url || item.image || '/images/catalog/placeholder.jpg'
}

const visitWithFilters = () => {
  isFiltering.value = true

  const query = {}
  if (search.value) query.q = search.value
  if (sort.value && sort.value !== 'popularity') query.sort = sort.value
  if (activeMoment.value) query.moment = activeMoment.value

  const options = {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    only: ['juices', 'filters', 'categoryContext'],
    onFinish: () => {
      isFiltering.value = false
    }
  }

  if (selectedCategory.value) {
    router.get(route('products.category', { category: selectedCategory.value }), query, options)
  } else {
    router.get(route('products'), query, options)
  }
}

const handleSearchInput = () => {
  if (searchTimer) {
    clearTimeout(searchTimer)
  }
  searchTimer = setTimeout(() => {
    visitWithFilters()
  }, 350)
}

const handleSortChange = () => {
  visitWithFilters()
}

const toggleCategory = (slug) => {
  selectedCategory.value = selectedCategory.value === slug ? null : slug
  visitWithFilters()
}

const toggleMoment = (value) => {
  activeMoment.value = activeMoment.value === value ? null : value
  visitWithFilters()
}

const clearFilters = () => {
  search.value = ''
  activeMoment.value = null
  sort.value = 'popularity'
  selectedCategory.value = null
  visitWithFilters()
}

const formatPrice = (value) => {
  if (typeof value !== 'number') {
    return 'N/A'
  }

  return `${value.toFixed(0)} FCFA`
}

onUnmounted(() => {
  if (searchTimer) {
    clearTimeout(searchTimer)
  }
})
</script>
