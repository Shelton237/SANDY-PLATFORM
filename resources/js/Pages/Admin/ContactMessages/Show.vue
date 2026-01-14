<template>
  <AdminLayout :title="`Message ${message.name}`">
    <Head :title="`Message ${message.name}`" />

    <div class="space-y-6">
      <section class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Contact</p>
            <h1 class="text-2xl font-semibold text-[#254a29]">{{ message.name }}</h1>
            <p class="text-sm text-slate-500">{{ message.company || '—' }}</p>
          </div>
          <span
            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
            :class="message.handled_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
          >
            {{ message.handled_at ? 'Traité' : 'En attente' }}
          </span>
        </div>

        <dl class="grid md:grid-cols-3 gap-4 text-sm text-slate-600">
          <div>
            <p class="text-xs uppercase text-slate-400">Email</p>
            <p class="font-semibold text-[#254a29]">{{ message.email }}</p>
          </div>
          <div>
            <p class="text-xs uppercase text-slate-400">Téléphone</p>
            <p class="font-semibold text-[#254a29]">{{ message.phone || '—' }}</p>
          </div>
          <div>
            <p class="text-xs uppercase text-slate-400">Reçu le</p>
            <p class="font-semibold text-[#254a29]">{{ formatDate(message.created_at) }}</p>
          </div>
        </dl>

        <article class="rounded-2xl border border-slate-100 bg-slate-50 p-5 text-sm text-slate-600 leading-relaxed">
          {{ message.message }}
        </article>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6 space-y-4">
        <h2 class="text-lg font-semibold text-[#254a29]">Suivi</h2>
        <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
          <div>
            <label class="text-xs uppercase text-slate-400">Statut</label>
            <select v-model="form.handled" :class="inputClasses">
              <option :value="false">En attente</option>
              <option :value="true">Traité</option>
            </select>
          </div>
          <div>
            <label class="text-xs uppercase text-slate-400">Note interne</label>
            <textarea v-model="form.note" rows="3" :class="inputClasses" placeholder="Ajoutez une note visible par l'équipe"></textarea>
          </div>
          <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="rounded-2xl bg-[#254a29] px-4 py-2 text-white font-semibold hover:bg-[#1d3c22]" :disabled="form.processing">
              Mettre à jour
            </button>
          </div>
        </form>
      </section>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  message: {
    type: Object,
    required: true,
  },
})

const message = props.message
const inputClasses =
  'mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-[#254a29] bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/30'

const form = useForm({
  handled: Boolean(message.handled_at),
  note: message.metadata?.note || '',
})

const submit = () => {
  form.put(route('admin.contact-messages.update', message.id))
}

const formatDate = (value) => {
  if (!value) return '—'
  return new Date(value).toLocaleString('fr-FR', { dateStyle: 'medium', timeStyle: 'short' })
}
</script>
