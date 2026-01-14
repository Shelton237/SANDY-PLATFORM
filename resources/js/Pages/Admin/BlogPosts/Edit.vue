<template>
  <AdminLayout title="Éditer l'article">
    <Head :title="`Modifier ${form.title || 'article'}`" />

    <section class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Blog</p>
          <h1 class="text-3xl font-semibold text-[#254a29]">{{ form.title || 'Article sans titre' }}</h1>
          <p class="text-sm text-slate-500 max-w-2xl">
            Ce contenu est visible sur le front marketing. Ajustez le récit dès que la réalité terrain évolue : nouveaux fournisseurs,
            nouveaux chiffres d'impact, coulisses de production, etc.
          </p>
        </div>
        <div class="flex flex-wrap gap-3">
          <Link v-if="post.slug" :href="route('blog.show', post.slug)" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-600" target="_blank">
            Voir sur le site
          </Link>
          <button type="button" class="rounded-2xl border border-rose-200 px-4 py-2 text-sm text-rose-600" @click="destroy">
            Supprimer
          </button>
        </div>
      </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-3">
      <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <PostForm :form="form" :categories="categories" :tags="tags" submit-label="Mettre à jour" :on-submit="submit" />
      </div>

      <aside class="space-y-4">
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Statut</p>
              <p class="text-lg font-semibold" :class="form.published_at ? 'text-emerald-600' : 'text-slate-600'">
                {{ form.published_at ? 'Publié' : 'Brouillon' }}
              </p>
            </div>
            <span class="rounded-full bg-amber-50 px-4 py-1 text-xs font-semibold text-amber-600">
              {{ form.category || 'Non classé' }}
            </span>
          </div>
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Auteur</p>
            <p class="text-lg font-semibold text-[#254a29]">{{ post.author?.name || 'Non assigné' }}</p>
          </div>
          <p class="text-sm text-slate-500">Dernière mise à jour : {{ formatDate(post.updated_at) }}</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-2">
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Check rapide</p>
          <ul class="space-y-2 text-sm text-slate-600">
            <li>✔️ Couverture renseignée</li>
            <li>✔️ Tags : {{ form.tags.length }}</li>
            <li>✔️ Temps de lecture : {{ form.reading_time || '—' }} min</li>
          </ul>
        </article>
      </aside>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
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
  reading_time: props.post.reading_time || '',
  published_at: props.post.published_at || '',
  is_featured: props.post.is_featured || false,
  tags: props.post.tags || []
})

const submit = () => {
  form.put(route('admin.blog-posts.update', props.post.id))
}

const destroy = () => {
  if (!confirm('Supprimer cet article ?')) {
    return
  }

  router.delete(route('admin.blog-posts.destroy', props.post.id))
}

const formatDate = (value) => {
  if (!value) return '—'
  return new Date(value).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}
</script>
