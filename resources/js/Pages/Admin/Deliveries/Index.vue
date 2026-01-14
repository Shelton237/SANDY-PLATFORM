<template>
  <AdminLayout title="Livraisons">
    <Head title="Livraisons" />

    <div class="grid gap-4 md:grid-cols-4 mb-6">
      <article v-for="card in statCards" :key="card.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ card.value }}</p>
      </article>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 mb-6">
      <form class="flex gap-3 items-center" @submit.prevent="applyFilters">
        <select v-model="status" class="px-4 py-2 rounded-2xl border border-slate-200">
          <option value="">Tous les statuts</option>
          <option v-for="(label, value) in statusOptions" :key="value" :value="value">
            {{ label }}
          </option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-2xl bg-[#f49926] text-white font-semibold">
          Filtrer
        </button>
      </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-widest text-slate-500">
          <tr>
            <th class="px-6 py-3 text-left">Commande</th>
            <th class="px-6 py-3 text-left">Client</th>
            <th class="px-6 py-3 text-left">Date</th>
            <th class="px-6 py-3 text-left">Créneau</th>
            <th class="px-6 py-3 text-left">Statut</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="delivery in deliveries.data" :key="delivery.id" class="border-b border-slate-100 last:border-none">
            <td class="px-6 py-4">
              <p class="font-semibold text-[#254a29]">{{ delivery.order?.number }}</p>
              <p class="text-xs text-slate-500">{{ delivery.id }}</p>
            </td>
            <td class="px-6 py-4 text-slate-500">{{ delivery.order?.customer_name }}</td>
            <td class="px-6 py-4 text-slate-500">{{ delivery.scheduled_date || '—' }}</td>
            <td class="px-6 py-4 text-slate-500">{{ delivery.time_window || '—' }}</td>
            <td class="px-6 py-4">
              <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                {{ statusOptions[delivery.status] || delivery.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <Link :href="route('admin.deliveries.show', delivery.id)" class="text-[#f49926] font-semibold">
                Détails
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-slate-100">
        <Pagination :links="deliveries.links" />
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
  deliveries: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  statusOptions: {
    type: Object,
    default: () => ({})
  },
  stats: {
    type: Object,
    default: () => ({})
  }
})

const status = ref(props.filters.status ?? '')

const statCards = computed(() => [
  { label: 'A planifier', value: props.stats.pending ?? 0 },
  { label: 'Planifiées', value: props.stats.scheduled ?? 0 },
  { label: 'En cours', value: props.stats.dispatched ?? 0 },
  { label: 'Livrées (jour)', value: props.stats.delivered_today ?? 0 }
])

const applyFilters = () => {
  router.get(route('admin.deliveries.index'), {
    status: status.value || undefined
  }, {
    preserveState: true,
    replace: true
  })
}
</script>
