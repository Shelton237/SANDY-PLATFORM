<template>
  <AdminLayout title="Nouvel article">
    <Head title="Créer un article" />

    <section class="mb-8 rounded-3xl border border-slate-200 bg-gradient-to-r from-[#fef7ee] via-white to-[#f0faf2] p-6 shadow-sm">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Communication</p>
          <h1 class="text-3xl font-semibold text-[#254a29]">Documenter le pipeline Sandy</h1>
          <p class="text-sm text-slate-500 max-w-2xl">
            Ce contenu alimente le front marketing et la crédibilité de l'usine. Décrivez vos process (approvisionnement, production,
            distribution, retail) pour rassurer les prospects.
          </p>
        </div>
        <div class="flex flex-wrap gap-3">
          <span v-for="step in steps" :key="step" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-500">
            {{ step }}
          </span>
        </div>
      </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-3">
      <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <PostForm :form="form" :categories="categories" :tags="tags" submit-label="Publier" :on-submit="submit" />
      </div>

      <aside class="space-y-4">
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-3">
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Aperçu</p>
          <div>
            <p class="text-sm text-slate-500">Catégorie</p>
            <p class="text-lg font-semibold text-[#254a29]">{{ form.category || 'Non définie' }}</p>
          </div>
          <div>
            <p class="text-sm text-slate-500">Tags</p>
            <p class="text-lg font-semibold text-[#254a29]">
              {{ form.tags.length ? form.tags.map((tag) => `#${tag}`).join(', ') : 'Aucun' }}
            </p>
          </div>
          <div>
            <p class="text-sm text-slate-500">Statut</p>
            <p class="text-lg font-semibold text-[#254a29]">{{ form.published_at ? 'Publié' : 'Brouillon' }}</p>
          </div>
        </article>
        <article class="rounded-3xl border border-amber-100 bg-amber-50 p-5 shadow-sm space-y-2">
          <p class="text-xs uppercase tracking-[0.4em] text-amber-600">Astuce</p>
          <p class="text-sm text-amber-900">
            Introduisez toujours le lien avec l'app (ex: comment l'atelier gère un lot, comment la finance suit la production).
          </p>
        </article>
      </aside>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PostForm from './Partials/PostForm.vue'

const props = defineProps({
  post: {
    type: Object,
    required: true
  },
  categories: {
    type: Array,
    default: () => []
  },
  tags: {
    type: Array,
    default: () => []
  }
})

const form = useForm({
  title: props.post.title || '',
  slug: props.post.slug || '',
  excerpt: props.post.excerpt || '',
  body: props.post.body || '',
  cover_image: props.post.cover_image || '',
  category: props.post.category || '',
  reading_time: props.post.reading_time || 4,
  published_at: props.post.published_at || '',
  is_featured: props.post.is_featured || false,
  tags: props.post.tags || []
})

const steps = ['Brief', 'Catégorie', 'Storytelling', 'Publication']

const submit = () => {
  form.post(route('admin.blog-posts.store'))
}
</script>
