<template>
  <AdminLayout title="Blog">
    <Head title="Articles du blog" />

    <section class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Tunnel marketing</p>
          <h1 class="text-3xl font-semibold text-[#254a29]">Contenus pour le front</h1>
          <p class="text-sm text-slate-500 max-w-2xl">
            Chaque article alimente le storytelling public (homepage, blog, campagnes). Centralisez vos récits de production,
            de logistique ou de nutrition pour rassurer les visiteurs avant la commande.
          </p>
        </div>
        <Link :href="route('admin.blog-posts.create')" class="inline-flex items-center gap-2 rounded-2xl bg-[#254a29] px-5 py-3 text-white font-semibold">
          <i class="bi bi-journal-plus"></i>
          Nouvel article
        </Link>
      </div>
    </section>

    <div class="grid gap-4 md:grid-cols-4 mb-8">
      <article v-for="card in statCards" :key="card.label" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ card.value }}</p>
      </article>
    </div>

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm mb-6">
      <form class="grid gap-4 md:grid-cols-4" @submit.prevent="applyFilters">
        <input
          v-model="search"
          type="search"
          placeholder="Titre, extrait..."
          class="rounded-2xl border-slate-200 bg-white px-4 py-2 text-sm focus:border-[#f49926] focus:ring-[#f49926]/30"
        />
        <select
          v-model="status"
          class="rounded-2xl border-slate-200 bg-white px-4 py-2 text-sm focus:border-[#f49926] focus:ring-[#f49926]/30"
        >
          <option value="">Tous les statuts</option>
          <option v-for="(label, value) in statusOptions" :key="value" :value="value">{{ label }}</option>
        </select>
        <select
          v-model="category"
          class="rounded-2xl border-slate-200 bg-white px-4 py-2 text-sm focus:border-[#f49926] focus:ring-[#f49926]/30"
        >
          <option value="">Toutes les catégories</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>
        <div class="flex gap-3">
          <button type="submit" class="flex-1 rounded-2xl bg-[#f49926] px-4 py-2 text-sm font-semibold text-white">
            Appliquer
          </button>
          <button type="button" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm" @click="resetFilters">
            Effacer
          </button>
        </div>
      </form>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-[0.3em] text-slate-500">
          <tr>
            <th class="px-6 py-3 text-left">Article</th>
            <th class="px-6 py-3 text-left">Catégorie</th>
            <th class="px-6 py-3 text-left">Statut</th>
            <th class="px-6 py-3 text-left">Mise en ligne</th>
            <th class="px-6 py-3 text-left">Auteur</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts.data" :key="post.id" class="border-b border-slate-100 last:border-0">
            <td class="px-6 py-4">
              <p class="font-semibold text-[#254a29]">{{ post.title }}</p>
              <p class="text-xs text-slate-500">{{ post.slug }}</p>
            </td>
            <td class="px-6 py-4 text-slate-600">
              {{ post.metadata?.category || 'Non classé' }}
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full text-xs font-semibold" :class="statusChip(post)">
                {{ statusLabel(post) }}
              </span>
            </td>
            <td class="px-6 py-4 text-slate-600">
              {{ formatDate(post.published_at) || 'Brouillon' }}
            </td>
            <td class="px-6 py-4 text-slate-600">
              {{ post.author?.name || 'À définir' }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="inline-flex items-center gap-3 text-sm">
                <Link :href="route('admin.blog-posts.edit', post.id)" class="font-semibold text-[#254a29] hover:text-[#f49926]">
                  Éditer
                </Link>
                <button type="button" class="text-slate-400 hover:text-rose-500" @click="destroyPost(post)">
                  Supprimer
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-slate-100">
        <Pagination :links="posts.links" />
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  posts: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  categories: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({})
  },
  statusOptions: {
    type: Object,
    default: () => ({})
  }
})

const search = ref(props.filters.search ?? '')
const status = ref(props.filters.status ?? '')
const category = ref(props.filters.category ?? '')

const statCards = computed(() => [
  { label: 'Articles', value: props.stats.total ?? 0 },
  { label: 'Publiés', value: props.stats.published ?? 0 },
  { label: 'Brouillons', value: props.stats.drafts ?? 0 },
  { label: 'À la une', value: props.stats.featured ?? 0 }
])

const applyFilters = () => {
  router.get(route('admin.blog-posts.index'), {
    search: search.value || undefined,
    status: status.value || undefined,
    category: category.value || undefined
  }, {
    preserveState: true,
    replace: true
  })
}

const resetFilters = () => {
  search.value = ''
  status.value = ''
  category.value = ''
  applyFilters()
}

const destroyPost = (post) => {
  if (!confirm(`Supprimer "${post.title}" ?`)) {
    return
  }

  router.delete(route('admin.blog-posts.destroy', post.id), {
    preserveScroll: true
  })
}

const statusLabel = (post) => {
  return post.published_at ? 'Publié' : 'Brouillon'
}

const statusChip = (post) => {
  return post.published_at ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'
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
