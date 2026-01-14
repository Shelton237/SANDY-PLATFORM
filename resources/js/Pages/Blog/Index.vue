<template>
  <AppLayout>
    <Head title="Blog Sandy Juice" />

    <section class="bg-gradient-to-b from-[#1c3b21] to-[#254a29] text-white py-16">
      <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center space-y-4">
          <p class="text-xs uppercase tracking-[0.4em] text-amber-300">Nos histoires</p>
          <h1 class="text-4xl md:text-5xl font-bold">Blog & coulisses de l'usine Sandy Juice</h1>
          <p class="text-lg text-white/80 max-w-3xl mx-auto">
            Restez informé des innovations produits, de notre circuit de production au Cameroun et des conseils bien-être pour
            consommer nos jus naturels.
          </p>
        </div>

        <form class="mt-10 bg-white/10 border border-white/20 rounded-3xl p-4 md:p-6 backdrop-blur" @submit.prevent="applyFilters">
          <div class="flex flex-col md:flex-row gap-4">
            <input
              v-model="search"
              type="search"
              placeholder="Rechercher une histoire, un ingrédient, un conseil..."
              class="flex-1 rounded-2xl bg-white/90 text-slate-800 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-300"
            />
            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-[#f49926] text-white font-semibold">
              <i class="bi bi-search mr-2"></i>
              Rechercher
            </button>
          </div>
          <div class="flex flex-wrap gap-3 mt-4">
            <button
              v-for="category in categoryOptions"
              :key="`cat-${category}`"
              type="button"
              class="px-4 py-2 rounded-2xl border text-sm transition"
              :class="selectedCategory === category ? 'bg-white text-[#254a29] font-semibold' : 'border-white/40 text-white/80 hover:text-white'"
              @click="toggleCategory(category)"
            >
              {{ category }}
            </button>
            <button
              v-if="selectedCategory"
              type="button"
              class="text-xs uppercase tracking-widest text-white/70 hover:text-white"
              @click="clearCategory"
            >
              Réinitialiser
            </button>
          </div>
        </form>
      </div>
    </section>

    <section class="py-12 bg-white">
      <div class="container mx-auto px-4 max-w-6xl">
        <div v-if="featured.length" class="mb-16">
          <div class="flex items-center justify-between mb-5">
            <h2 class="text-2xl font-semibold text-[#254a29]">Mises en avant</h2>
            <span class="text-sm text-slate-500">Notre sélection éditoriale</span>
          </div>
          <div class="grid gap-6 md:grid-cols-2">
            <article
              v-for="item in featured"
              :key="item.id"
              class="rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col md:flex-row"
            >
              <div class="md:w-1/2 h-64 md:h-auto">
                <img :src="item.cover_image || '/images/placeholder-blog.jpg'" :alt="item.title" class="w-full h-full object-cover" loading="lazy" />
              </div>
              <div class="flex-1 p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-500">{{ formatDate(item.published_at) }}</p>
                <h3 class="text-2xl font-semibold text-[#254a29]">{{ item.title }}</h3>
                <p class="text-slate-600 text-sm">{{ item.excerpt }}</p>
                <Link :href="route('blog.show', item.slug)" class="inline-flex items-center text-[#f49926] font-semibold group">
                  Lire l'article
                  <i class="bi bi-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                </Link>
              </div>
            </article>
          </div>
        </div>

        <div class="grid gap-10 lg:grid-cols-[2fr,1fr]">
          <div class="space-y-6">
            <article
              v-for="post in posts.data"
              :key="post.id"
              class="rounded-3xl border border-slate-100 bg-white shadow-sm overflow-hidden hover:shadow-lg transition group"
            >
              <div class="h-72 w-full">
                <img :src="post.cover_image || '/images/placeholder-blog.jpg'" :alt="post.title" class="w-full h-full object-cover" loading="lazy" />
              </div>
              <div class="p-6 space-y-4">
                <div class="flex flex-wrap gap-3 text-xs text-slate-500 uppercase tracking-[0.3em]">
                  <span>{{ formatDate(post.published_at) }}</span>
                  <span v-if="post.metadata?.category" class="inline-flex items-center gap-2 text-amber-500 normal-case tracking-normal font-medium">
                    <i class="bi bi-tag"></i>
                    {{ post.metadata.category }}
                  </span>
                </div>
                <h3 class="text-2xl font-semibold text-[#254a29] group-hover:text-[#f49926] transition">
                  {{ post.title }}
                </h3>
                <p class="text-slate-600 leading-relaxed">{{ post.excerpt }}</p>
                <div class="flex items-center justify-between text-sm text-slate-500">
                  <span v-if="post.author">Par {{ post.author.name }}</span>
                  <span v-if="post.metadata?.reading_time">{{ post.metadata.reading_time }} min de lecture</span>
                </div>
                <Link
                  :href="route('blog.show', post.slug)"
                  class="inline-flex items-center text-[#f49926] font-semibold"
                >
                  Explorer l'article
                  <i class="bi bi-arrow-right ml-2"></i>
                </Link>
              </div>
            </article>

            <div v-if="posts.links.length" class="pt-4 border-t border-slate-100">
              <Pagination :links="posts.links" />
            </div>
          </div>

          <aside class="space-y-8">
            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-6">
              <h4 class="text-lg font-semibold text-[#254a29] mb-4">Articles récents</h4>
              <ul class="space-y-4">
                <li v-for="item in recent" :key="item.id" class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                  <Link :href="route('blog.show', item.slug)" class="text-sm font-semibold text-[#254a29] hover:text-[#f49926] block">
                    {{ item.title }}
                  </Link>
                  <p class="text-xs text-slate-500 mt-1">{{ formatDate(item.published_at) }}</p>
                </li>
              </ul>
            </div>

            <div class="rounded-3xl border border-amber-100 bg-amber-50 p-6">
              <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Newsletter</p>
              <h4 class="text-xl font-semibold text-[#2d2616] mt-3">Conseils exclusifs & coulisses</h4>
              <p class="text-sm text-slate-600 mt-2">Recevez nos actualités produits, promos et alertes production.</p>
              <form class="mt-4 space-y-3">
                <input type="email" placeholder="Votre email" class="w-full rounded-2xl border border-white/70 px-4 py-3 text-sm focus:outline-none" />
                <button type="button" class="w-full rounded-2xl bg-[#254a29] text-white py-3 font-semibold">S'abonner</button>
              </form>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  posts: {
    type: Object,
    required: true
  },
  featured: {
    type: Array,
    default: () => []
  },
  recent: {
    type: Array,
    default: () => []
  },
  categories: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

const search = ref(props.filters.search ?? '')
const selectedCategory = ref(props.filters.category ?? '')

const categoryOptions = computed(() => {
  if (props.categories.length) {
    return props.categories
  }

  return ['Nutrition', 'Production', 'Logistique', 'Bien-être']
})

const applyFilters = () => {
  router.get(route('blog'), {
    search: search.value || undefined,
    category: selectedCategory.value || undefined
  }, {
    preserveState: true,
    replace: true,
    preserveScroll: true
  })
}

const toggleCategory = (category) => {
  if (selectedCategory.value === category) {
    selectedCategory.value = ''
  } else {
    selectedCategory.value = category
  }
  applyFilters()
}

const clearCategory = () => {
  selectedCategory.value = ''
  applyFilters()
}

const formatDate = (value) => {
  if (!value) return ''
  return new Date(value).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}
</script>
