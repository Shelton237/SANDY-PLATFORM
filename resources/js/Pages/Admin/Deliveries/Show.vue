<template>
  <AdminLayout title="Livraison">
    <Head title="Livraison" />

    <div class="grid gap-6 lg:grid-cols-2">
      <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Commande</p>
          <h1 class="text-2xl font-semibold text-[#254a29]">{{ delivery.order?.number }}</h1>
          <p class="text-sm text-slate-500">{{ delivery.order?.customer_name }}</p>
        </div>
        <div class="text-sm text-slate-600 space-y-2">
          <p>{{ delivery.address_line1 }} {{ delivery.city }}</p>
          <p>Créneau: {{ delivery.time_window || '—' }}</p>
          <p>Date: {{ delivery.scheduled_date || '—' }}</p>
        </div>
      </section>

      <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Statut</label>
            <select v-model="form.status" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
              <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Date programmée</label>
            <input v-model="form.scheduled_date" type="date" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Créneau horaire</label>
            <input v-model="form.time_window" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="10h-12h" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Coursier</label>
            <input v-model="form.courier_name" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Téléphone coursier</label>
            <input v-model="form.courier_phone" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-600 mb-2 block">Notes</label>
            <textarea v-model="form.notes" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
          </div>
          <button type="submit" class="w-full rounded-2xl bg-[#f49926] text-white py-3 font-semibold flex items-center justify-center gap-2">
            <i class="bi bi-save"></i>
            Mettre à jour
          </button>
        </form>
      </section>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  delivery: {
    type: Object,
    required: true
  },
  statusOptions: {
    type: Object,
    default: () => ({})
  }
})

const form = useForm({
  status: props.delivery.status,
  scheduled_date: props.delivery.scheduled_date,
  time_window: props.delivery.time_window,
  courier_name: props.delivery.courier_name,
  courier_phone: props.delivery.courier_phone,
  vehicle_type: props.delivery.vehicle_type,
  tracking_link: props.delivery.tracking_link,
  notes: props.delivery.notes
})

const submit = () => {
  form.put(route('admin.deliveries.update', props.delivery.id))
}
</script>

