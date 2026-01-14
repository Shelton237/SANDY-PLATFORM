<template>
  <AppLayout>
    <Head :title="post.title" />

    <article class="bg-white">
      <div class="relative bg-slate-900">
        <img :src="post.cover_image || '/images/placeholder-blog.jpg'" :alt="post.title" class="w-full h-[420px] object-cover opacity-80" />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/60 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 px-4 pb-10">
          <div class="container mx-auto max-w-5xl text-white space-y-4">
            <Link :href="route('blog')" class="inline-flex items-center text-sm text-white/70 hover:text-white">
              <i class="bi bi-arrow-left mr-2"></i>
              Retour au blog
            </Link>
            <p class="text-xs uppercase tracking-[0.4em] text-amber-300">{{ categoryLabel }}</p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">{{ post.title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-white/80">
              <span v-if="post.author">Par {{ post.author.name }}</span>
              <span>{{ formatDate(post.published_at) }}</span>
              <span v-if="post.metadata?.reading_time">{{ post.metadata.reading_time }} min</span>
            </div>
          </div>
        </div>
      </div>

      <div class="container mx-auto px-4 py-12 max-w-5xl grid gap-10 lg:grid-cols-[2fr,1fr]">
        <div class="prose prose-lg max-w-none">
          <p v-if="post.excerpt" class="text-xl text-[#254a29] font-semibold leading-relaxed">{{ post.excerpt }}</p>

          <div class="space-y-6 mt-6">
            <p v-for="(paragraph, index) in contentSections" :key="index" class="text-slate-700 leading-8">
              {{ paragraph }}
            </p>
          </div>

          <div class="mt-10 rounded-3xl border border-amber-100 bg-amber-50 p-6 space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-amber-600">Envie de goûter ?</p>
            <h3 class="text-2xl font-semibold text-[#2d2616]">Commandez nos recettes Sandy Juice</h3>
            <p class="text-sm text-slate-600">Passez directement vers le catalogue et planifiez la livraison À Yaoundé sous 24h.</p>
            <div class="flex flex-wrap gap-3">
              <Link :href="route('products')" class="inline-flex items-center px-5 py-3 rounded-2xl bg-[#254a29] text-white font-semibold">
                Voir le catalogue
                <i class="bi bi-arrow-right-short text-xl ml-2"></i>
              </Link>
              <Link :href="route('contact')" class="inline-flex items-center px-5 py-3 rounded-2xl border border-[#254a29]/20 text-[#254a29] font-semibold">
                Contacter l'équipe
              </Link>
            </div>
          </div>
        </div>

        <aside class="space-y-8">
          <div class="rounded-3xl border border-slate-100 bg-slate-50 p-6">
            <h4 class="text-lg font-semibold text-[#254a29] mb-4">Informations clés</h4>
            <ul class="space-y-3 text-sm text-slate-600">
              <li class="flex items-center gap-3">
                <i class="bi bi-calendar text-[#f49926]"></i>
                <span>Publié le {{ formatDate(post.published_at) }}</span>
              </li>
              <li v-if="post.metadata?.category" class="flex items-center gap-3">
                <i class="bi bi-tag text-[#f49926]"></i>
                <span>Catégorie : {{ post.metadata.category }}</span>
              </li>
              <li v-if="tags.length" class="flex items-start gap-3">
                <i class="bi bi-hash text-[#f49926] mt-1"></i>
                <div class="flex flex-wrap gap-2">
                  <span v-for="tag in tags" :key="tag" class="px-3 py-1 rounded-full bg-white text-[#254a29] border border-slate-200">
                    {{ tag }}
                  </span>
                </div>
              </li>
            </ul>
          </div>

          <div v-if="related.length" class="rounded-3xl border border-slate-100 bg-white p-6 space-y-4">
            <h4 class="text-lg font-semibold text-[#254a29]">À lire aussi</h4>
            <article v-for="item in related" :key="item.id" class="flex gap-3 border-b border-slate-100 pb-4 last:border-0 last:pb-0">
              <img
                :src="item.cover_image || '/images/placeholder-blog.jpg'"
                :alt="item.title"
                class="w-24 h-24 rounded-2xl object-cover flex-shrink-0"
                loading="lazy"
              />
              <div>
                <p class="text-xs text-slate-500 uppercase tracking-[0.4em]">{{ formatDate(item.published_at) }}</p>
                <Link :href="route('blog.show', item.slug)" class="text-sm font-semibold text-[#254a29] hover:text-[#f49926]">
                  {{ item.title }}
                </Link>
                <p class="text-xs text-slate-500 mt-1">{{ item.excerpt }}</p>
              </div>
            </article>
          </div>
        </aside>
      </div>
    </article>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  post: {
    type: Object,
    required: true
  },
  related: {
    type: Array,
    default: () => []
  }
})

const categoryLabel = computed(() => props.post.metadata?.category ?? 'Blog Sandy Juice')
const tags = computed(() => props.post.metadata?.tags ?? [])

const contentSections = computed(() => {
  if (!props.post.body) return []
  return props.post.body.split(/\n{2,}/).map((section) => section.trim()).filter(Boolean)
})

const formatDate = (value) => {
  if (!value) {
    return ''
  }

  return new Date(value).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}
</script>
