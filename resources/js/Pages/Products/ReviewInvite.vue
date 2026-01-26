<template>
  <AppLayout>
    <SeoHead
      :title="seoTitle"
      :description="seoDescription"
      :image="product.image"
    />

    <section class="bg-gradient-to-br from-[#fef7ee] via-white to-[#f2faf0] border-b border-orange-100">
      <div class="container mx-auto px-4 py-12 lg:py-16 space-y-10">
        <header class="space-y-4 text-center max-w-3xl mx-auto">
          <p class="text-xs uppercase tracking-[0.4em] text-orange-500">Inviter un client</p>
          <h1 class="text-4xl font-semibold text-[#254a29]">Collectez des retours authentiques sur {{ product.name }}</h1>
          <p class="text-slate-500">
            Partagez ce lien sécurisé pour que vos ambassadeurs, distributeurs ou clients finaux racontent comment ils consomment ce jus.
          </p>
        </header>

        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm p-6 flex flex-wrap items-center gap-4">
          <div class="flex-1 min-w-[240px]">
            <p class="text-xs uppercase text-slate-500">Lien à partager</p>
            <p class="text-sm text-slate-500 mt-1 break-all">{{ shareLink }}</p>
          </div>
          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-2xl bg-[#254a29] text-white font-semibold px-4 py-3 hover:bg-[#1f3d22] transition"
            @click="copyLink"
          >
            <i class="bi bi-link-45deg text-lg"></i>
            {{ copyStatus }}
          </button>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
          <article class="rounded-3xl border border-slate-100 bg-white p-6 space-y-5">
            <div class="rounded-2xl overflow-hidden border border-slate-100">
              <img
                :src="product.image || placeholderImage"
                :alt="product.name"
                class="w-full h-64 object-cover"
              />
            </div>
            <div class="space-y-2">
              <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Produit</p>
              <h2 class="text-3xl font-semibold text-[#254a29]">{{ product.name }}</h2>
              <p class="text-slate-500">{{ product.tagline || product.description }}</p>
            </div>
            <div class="rounded-2xl bg-[#fef4e7] border border-[#f9d9b1] px-4 py-3 text-sm text-[#a95407]">
              Ce formulaire est public. Toute personne disposant du lien peut partager son expérience sans créer de compte.
            </div>
          </article>

          <article class="rounded-3xl border border-slate-100 bg-white shadow-lg p-6">
            <h3 class="text-2xl font-semibold text-[#254a29]">Partager mon expérience</h3>
            <p class="text-sm text-slate-500 mt-1">
              Parlez-nous de votre moment préféré avec {{ product.name }}. L’équipe Sandy lit chaque retour pour améliorer ses recettes.
            </p>

            <div
              v-if="flash.success"
              class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
            >
              {{ flash.success }}
            </div>

            <form class="mt-5 space-y-4" @submit.prevent="submitReview">
              <div>
                <label class="text-xs uppercase text-slate-500">Votre note</label>
                <div class="flex items-center gap-2 mt-2">
                  <button
                    v-for="note in maxRating"
                    :key="`rating-${note}`"
                    type="button"
                    class="text-3xl transition"
                    :class="note <= reviewForm.rating ? 'text-[#f49926]' : 'text-slate-300'"
                    @click="reviewForm.rating = note"
                  >
                    <i :class="['bi', note <= reviewForm.rating ? 'bi-star-fill' : 'bi-star']"></i>
                    <span class="sr-only">Choisir {{ note }} {{ note > 1 ? 'étoiles' : 'étoile' }}</span>
                  </button>
                </div>
                <p v-if="reviewForm.errors.rating" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.rating }}</p>
              </div>

              <div>
                <label class="text-xs uppercase text-slate-500">Commentaire</label>
                <textarea
                  v-model="reviewForm.comment"
                  rows="5"
                  :class="textareaClasses"
                  placeholder="Texture, goût, association favorite, moment de consommation..."
                ></textarea>
                <p v-if="reviewForm.errors.comment" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.comment }}</p>
              </div>

              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="text-xs uppercase text-slate-500">Prénom / Nom</label>
                  <input v-model="reviewForm.author_name" type="text" :class="inputClasses" placeholder="Ex : Mireille S." />
                  <p v-if="reviewForm.errors.author_name" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.author_name }}</p>
                </div>
                <div>
                  <label class="text-xs uppercase text-slate-500">Email (optionnel)</label>
                  <input v-model="reviewForm.author_email" type="email" :class="inputClasses" placeholder="Pour vous recontacter si besoin" />
                  <p v-if="reviewForm.errors.author_email" class="text-xs text-rose-500 mt-1">{{ reviewForm.errors.author_email }}</p>
                </div>
              </div>

              <button
                type="submit"
                class="w-full inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-3 font-semibold text-white hover:bg-[#f28700] transition disabled:opacity-60"
                :disabled="reviewForm.processing"
              >
                <i class="bi bi-chat-quote mr-2"></i>
                Envoyer mon avis
              </button>
            </form>
          </article>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SeoHead from '@/Components/Common/SeoHead.vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  shareLink: {
    type: String,
    required: true
  }
})

const page = usePage()
const flash = computed(() => page.props.flash ?? {})
const placeholderImage = '/images/catalog/placeholder.jpg'
const seoTitle = computed(() => `Donnez votre avis sur ${props.product.name}`)
const seoDescription = computed(() => props.product.description || props.product.tagline || 'Partagez votre expérience Sandy Juice.')

const maxRating = 5
const reviewForm = useForm({
  author_name: '',
  author_email: '',
  rating: maxRating,
  comment: ''
})

const copyStatus = ref('Copier le lien')
let copyTimeout

const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(props.shareLink)
    copyStatus.value = 'Lien copié !'
  } catch (error) {
    copyStatus.value = 'Impossible de copier'
    console.error(error)
  } finally {
    clearTimeout(copyTimeout)
    copyTimeout = setTimeout(() => {
      copyStatus.value = 'Copier le lien'
    }, 3000)
  }
}

const inputClasses =
  'w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-[#254a29] bg-white focus:border-[#f49926] focus:ring-2 focus:ring-[#f49926]/20 placeholder:text-slate-400'
const textareaClasses = `${inputClasses} resize-none`

const submitReview = () => {
  reviewForm.post(route('products.reviews.store', props.product.slug), {
    preserveScroll: true,
    onSuccess: () => {
      reviewForm.reset('comment')
      reviewForm.rating = maxRating
    }
  })
}
</script>
