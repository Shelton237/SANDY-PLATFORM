<template>
  <form class="space-y-6" @submit.prevent="onSubmit">
    <div class="grid gap-6 md:grid-cols-2">
      <div>
        <label class="mb-1 block text-sm font-semibold text-slate-600">Titre</label>
        <input
          v-model="form.title"
          type="text"
          :class="baseInput"
          placeholder="Ex: De la récolte à la bouteille"
        />
        <ErrorText :message="form.errors.title" />
      </div>
      <div>
        <label class="mb-1 block text-sm font-semibold text-slate-600">Slug (URL)</label>
        <input
          v-model="form.slug"
          type="text"
          :class="baseInput"
          placeholder="recolte-bouteille"
        />
        <ErrorText :message="form.errors.slug" />
      </div>
    </div>

    <div>
      <label class="mb-1 block text-sm font-semibold text-slate-600">Extrait</label>
      <RichTextEditor
        v-model="form.excerpt"
        placeholder="Résumé affiché sur le front"
        min-height="140px"
      />
      <ErrorText :message="form.errors.excerpt" />
    </div>

    <div>
      <label class="mb-1 block text-sm font-semibold text-slate-600">Image de couverture</label>
      <div class="space-y-3">
        <div class="flex flex-col gap-3 sm:flex-row">
          <input
            v-model="form.cover_image"
            type="text"
            :class="baseInput"
            class="flex-1"
            placeholder="https:// ou /storage/blog-covers/..."
          />
          <button
            type="button"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm hover:border-slate-300"
            :disabled="coverUploading"
            @click="triggerCoverSelect"
          >
            <i class="bi bi-upload mr-2"></i>
            <span v-if="coverUploading">Import...</span>
            <span v-else>Importer</span>
          </button>
        </div>
        <input
          ref="coverInputRef"
          type="file"
          class="hidden"
          accept="image/*"
          @change="handleCoverUpload"
        />
        <p v-if="coverError" class="text-sm text-rose-500">{{ coverError }}</p>
        <div
          v-if="coverPreview || form.cover_image"
          class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"
        >
          <img :src="coverPreview || form.cover_image" alt="Cover preview" class="w-full max-h-[320px] bg-white object-contain" />
          <div class="flex items-center justify-between px-4 py-2 text-sm text-slate-600">
            <span>Prévisualisation</span>
            <button type="button" class="text-rose-600 hover:underline" @click="removeCover">
              Supprimer
            </button>
          </div>
        </div>
      </div>
      <ErrorText :message="form.errors.cover_image" />
    </div>

    <div class="grid gap-6 md:grid-cols-3">
      <div>
        <label class="mb-1 block text-sm font-semibold text-slate-600">Catégorie</label>
        <input
          v-model="form.category"
          list="blog-categories"
          :class="baseInput"
          placeholder="Production"
        />
        <datalist id="blog-categories">
          <option v-for="category in categories" :key="category" :value="category"></option>
        </datalist>
        <ErrorText :message="form.errors.category" />
      </div>
      <div>
        <label class="mb-1 block text-sm font-semibold text-slate-600">Temps de lecture (min)</label>
        <input
          v-model.number="form.reading_time"
          type="number"
          min="1"
          max="60"
          :class="baseInput"
          placeholder="4"
        />
        <ErrorText :message="form.errors.reading_time" />
      </div>
      <div>
        <label class="mb-1 block text-sm font-semibold text-slate-600">Publication</label>
        <input
          v-model="form.published_at"
          type="datetime-local"
          :class="baseInput"
        />
        <ErrorText :message="form.errors.published_at" />
      </div>
    </div>

    <div>
      <label class="mb-1 block text-sm font-semibold text-slate-600">Tags</label>
      <div class="flex gap-3">
        <input
          v-model="tagDraft"
          type="text"
          class="flex-1"
          :class="baseInput"
          placeholder="ajouter un tag"
          @keyup.enter.prevent="addTag"
        />
        <button
          type="button"
          class="rounded-2xl border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:border-slate-400"
          @click="addTag"
        >
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
      <input
        id="is_featured"
        v-model="form.is_featured"
        type="checkbox"
        class="h-4 w-4 rounded border-slate-300 text-[#f49926] focus:ring-[#f49926]/40"
      />
      <label for="is_featured" class="text-sm text-slate-600">Mettre en avant sur la page d'accueil</label>
    </div>

    <div>
      <label class="mb-1 block text-sm font-semibold text-slate-600">Contenu</label>
      <RichTextEditor
        v-model="form.body"
        placeholder="Votre récit..."
        min-height="260px"
      />
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
import { defineComponent, onMounted, ref } from 'vue'
import RichTextEditor from '@/Components/RichTextEditor.vue'

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
const baseInput = 'block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-[#f49926] focus:ring-[#f49926]/30 focus:outline-none'
const form = props.form
const coverInputRef = ref(null)
const coverUploading = ref(false)
const coverError = ref('')
const coverPreview = ref('')
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

const resolveRouteHelper = () => {
  if (typeof window !== 'undefined' && typeof window.route === 'function') {
    return window.route
  }
  if (typeof route === 'function') {
    return route
  }
  return null
}

const ziggyRoute = resolveRouteHelper()
const uploadCoverUrl = ziggyRoute ? ziggyRoute('admin.blog-posts.cover.upload') : '/admin/blog-posts/cover/upload'
const csrfToken =
  typeof document !== 'undefined'
    ? document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
    : ''

const setCoverPreview = (value) => {
  if (coverPreview.value && coverPreview.value.startsWith('blob:')) {
    URL.revokeObjectURL(coverPreview.value)
  }
  coverPreview.value = value || ''
}

const handleCoverUpload = async (event) => {
  const file = event?.target?.files?.[0]

  if (!file) {
    return
  }

  coverUploading.value = true
  coverError.value = ''
  setCoverPreview(URL.createObjectURL(file))

  const formData = new FormData()
  formData.append('cover', file)

  try {
    const response = await fetch(uploadCoverUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin',
      body: formData
    })

    if (!response.ok) {
      let message = 'Import impossible. Réessayez.'
      try {
        const errorPayload = await response.json()
        message = errorPayload?.message ?? message
      } catch (error) {
        // ignore parse errors
      }
      throw new Error(message)
    }

    const payload = await response.json()
    if (payload?.url) {
      form.cover_image = payload.url
      setCoverPreview(payload.url)
    }
  } catch (error) {
    coverError.value = error?.message ?? 'Import impossible. Réessayez.'
  } finally {
    coverUploading.value = false
    if (event?.target) {
      event.target.value = ''
    }
  }
}

const removeCover = () => {
  form.cover_image = ''
  setCoverPreview('')
}

const triggerCoverSelect = () => {
  coverInputRef.value?.click()
}

onMounted(() => {
  if (form.cover_image) {
    setCoverPreview(form.cover_image)
  }
})
</script>
