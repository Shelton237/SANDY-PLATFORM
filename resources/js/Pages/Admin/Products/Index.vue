<template>
  <AdminLayout title="Produits">
    <Head title="Produits" />

    <div class="mb-6 grid gap-4 md:grid-cols-4">
      <article v-for="stat in statCards" :key="stat.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ stat.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ stat.value }}</p>
      </article>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 mb-6">
      <div class="flex flex-wrap gap-3 justify-between items-center">
        <form class="flex flex-wrap gap-3" @submit.prevent="applyFilters">
          <input
            v-model="search"
            type="search"
            placeholder="Rechercher une recette"
            class="px-4 py-2 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
          />
          <select v-model="status" class="px-4 py-2 rounded-2xl border border-slate-200">
            <option value="">Tous les statuts</option>
            <option v-for="(label, value) in statuses" :value="value" :key="value">
              {{ label }}
            </option>
          </select>
          <select v-model="category" class="px-4 py-2 rounded-2xl border border-slate-200">
            <option value="">Toutes les categories</option>
            <option v-for="cat in categories" :key="cat.slug" :value="cat.slug">
              {{ cat.name }}
            </option>
          </select>
          <button type="submit" class="px-4 py-2 rounded-2xl bg-[#f49926] text-white font-semibold">
            Filtrer
          </button>
        </form>
        <Link :href="route('admin.products.create')" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-[#254a29] text-white">
          <i class="bi bi-plus-circle"></i>
          Nouvelle recette
        </Link>
      </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-widest text-slate-500">
          <tr>
            <th class="text-left px-6 py-3">Produit</th>
            <th class="text-left px-6 py-3">Categorie</th>
            <th class="text-left px-6 py-3">Prix</th>
            <th class="text-left px-6 py-3">Statut</th>
            <th class="text-left px-6 py-3">Stock</th>
            <th class="text-right px-6 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products.data" :key="product.id" class="border-b border-slate-100 last:border-none">
            <td class="px-6 py-4">
              <p class="font-semibold text-[#254a29]">{{ product.name }}</p>
              <p class="text-xs text-slate-500">{{ product.slug }}</p>
            </td>
            <td class="px-6 py-4 text-slate-500">{{ product.category }}</td>
            <td class="px-6 py-4 font-semibold text-[#f49926]">{{ formatPrice(product.price) }}</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full text-xs font-semibold" :class="statusChip(product.status)">
                {{ statuses[product.status] || product.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-slate-500">{{ product.stock ?? 0 }}</td>
            <td class="px-6 py-4 text-right">
              <div class="flex justify-end gap-2">
                <Link :href="route('admin.products.edit', product.id)" class="text-[#254a29] hover:text-[#f49926]">
                  Editer
                </Link>
                <Link :href="route('admin.products.show', product.id)" class="text-slate-400 hover:text-[#254a29]">
                  Voir
                </Link>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-slate-100">
        <Pagination :links="products.links" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  products: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  stats: {
    type: Object,
    default: () => ({})
  },
  categories: {
    type: Array,
    default: () => []
  },
  statuses: {
    type: Object,
    default: () => ({})
  }
})

const search = ref(props.filters.search ?? '')
const status = ref(props.filters.status ?? '')
const category = ref(props.filters.category ?? '')

const statCards = computed(() => [
  { label: 'Total', value: props.stats.total ?? 0 },
  { label: 'Publies', value: props.stats.published ?? 0 },
  { label: 'Brouillons', value: props.stats.drafts ?? 0 },
  { label: 'Editions limitees', value: props.stats.limited ?? 0 }
])

const applyFilters = () => {
  router.get(route('admin.products.index'), {
    search: search.value || undefined,
    status: status.value || undefined,
    category: category.value || undefined
  }, {
    preserveState: true,
    replace: true
  })
}

const formatPrice = (value) => {
  const number = Number(value) || 0
  return `${number.toFixed(0)} FCFA`
}

const statusChip = (value) => {
  switch (value) {
    case 'published':
      return 'bg-emerald-50 text-emerald-700'
    case 'draft':
      return 'bg-slate-100 text-slate-500'
    case 'archived':
      return 'bg-orange-50 text-orange-700'
    default:
      return 'bg-slate-100 text-slate-500'
  }
}
</script>
