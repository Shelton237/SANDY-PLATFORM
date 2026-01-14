<template>
  <form @submit.prevent="onSubmit" class="space-y-8">
    <section :class="ui.section">
      <div class="flex items-start gap-4">
        <span :class="ui.sectionIcon">
          <i class="bi bi-cup-straw"></i>
        </span>
        <div>
          <p :class="ui.eyebrow">Base</p>
          <h2 class="text-lg font-semibold text-slate-900">Informations produit</h2>
          <p class="text-sm text-slate-500">Definissez le socle du jus presente dans le catalogue.</p>
        </div>
      </div>
      <div class="mt-6 grid gap-5 md:grid-cols-12">
        <div class="md:col-span-6">
          <label :class="ui.label" for="product-name">
            <i class="bi bi-type text-emerald-500"></i>
            Nom
            <span class="text-emerald-500">*</span>
          </label>
          <p :class="ui.hint">Nom public affiche sur la boutique.</p>
          <input
            id="product-name"
            v-model="form.name"
            type="text"
            required
            :class="ui.input"
            placeholder="Nectar mangue gingembre"
          />
        </div>
        <div class="md:col-span-6">
          <label :class="ui.label" for="product-slug">
            <i class="bi bi-link-45deg text-emerald-500"></i>
            Slug
          </label>
          <p :class="ui.hint">Laissez vide pour generer automatiquement.</p>
          <input id="product-slug" v-model="form.slug" type="text" :class="ui.input" placeholder="nectar-mangue" />
        </div>
        <div class="md:col-span-6">
          <label :class="ui.label" for="product-category">
            <i class="bi bi-tags text-emerald-500"></i>
            Categorie
            <span class="text-emerald-500">*</span>
          </label>
          <p :class="ui.hint">Choisissez la famille de jus.</p>
          <template v-if="categoryOptions.length">
            <select id="product-category" v-model="form.category" required :class="ui.select">
              <option value="">Choisir une categorie</option>
              <option v-for="option in categoryOptions" :key="option.slug" :value="option.slug">
                {{ option.name }}
              </option>
            </select>
          </template>
          <template v-else>
            <input
              id="product-category"
              v-model="form.category"
              type="text"
              required
              :class="ui.input"
              placeholder="Defaut"
            />
          </template>
        </div>
        <div class="md:col-span-3">
          <label :class="ui.label" for="product-status">
            <i class="bi bi-flag text-emerald-500"></i>
            Statut
          </label>
          <select id="product-status" v-model="form.status" :class="[ui.select, 'mt-2']">
            <option v-for="(label, value) in statuses" :key="value" :value="value">
              {{ label }}
            </option>
          </select>
        </div>
        <div class="md:col-span-3">
          <label :class="ui.label" for="product-badge">
            <i class="bi bi-award text-emerald-500"></i>
            Badge
          </label>
          <p :class="ui.hint">Ex: best seller, promo.</p>
          <input id="product-badge" v-model="form.badge" type="text" :class="ui.input" placeholder="Best seller" />
        </div>
      </div>
    </section>

    <section :class="ui.section">
      <div class="flex items-start gap-4">
        <span :class="ui.sectionIcon">
          <i class="bi bi-cash-stack"></i>
        </span>
        <div>
          <p :class="ui.eyebrow">Commerce</p>
          <h2 class="text-lg font-semibold text-slate-900">Tarification & disponibilite</h2>
          <p class="text-sm text-slate-500">Suivez vos stocks et tarifs exprimes en FCFA.</p>
        </div>
      </div>
      <div class="mt-6 grid gap-5 md:grid-cols-12">
        <div class="md:col-span-4">
          <label :class="ui.label" for="product-price">
            <i class="bi bi-currency-exchange text-emerald-500"></i>
            Prix (FCFA)
            <span class="text-emerald-500">*</span>
          </label>
          <input
            id="product-price"
            v-model.number="form.price"
            type="number"
            min="0"
            step="0.1"
            required
            :class="ui.input"
          />
        </div>
        <div class="md:col-span-4">
          <label :class="ui.label" for="product-stock">
            <i class="bi bi-box-seam text-emerald-500"></i>
            Stock
          </label>
          <input
            id="product-stock"
            v-model.number="form.stock"
            type="number"
            min="0"
            step="1"
            :class="ui.input"
          />
        </div>
        <div class="md:col-span-4">
          <label :class="ui.label" for="product-size">
            <i class="bi bi-arrows-collapse text-emerald-500"></i>
            Taille / format
          </label>
          <input
            id="product-size"
            v-model="form.size"
            type="text"
            :class="ui.input"
            placeholder="330 ml"
          />
        </div>
        <div class="md:col-span-6">
          <label :class="ui.label" for="product-availability">
            <i class="bi bi-clock text-emerald-500"></i>
            Disponibilite
          </label>
          <p :class="ui.hint">Plage de pressage ou limites.</p>
          <input
            id="product-availability"
            v-model="form.availability"
            type="text"
            :class="ui.input"
            placeholder="Pressage quotidien 6h-12h"
          />
        </div>
        <div class="md:col-span-3">
          <label :class="ui.label" for="product-accent">
            <i class="bi bi-droplet text-emerald-500"></i>
            Accent couleur
          </label>
          <input
            id="product-accent"
            v-model="form.accent"
            type="text"
            :class="ui.input"
            placeholder="emerald, orange..."
          />
        </div>
        <div class="md:col-span-3">
          <label :class="ui.label" for="product-image">
            <i class="bi bi-image text-emerald-500"></i>
            Image principale
          </label>
          <p :class="ui.hint">URL du visuel hero.</p>
          <input
            id="product-image"
            v-model="form.image_path"
            type="text"
            :class="ui.input"
            placeholder="https://..."
          />
        </div>
      </div>
      <div class="mt-6 grid gap-4 md:grid-cols-4">
        <label
          class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm"
        >
          <input v-model="form.is_new" type="checkbox" :class="ui.checkbox" />
          Nouveaute
        </label>
        <label
          class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm"
        >
          <input v-model="form.is_limited" type="checkbox" :class="ui.checkbox" />
          Edition limitee
        </label>
        <div>
          <label :class="ui.label" for="product-energy">Energie (0-10)</label>
          <input
            id="product-energy"
            v-model.number="form.energy_index"
            type="number"
            min="0"
            max="10"
            :class="ui.input"
          />
        </div>
        <div>
          <label :class="ui.label" for="product-calories">Calories</label>
          <input
            id="product-calories"
            v-model.number="form.calories"
            type="number"
            min="0"
            :class="ui.input"
          />
        </div>
      </div>
    </section>

    <section :class="ui.section">
      <div class="flex items-start gap-4">
        <span :class="ui.sectionIcon">
          <i class="bi bi-journal-richtext"></i>
        </span>
        <div>
          <p :class="ui.eyebrow">Description</p>
          <h2 class="text-lg font-semibold text-slate-900">Contenu & storytelling</h2>
          <p class="text-sm text-slate-500">Racontez l'histoire, les ingredients et les benefices.</p>
        </div>
      </div>
      <div class="mt-6 space-y-5">
        <div>
          <label :class="ui.label" for="product-tagline">Tagline</label>
          <p :class="ui.hint">Baseline courte.</p>
          <input
            id="product-tagline"
            v-model="form.tagline"
            type="text"
            :class="ui.input"
            placeholder="Shot green ultra clean"
          />
        </div>
        <div>
          <label :class="ui.label" for="product-description">Description detaillee</label>
          <textarea
            id="product-description"
            v-model="form.description"
            rows="4"
            :class="ui.textarea"
          ></textarea>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
          <div>
            <label :class="ui.label" for="product-ingredients">Ingredients (un par ligne)</label>
            <textarea
              id="product-ingredients"
              v-model="ingredientsText"
              rows="4"
              :class="ui.textarea"
            ></textarea>
          </div>
          <div>
            <label :class="ui.label" for="product-benefits">Bienfaits (un par ligne)</label>
            <textarea
              id="product-benefits"
              v-model="benefitsText"
              rows="4"
              :class="ui.textarea"
            ></textarea>
          </div>
        </div>
        <div class="grid gap-5 md:grid-cols-3">
          <div>
            <label :class="ui.label" for="product-moments">Moments de consommation</label>
            <p :class="ui.hint">Separez par virgule.</p>
            <input
              id="product-moments"
              v-model="momentsText"
              type="text"
              :class="ui.input"
              placeholder="matin, apres sport..."
            />
          </div>
          <div>
            <label :class="ui.label" for="product-taste">Notes de gout</label>
            <input
              id="product-taste"
              v-model="tasteText"
              type="text"
              :class="ui.input"
              placeholder="tropical, epice"
            />
          </div>
          <div>
            <label :class="ui.label" for="product-batch-note">Note atelier / batch</label>
            <input
              id="product-batch-note"
              v-model="form.batch_note"
              type="text"
              :class="ui.input"
              placeholder="Pressage 6h-11h"
            />
          </div>
        </div>
      </div>
    </section>

    <section :class="ui.section">
      <div class="flex items-start gap-4">
        <span :class="ui.sectionIcon">
          <i class="bi bi-images"></i>
        </span>
        <div>
          <p :class="ui.eyebrow">Media</p>
          <h2 class="text-lg font-semibold text-slate-900">Galerie photos</h2>
          <p class="text-sm text-slate-500">Associez plusieurs visuels pour l'affichage produit.</p>
        </div>
      </div>
      <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500">Collez les URLs de vos visuels (S3, CDN, asset interne).</p>
        <button
          type="button"
          class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:border-emerald-400 hover:text-emerald-600"
          @click="addImage"
        >
          <i class="bi bi-plus-circle"></i>
          Ajouter une image
        </button>
      </div>
      <div v-if="form.images?.length" class="mt-5 grid gap-4">
        <div
          v-for="(image, index) in form.images"
          :key="index"
          class="grid gap-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-12"
        >
          <div class="md:col-span-5">
            <label :class="ui.label" :for="`image-url-${index}`">URL *</label>
            <input
              :id="`image-url-${index}`"
              v-model="image.url"
              type="url"
              required
              :class="ui.input"
              placeholder="https://..."
            />
            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
              <label class="inline-flex items-center gap-2 font-semibold text-[#254a29] cursor-pointer">
                <i class="bi bi-cloud-arrow-up"></i>
                Importer
                <input type="file" class="hidden" accept="image/*" @change="handleGalleryUpload($event, index)" />
              </label>
              <span v-if="image.__upload?.uploading" class="text-slate-500">Import en cours...</span>
              <a
                v-if="image.url"
                :href="image.url"
                class="text-[#f49926] hover:underline"
                target="_blank"
                rel="noopener"
              >
                Prévisualiser
              </a>
            </div>
            <p v-if="image.__upload?.error" class="text-xs text-red-500 mt-1">
              {{ image.__upload.error }}
            </p>
          </div>
          <div class="md:col-span-5">
            <label :class="ui.label" :for="`image-alt-${index}`">Texte alternatif</label>
            <input
              :id="`image-alt-${index}`"
              v-model="image.alt"
              type="text"
              :class="ui.input"
              placeholder="Bouteille studio"
            />
          </div>
          <div class="md:col-span-2 flex items-end justify-between gap-2">
            <div class="flex-1">
              <label :class="ui.label" :for="`image-position-${index}`">Ordre</label>
              <input
                :id="`image-position-${index}`"
                v-model.number="image.position"
                type="number"
                min="0"
                :class="ui.input"
              />
            </div>
            <button
              type="button"
              class="text-sm font-semibold text-red-500 hover:text-red-600"
              @click="removeImage(index)"
            >
              Supprimer
            </button>
          </div>
        </div>
      </div>
      <p v-else class="mt-5 text-sm text-slate-500">Aucune image pour le moment.</p>
    </section>

    <div class="flex items-center justify-end gap-3">
      <Link
        :href="route('admin.products.index')"
        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-emerald-400 hover:text-emerald-600"
      >
        Annuler
      </Link>
      <button
        type="submit"
        class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-200 disabled:opacity-60"
        :disabled="form.processing"
      >
        <i class="bi bi-save"></i>
        {{ submitLabel }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed, watch } from 'vue'

