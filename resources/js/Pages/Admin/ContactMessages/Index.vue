<template>
  <AdminLayout title="Messages de contact">
    <Head title="Messages de contact" />

    <section class="grid gap-4 md:grid-cols-4 mb-6">
      <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Total</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ messages.total }}</p>
      </article>
      <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">En attente</p>
        <p class="mt-3 text-2xl font-semibold text-[#b45309]">{{ pendingCount }}</p>
      </article>
      <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Traités</p>
        <p class="mt-3 text-2xl font-semibold text-[#059669]">{{ handledCount }}</p>
      </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
      <header class="p-6 border-b border-slate-100 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Pipeline contact</p>
          <h1 class="text-2xl font-semibold text-[#254a29]">Messages reçus</h1>
        </div>
        <div class="flex flex-wrap gap-3">
          <input
            v-model="localFilters.search"
            type="search"
            placeholder="Rechercher (nom, email, message)"
            :class="[inputClasses, 'w-64']"
            @input="debouncedFilter"
          />
          <select v-model="localFilters.status" :class="[inputClasses, 'w-40']" @change="applyFilters">
            <option value="">Tous</option>
            <option value="pending">En attente</option>
            <option value="handled">Traités</option>
          </select>
        </div>
      </header>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="text-left text-slate-500 uppercase text-xs tracking-widest border-b border-slate-100">
            <tr>
              <th class="py-3 px-6">Client</th>
              <th class="py-3 px-6">Coordonnées</th>
              <th class="py-3 px-6">Message</th>
              <th class="py-3 px-6">Statut</th>
              <th class="py-3 px-6 text-right">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in messages.data" :key="item.id" class="border-b border-slate-50">
              <td class="py-4 px-6">
                <p class="font-semibold text-[#254a29]">{{ item.name }}</p>
                <p class="text-xs text-slate-400">{{ item.company || '—' }}</p>
              </td>
              <td class="py-4 px-6 text-slate-600">
                <p>{{ item.email }}</p>
                <p class="text-xs text-slate-400">{{ item.phone || '—' }}</p>
              </td>
              <td class="py-4 px-6 text-slate-600">
                <p class="line-clamp-2">{{ item.message }}</p>
              </td>
              <td class="py-4 px-6">
                <span
                  class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                  :class="item.handled_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                >
                  {{ item.handled_at ? 'Traité' : 'En attente' }}
                </span>
              </td>
              <td class="py-4 px-6 text-right">
                <Link :href="route('admin.contact-messages.show', item.id)" class="inline-flex items-center gap-1 text-[#f49926] font-semibold">
                  Voir
                  <i class="bi bi-arrow-up-right"></i>
                </Link>
              </td>
            </tr>
            <tr v-if="messages.data.length === 0">
              <td colspan="5" class="text-center py-10 text-slate-400">Aucun message trouvé.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <footer class="p-6 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <span>Page {{ messages.current_page }} / {{ messages.last_page }}</span>
        <div class="space-x-3">
          <button :class="secondaryButtonClasses" :disabled="!messages.prev_page_url" @click="visit(messages.prev_page_url)">Précédent</button>
          <button :class="secondaryButtonClasses" :disabled="!messages.next_page_url" @click="visit(messages.next_page_url)">Suivant</button>
        </div>
      </footer>
    </section>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, reactive } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  messages: {
    type: Object,
    default: () => ({}),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
})

const messages = computed(() => props.messages ?? { data: [] })
const localFilters = reactive({
  search: props.filters?.search ?? '',
  status: props.filters?.status ?? '',
})

const pendingCount = computed(() => messages.value.data.filter((m) => !m.handled_at).length)
const handledCount = computed(() => messages.value.data.filter((m) => m.handled_at).length)

const inputClasses =
  'rounded-2xl border border-slate-200 px-4 py-2 text-sm text-[#254a29] bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30'
const secondaryButtonClasses =
  'rounded-2xl border border-slate-200 px-3 py-1 text-xs uppercase tracking-wide text-slate-500 disabled:opacity-50'

let timer = null
const debouncedFilter = () => {
  clearTimeout(timer)
  timer = setTimeout(applyFilters, 300)
}

const applyFilters = () => {
  router.get(route('admin.contact-messages.index'), localFilters, {
    preserveState: true,
    replace: true,
  })
}

const visit = (url) => {
  if (!url) return
  router.visit(url, { preserveState: true, preserveScroll: true })
}
</script>
