<template>
  <form class="space-y-6" @submit.prevent="onSubmit">
    <div class="grid gap-6 md:grid-cols-2">
      <div>
        <label class="form-label">Titre</label>
        <input v-model="form.title" type="text" class="form-input" placeholder="Ex: De la récolte à la bouteille" />
        <ErrorText :message="form.errors.title" />
      </div>
      <div>
        <label class="form-label">Slug (URL)</label>
        <input v-model="form.slug" type="text" class="form-input" placeholder="recolte-bouteille" />
        <ErrorText :message="form.errors.slug" />
      </div>
    </div>

    <div>
      <label class="form-label">Extrait</label>
      <textarea v-model="form.excerpt" rows="3" class="form-textarea" placeholder="Résumé affiché sur le front"></textarea>
      <ErrorText :message="form.errors.excerpt" />
    </div>

    <div>
      <label class="form-label">Image de couverture (URL ou chemin)</label>
      <input v-model="form.cover_image" type="text" class="form-input" placeholder="/images/blog/story.jpg" />
      <ErrorText :message="form.errors.cover_image" />
    </div>

    <div class="grid gap-6 md:grid-cols-3">
      <div>
        <label class="form-label">Catégorie</label>
        <input v-model="form.category" list="blog-categories" class="form-input" placeholder="Production" />
        <datalist id="blog-categories">
          <option v-for="category in categories" :key="category" :value="category"></option>
        </datalist>
        <ErrorText :message="form.errors.category" />
      </div>
      <div>
        <label class="form-label">Temps de lecture (min)</label>
        <input v-model.number="form.reading_time" type="number" min="1" max="60" class="form-input" placeholder="4" />
        <ErrorText :message="form.errors.reading_time" />
      </div>
      <div>
        <label class="form-label">Publication</label>
        <input v-model="form.published_at" type="datetime-local" class="form-input" />
        <ErrorText :message="form.errors.published_at" />
      </div>
    </div>

    <div>
      <label class="form-label">Tags</label>
      <div class="flex gap-3">
        <input v-model="tagDraft" type="text" class="form-input flex-1" placeholder="ajouter un tag" @keyup.enter.prevent="addTag" />
        <button type="button" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm" @click="addTag">
          Ajouter
        </button>
      </div>
      <p class="text-xs text-slate-500 mt-2">Suggestions : <button
          v-for="suggestion in tags"
          :key="suggestion"
          type="button"
          class="mr-2 text-[#f49926] hover:underline"
          @click="addTag(suggestion)"
        >
          #{{ suggestion }}
        </button>
      </p>
      <div class="mt-3 flex flex-wrap gap-2">
        <span
          v-for="tag in form.tags"
          :key="tag"
          class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-sm text-emerald-700"
        >
          #{{ tag }}
          <button type="button" class="text-xs text-emerald-500 hover:text-rose-500" @click="removeTag(tag)">
            ×
          </button>
        </span>
      </div>
      <ErrorText :message="form.errors.tags" />
    </div>

    <div class="flex items-center gap-3">
      <input id="is_featured" v-model="form.is_featured" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#f49926]" />
      <label for="is_featured" class="text-sm text-slate-600">Mettre en avant sur la page d'accueil</label>
    </div>

    <div>
      <label class="form-label">Contenu</label>
      <textarea v-model="form.body" rows="12" class="form-textarea" placeholder="Votre récit..."></textarea>
      <ErrorText :message="form.errors.body" />
    </div>

    <div class="flex items-center justify-end gap-3">
      <Link :href="route('admin.blog-posts.index')" class="text-sm text-slate-500 hover:text-[#254a29]">Annuler</Link>
      <button type="submit" class="rounded-2xl bg-[#254a29] px-6 py-3 text-white font-semibold">
        {{ submitLabel }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineComponent, ref } from 'vue'

const props = defineProps({
  form: {
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
  },
  submitLabel: {
    type: String,
    default: 'Enregistrer'
  },
  onSubmit: {
    type: Function,
    required: true
  }
})

const tagDraft = ref('')
const ErrorText = defineComponent({
  props: {
    message: {
      type: String,
      default: ''
    }
  },
  template: `<p v-if="message" class="mt-1 text-xs text-rose-500">{{ message }}</p>`
})

const addTag = (value) => {
  const tag = (value || tagDraft.value).trim()
  if (!tag) {
    return
  }
  if (!props.form.tags.includes(tag)) {
    props.form.tags.push(tag)
  }
  tagDraft.value = ''
}

const removeTag = (tag) => {
  props.form.tags = props.form.tags.filter((item) => item !== tag)
}

const onSubmit = () => {
  props.onSubmit()
}
</script>

<style scoped>
.form-label {
  @apply mb-2 block text-sm font-semibold text-slate-600;
}

.form-input {
  @apply w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-[#f49926] focus:ring-[#f49926]/30;
}

.form-textarea {
  @apply w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-[#f49926] focus:ring-[#f49926]/30;
}
</style>