const ui = {
  section: 'rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-sm ring-1 ring-black/5',
  sectionIcon: 'inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-xl',
  eyebrow: 'text-xs font-semibold uppercase tracking-wide text-emerald-500',
  label: 'flex items-center gap-2 text-sm font-semibold text-slate-700',
  hint: 'mt-1 text-xs text-slate-500',
  input:
    'mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100',
  select:
    'block w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100',
  textarea:
    'mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100',
  checkbox: 'h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500'
}

const props = defineProps({
  form: {
    type: Object,
    required: true
  },
  statuses: {
    type: Object,
    default: () => ({})
  },
  categories: {
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

const categoryOptions = computed(() => props.categories ?? [])

const ingredientsText = computed({
  get: () => (props.form.ingredients || []).join('\n'),
  set: (value) => {
    props.form.ingredients = parseList(value, true)
  }
})

const benefitsText = computed({
  get: () => (props.form.benefits || []).join('\n'),
  set: (value) => {
    props.form.benefits = parseList(value, true)
  }
})

const momentsText = computed({
  get: () => (props.form.moments || []).join(', '),
  set: (value) => {
    props.form.moments = parseList(value)
  }
})

const tasteText = computed({
  get: () => (props.form.taste_notes || []).join(', '),
  set: (value) => {
    props.form.taste_notes = parseList(value)
  }
})

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
const uploadImageUrl = ziggyRoute ? ziggyRoute('admin.products.images.upload') : '/admin/products/images/upload'
const csrfToken =
  typeof document !== 'undefined'
    ? document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
    : ''

const assignUploadMeta = (image) => {
  if (image && typeof image === 'object' && !image.__upload) {
    image.__upload = {
      uploading: false,
      error: null
    }
  }
}

const ensureImagesArray = () => {
  if (!Array.isArray(props.form.images)) {
    props.form.images = []
  }
  props.form.images.forEach(assignUploadMeta)
}

const addImage = () => {
  ensureImagesArray()
  const newImage = {
    url: '',
    alt: '',
    position: props.form.images.length
  }
  assignUploadMeta(newImage)
  props.form.images.push(newImage)
}

const removeImage = (index) => {
  ensureImagesArray()
  props.form.images.splice(index, 1)
}

const handleGalleryUpload = async (event, index) => {
  const file = event?.target?.files?.[0]
  const image = form.images?.[index]

  if (!file || !image) {
    return
  }

  assignUploadMeta(image)
  image.__upload.uploading = true
  image.__upload.error = null

  const formData = new FormData()
  formData.append('image', file)

  try {
    const response = await fetch(uploadImageUrl, {
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
      let message = 'Upload failed'
      try {
        const errorPayload = await response.json()
        message = errorPayload?.message ?? message
      } catch (error) {
        // ignore
      }
      throw new Error(message)
    }

    const payload = await response.json()

    if (payload?.url) {
      image.url = payload.url
    }
  } catch (error) {
    image.__upload.error = error?.message ?? 'Import impossible. Réessayez.'
  } finally {
    image.__upload.uploading = false
    if (event?.target) {
      event.target.value = ''
    }
  }
}

const parseList = (value, useLineBreak = false) => {
  if (!value) {
    return []
  }
  const items = useLineBreak ? value.split(/\r?\n/) : value.split(',')
  return items.map((item) => item.trim()).filter(Boolean)
}

const form = props.form
ensureImagesArray()

watch(
  () => form.ingredients,
  (val) => {
    if (!Array.isArray(val)) {
      form.ingredients = parseList(val)
    }
  },
)
</script>
