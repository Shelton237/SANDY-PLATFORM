<template>
  <AppLayout>
    <Head title="Contact" />

    <section class="bg-gradient-to-br from-[#f2faf0] via-white to-[#fef7ee] border-b border-orange-50">
      <div class="container mx-auto px-4 py-12 lg:py-16 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-3">
          <p class="text-xs uppercase tracking-[0.4em] text-[#f49926]">Nous écrire</p>
          <h1 class="text-3xl lg:text-4xl font-semibold text-[#254a29]">Besoin d’un circuit jus sur-mesure ?</h1>
          <p class="text-slate-500">
            Décrivez votre besoin (approvisionnement, production, distribution). Nous vous rappelons dans l’heure pour co-construire le pipeline.
          </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
          <article class="rounded-3xl border border-slate-100 bg-white shadow-sm p-6 space-y-5">
            <div class="grid sm:grid-cols-2 gap-4">
              <div v-for="info in infos" :key="info.label" class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ info.label }}</p>
                <p class="mt-2 text-lg font-semibold text-[#254a29]">{{ info.value }}</p>
                <p class="text-xs text-slate-500">{{ info.caption }}</p>
              </div>
            </div>
            <div class="flex flex-wrap gap-3 pt-2">
              <a
                v-for="social in socials"
                :key="social.label"
                :href="social.url"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm text-[#254a29] hover:border-[#f49926] hover:text-[#f49926] transition"
              >
                <i :class="['bi', social.icon]"></i>
                <div class="flex flex-col leading-tight">
                  <span class="font-semibold">{{ social.label }}</span>
                  <span class="text-xs text-slate-500">{{ social.handle }}</span>
                </div>
              </a>
            </div>
            <div class="rounded-2xl bg-[#fef4e7] border border-[#f9d9b1] p-5 text-sm text-[#b45309]">
              Nous orchestrons l’intégralité du circuit (approvisionnement → stockage → production → commercialisation → livraison). Expliquez-nous votre étape actuelle.
            </div>
          </article>

          <article class="rounded-3xl border border-slate-100 bg-white shadow-xl p-6">
            <form class="space-y-4" @submit.prevent="submit">
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="text-xs uppercase text-slate-500">Nom complet</label>
                  <input v-model="form.name" type="text" :class="inputClasses" />
                  <p v-if="form.errors.name" :class="errorClass">{{ form.errors.name }}</p>
                </div>
                <div>
                  <label class="text-xs uppercase text-slate-500">Société</label>
                  <input v-model="form.company" type="text" :class="inputClasses" placeholder="Optionnel" />
                  <p v-if="form.errors.company" :class="errorClass">{{ form.errors.company }}</p>
                </div>
              </div>

              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="text-xs uppercase text-slate-500">Email</label>
                  <input v-model="form.email" type="email" :class="inputClasses" />
                  <p v-if="form.errors.email" :class="errorClass">{{ form.errors.email }}</p>
                </div>
                <div>
                  <label class="text-xs uppercase text-slate-500">Téléphone</label>
                  <input v-model="form.phone" type="text" :class="inputClasses" placeholder="+241 ..." />
                  <p v-if="form.errors.phone" :class="errorClass">{{ form.errors.phone }}</p>
                </div>
              </div>

              <div>
                <label class="text-xs uppercase text-slate-500">Message</label>
                <textarea v-model="form.message" rows="5" :class="textareaClasses" placeholder="Décrivez votre besoin (circuit, volumes, délais… )"></textarea>
                <p v-if="form.errors.message" :class="errorClass">{{ form.errors.message }}</p>
              </div>

              <button
                type="submit"
                class="w-full inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-3 text-white font-semibold hover:bg-[#f28700] transition disabled:opacity-50"
                :disabled="form.processing"
              >
                <i class="bi bi-send mr-2"></i>
                Envoyer ma demande
              </button>
            </form>
          </article>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  contactInfo: {
    type: Object,
    default: () => ({}),
  },
  socials: {
    type: Array,
    default: () => [],
  },
})

const infos = [
  { label: 'Email', value: props.contactInfo.email || 'bonjour@sandy-juice.com', caption: 'Support & commandes' },
  { label: 'Téléphone', value: props.contactInfo.phone || '+237 655 69 98 25', caption: 'WhatsApp & appels' },
  { label: 'Atelier', value: props.contactInfo.address || 'Yaoundé', caption: 'Visites sur rendez-vous' },
  { label: 'Horaires', value: props.contactInfo.hours || 'Lun-Sam 7h-19h', caption: 'Dimanche sur demande' },
]

const socials = computed(() => props.socials ?? [])

const form = useForm({
  name: '',
  email: '',
  phone: '',
  company: '',
  message: '',
})

const inputClasses =
  'w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-[#254a29] bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20 placeholder:text-slate-400'
const textareaClasses = `${inputClasses} resize-none`
const errorClass = 'text-xs text-rose-500 mt-1'

const submit = () => {
  form.post(route('contact.send'), {
    onSuccess: () => form.reset('message'),
  })
}
</script>
