<template>
  <form @submit.prevent="onSubmit" class="space-y-6">

    <!-- ── 1. Photo principale ────────────────────────────────────────────── -->
    <section class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2">
        <i class="bi bi-image text-[#E85A14]"></i>
        <span class="text-sm font-semibold text-slate-700">Photo principale</span>
      </div>
      <div class="p-5">
        <label
          class="relative flex flex-col items-center justify-center w-full rounded-xl border-2 border-dashed transition cursor-pointer overflow-hidden"
          :class="form.image_path ? 'border-transparent h-56' : 'border-slate-200 hover:border-[#E85A14]/50 h-40 bg-slate-50'"
        >
          <!-- Preview -->
          <img
            v-if="form.image_path"
            :src="form.image_path"
            class="absolute inset-0 w-full h-full object-cover"
            alt="Aperçu"
          >
          <div
            class="relative z-10 flex flex-col items-center gap-2 text-center"
            :class="form.image_path ? 'bg-black/40 px-4 py-2 rounded-xl' : ''"
          >
            <i class="bi bi-cloud-arrow-up text-2xl" :class="form.image_path ? 'text-white' : 'text-slate-400'"></i>
            <span class="text-sm font-semibold" :class="form.image_path ? 'text-white' : 'text-slate-500'">
              {{ form.image_path ? 'Changer la photo' : 'Cliquer ou glisser une photo ici' }}
            </span>
            <span class="text-xs" :class="form.image_path ? 'text-white/70' : 'text-slate-400'">JPG, PNG, WebP — max 5 Mo</span>
          </div>
          <input type="file" class="hidden" accept="image/*" @change="handlePrimaryImageUpload">
        </label>
        <div v-if="heroImageUpload.uploading" class="mt-2 flex items-center gap-2 text-xs text-slate-500">
          <i class="bi bi-arrow-repeat animate-spin text-[#E85A14]"></i> Upload en cours…
        </div>
        <p v-if="heroImageUpload.error" class="mt-2 text-xs text-red-500">{{ heroImageUpload.error }}</p>
        <input
          v-model="form.image_path"
          type="text"
          class="mt-3 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-500 placeholder:text-slate-400 focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
          placeholder="Ou collez une URL d'image…"
        >
      </div>
    </section>

    <!-- ── 2. Infos essentielles ──────────────────────────────────────────── -->
    <section class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2">
        <i class="bi bi-cup-straw text-[#E85A14]"></i>
        <span class="text-sm font-semibold text-slate-700">Informations essentielles</span>
      </div>
      <div class="p-5 space-y-5">

        <!-- Nom + statut -->
        <div class="flex items-start gap-4">
          <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 mb-1">
              Nom du produit <span class="text-[#E85A14]">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
              placeholder="Ex : Nectar mangue gingembre"
            >
          </div>
          <!-- Toggle statut -->
          <div class="shrink-0 pt-5">
            <button
              type="button"
              class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold border transition"
              :class="isPublished
                ? 'bg-emerald-50 border-emerald-300 text-emerald-700'
                : 'bg-slate-50 border-slate-200 text-slate-500'"
              @click="toggleStatus"
            >
              <span class="w-2 h-2 rounded-full" :class="isPublished ? 'bg-emerald-500' : 'bg-slate-300'"></span>
              {{ isPublished ? 'Publié' : 'Brouillon' }}
            </button>
          </div>
        </div>

        <!-- Catégorie — chips -->
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-2">
            Catégorie <span class="text-[#E85A14]">*</span>
          </label>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="cat in categoryOptions"
              :key="cat.slug"
              type="button"
              class="px-4 py-1.5 rounded-full text-sm font-medium border transition"
              :class="form.category === cat.slug
                ? 'bg-[#254a29] text-white border-[#254a29]'
                : 'border-slate-200 text-slate-500 hover:border-[#254a29]/40'"
              @click="form.category = cat.slug"
            >
              {{ cat.name }}
            </button>
            <!-- Saisie libre si pas de catégories -->
            <input
              v-if="!categoryOptions.length"
              v-model="form.category"
              type="text"
              required
              class="rounded-full border border-slate-200 px-4 py-1.5 text-sm focus:border-[#E85A14] focus:outline-none"
              placeholder="Catégorie"
            >
          </div>
        </div>

        <!-- Prix / Stock / Format -->
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">
              Prix (FCFA) <span class="text-[#E85A14]">*</span>
            </label>
            <input
              v-model.number="form.price"
              type="number"
              min="0"
              step="50"
              required
              class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-[#E85A14] focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
              placeholder="1500"
            >
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Stock</label>
            <input
              v-model.number="form.stock"
              type="number"
              min="0"
              class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
              placeholder="0"
            >
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Format</label>
            <input
              v-model="form.size"
              type="text"
              class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
              placeholder="330 ml"
            >
          </div>
        </div>

        <!-- Tagline -->
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Tagline</label>
          <input
            v-model="form.tagline"
            type="text"
            class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
            placeholder="Ex : Le shot vert qui réveille"
          >
        </div>

        <!-- Description -->
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Description</label>
          <textarea
            v-model="form.description"
            rows="3"
            class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm resize-none focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
            placeholder="Décrivez le produit, ses saveurs, son origine…"
          ></textarea>
        </div>
      </div>
    </section>

    <!-- ── 3. Ingrédients & bienfaits ─────────────────────────────────────── -->
    <section class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2">
        <i class="bi bi-list-check text-[#E85A14]"></i>
        <span class="text-sm font-semibold text-slate-700">Ingrédients & bienfaits</span>
      </div>
      <div class="p-5 grid gap-4 md:grid-cols-2">
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">
            Ingrédients <span class="text-slate-400 font-normal">(un par ligne)</span>
          </label>
          <textarea
            v-model="ingredientsText"
            rows="5"
            class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm resize-none focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
            placeholder="Mangue fraîche&#10;Gingembre&#10;Citron vert&#10;Eau de coco"
          ></textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">
            Bienfaits <span class="text-slate-400 font-normal">(un par ligne)</span>
          </label>
          <textarea
            v-model="benefitsText"
            rows="5"
            class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm resize-none focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20"
            placeholder="Riche en vitamine C&#10;Anti-inflammatoire&#10;Booste l'immunité"
          ></textarea>
        </div>
      </div>
    </section>

    <!-- ── 4. Options avancées (collapsible) ─────────────────────────────── -->
    <section class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
      <button
        type="button"
        class="w-full px-5 py-3 flex items-center justify-between border-b border-slate-100 hover:bg-slate-50 transition"
        @click="showAdvanced = !showAdvanced"
      >
        <div class="flex items-center gap-2">
          <i class="bi bi-sliders text-slate-400"></i>
          <span class="text-sm font-semibold text-slate-700">Options avancées</span>
          <span class="text-xs text-slate-400">(badge, calories, notes…)</span>
        </div>
        <i class="bi text-slate-400 transition-transform" :class="showAdvanced ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
      </button>
      <div v-show="showAdvanced" class="p-5 space-y-5">

        <!-- Flags -->
        <div class="flex flex-wrap gap-3">
          <label class="flex items-center gap-2.5 px-4 py-2 rounded-xl border cursor-pointer transition select-none"
            :class="form.is_new ? 'border-[#f49926] bg-[#fef4e7] text-[#f49926]' : 'border-slate-200 text-slate-500 hover:border-[#f49926]/40'">
            <input v-model="form.is_new" type="checkbox" class="hidden">
            <i class="bi bi-stars"></i>
            Nouveauté
          </label>
          <label class="flex items-center gap-2.5 px-4 py-2 rounded-xl border cursor-pointer transition select-none"
            :class="form.is_limited ? 'border-purple-400 bg-purple-50 text-purple-700' : 'border-slate-200 text-slate-500 hover:border-purple-300'">
            <input v-model="form.is_limited" type="checkbox" class="hidden">
            <i class="bi bi-gem"></i>
            Édition limitée
          </label>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Badge</label>
            <input v-model="form.badge" type="text" class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20" placeholder="Best seller">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Calories (kcal)</label>
            <input v-model.number="form.calories" type="number" min="0" class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Énergie (0-10)</label>
            <input v-model.number="form.energy_index" type="number" min="0" max="10" class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Disponibilité</label>
            <input v-model="form.availability" type="text" class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20" placeholder="Quotidien 6h-12h">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">
              Notes de goût <span class="font-normal text-slate-400">(séparées par virgule)</span>
            </label>
            <input v-model="tasteText" type="text" class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20" placeholder="tropical, épicé, sucré">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">
              Moments de consommation <span class="font-normal text-slate-400">(virgule)</span>
            </label>
            <input v-model="momentsText" type="text" class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#E85A14] focus:outline-none focus:ring-2 focus:ring-[#E85A14]/20" placeholder="matin, après sport">
          </div>
        </div>
      </div>
    </section>

    <!-- ── 5. Galerie photos ──────────────────────────────────────────────── -->
    <section class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="bi bi-images text-[#E85A14]"></i>
          <span class="text-sm font-semibold text-slate-700">Galerie</span>
          <span class="text-xs text-slate-400">(photos supplémentaires)</span>
        </div>
        <button
          type="button"
          class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#254a29] border border-[#254a29]/30 px-3 py-1.5 rounded-lg hover:bg-[#254a29]/5 transition"
          @click="addImage"
        >
          <i class="bi bi-plus-lg"></i>
          Ajouter
        </button>
      </div>
      <div class="p-5">
        <div v-if="form.images?.length" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          <div
            v-for="(image, index) in form.images"
            :key="index"
            class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-square bg-slate-50"
          >
            <!-- Thumbnail -->
            <img
              v-if="image.url"
              :src="image.url"
              :alt="image.alt || 'Photo ' + (index + 1)"
              class="w-full h-full object-cover"
            >
            <div v-else class="w-full h-full flex items-center justify-center">
              <i class="bi bi-image text-3xl text-slate-300"></i>
            </div>

            <!-- Overlay actions -->
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-2 p-2">
              <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg cursor-pointer transition">
                <i class="bi bi-cloud-arrow-up"></i>
                Changer
                <input type="file" class="hidden" accept="image/*" @change="handleGalleryUpload($event, index)">
              </label>
              <button
                type="button"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-red-500/80 hover:bg-red-600/80 px-3 py-1.5 rounded-lg transition"
                @click="removeImage(index)"
              >
                <i class="bi bi-trash"></i>
                Supprimer
              </button>
            </div>

            <!-- Loading overlay -->
            <div v-if="image.__upload?.uploading" class="absolute inset-0 bg-black/60 flex items-center justify-center">
              <i class="bi bi-arrow-repeat animate-spin text-white text-2xl"></i>
            </div>

            <!-- URL input (replié sous la thumbnail) -->
            <div class="absolute bottom-0 left-0 right-0 bg-white/90 px-2 py-1 opacity-0 group-hover:opacity-100 transition">
              <input
                v-model="image.url"
                type="text"
                class="w-full text-[10px] border-0 bg-transparent outline-none text-slate-600 truncate"
                placeholder="URL…"
              >
            </div>
          </div>

          <!-- Bouton d'ajout rapide -->
          <label class="relative rounded-xl border-2 border-dashed border-slate-200 hover:border-[#E85A14]/50 aspect-square flex flex-col items-center justify-center gap-1 cursor-pointer bg-slate-50 transition">
            <i class="bi bi-plus-lg text-2xl text-slate-300"></i>
            <span class="text-xs text-slate-400">Ajouter</span>
            <input type="file" class="hidden" accept="image/*" @change="handleQuickGalleryAdd">
          </label>
        </div>

        <div v-else class="flex flex-col items-center justify-center py-10 gap-2">
          <label class="flex flex-col items-center gap-2 cursor-pointer">
            <i class="bi bi-images text-3xl text-slate-300"></i>
            <span class="text-sm text-slate-400">Aucune photo de galerie</span>
            <span class="text-xs font-semibold text-[#254a29] hover:underline">
              Cliquer pour ajouter
            </span>
            <input type="file" class="hidden" accept="image/*" @change="handleQuickGalleryAdd">
          </label>
        </div>
      </div>
    </section>

    <!-- ── Actions ───────────────────────────────────────────────────────── -->
    <div class="flex items-center justify-between gap-3 py-2">
      <Link
        :href="route('admin.products.index')"
        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition"
      >
        <i class="bi bi-arrow-left"></i>
        Retour au catalogue
      </Link>
      <div class="flex gap-3">
        <button
          type="button"
          class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:border-slate-300 transition"
          :disabled="form.processing"
          @click="saveDraft"
        >
          Enregistrer brouillon
        </button>
        <button
          type="submit"
          class="inline-flex items-center gap-2 rounded-xl bg-[#E85A14] hover:bg-[#c44010] text-white px-6 py-2.5 text-sm font-semibold shadow-sm transition disabled:opacity-60"
          :disabled="form.processing"
        >
          <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
          <i v-else class="bi bi-check-lg"></i>
          {{ submitLabel }}
        </button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps({
  form:        { type: Object,   required: true },
  statuses:    { type: Object,   default: () => ({}) },
  categories:  { type: Array,    default: () => [] },
  submitLabel: { type: String,   default: 'Enregistrer' },
  onSubmit:    { type: Function, required: true },
})

const categoryOptions = computed(() => props.categories ?? [])
const showAdvanced = ref(false)

// ── Statut toggle ──────────────────────────────────────────────────────────
const PUBLISHED_STATUS = 'published'
const isPublished = computed(() => props.form.status === PUBLISHED_STATUS)
const toggleStatus = () => {
  props.form.status = isPublished.value ? 'draft' : PUBLISHED_STATUS
}

// ── Listes (ingrédients, bienfaits, moments, goût) ────────────────────────
const parseList = (value, byLine = false) => {
  if (!value) return []
  return (byLine ? value.split(/\r?\n/) : value.split(','))
    .map(s => s.trim()).filter(Boolean)
}

const ingredientsText = computed({
  get: () => (props.form.ingredients || []).join('\n'),
  set: v => { props.form.ingredients = parseList(v, true) },
})
const benefitsText = computed({
  get: () => (props.form.benefits || []).join('\n'),
  set: v => { props.form.benefits = parseList(v, true) },
})
const momentsText = computed({
  get: () => (props.form.moments || []).join(', '),
  set: v => { props.form.moments = parseList(v) },
})
const tasteText = computed({
  get: () => (props.form.taste_notes || []).join(', '),
  set: v => { props.form.taste_notes = parseList(v) },
})

// ── Upload ─────────────────────────────────────────────────────────────────
const csrfToken = typeof document !== 'undefined'
  ? document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
  : ''
const uploadUrl = typeof window !== 'undefined' && typeof window.route === 'function'
  ? window.route('admin.products.images.upload')
  : '/admin/products/images/upload'

const heroImageUpload = ref({ uploading: false, error: null })

const doUpload = async (file) => {
  const fd = new FormData()
  fd.append('image', file)
  const res = await fetch(uploadUrl, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    credentials: 'same-origin',
    body: fd,
  })
  if (!res.ok) {
    const err = await res.json().catch(() => ({}))
    throw new Error(err?.message ?? 'Upload échoué')
  }
  return (await res.json())?.url ?? null
}

const handlePrimaryImageUpload = async (event) => {
  const file = event?.target?.files?.[0]
  if (!file) return
  heroImageUpload.value = { uploading: true, error: null }
  try {
    const url = await doUpload(file)
    if (url) props.form.image_path = url
  } catch (e) {
    heroImageUpload.value.error = e?.message ?? 'Import impossible'
  } finally {
    heroImageUpload.value.uploading = false
    if (event?.target) event.target.value = ''
  }
}

// ── Galerie ────────────────────────────────────────────────────────────────
const ensureImages = () => {
  if (!Array.isArray(props.form.images)) props.form.images = []
  props.form.images.forEach(img => { if (!img.__upload) img.__upload = { uploading: false, error: null } })
}

const addImage = () => {
  ensureImages()
  const img = { url: '', alt: '', position: props.form.images.length, __upload: { uploading: false, error: null } }
  props.form.images.push(img)
}

const removeImage = (index) => { ensureImages(); props.form.images.splice(index, 1) }

const handleGalleryUpload = async (event, index) => {
  const file = event?.target?.files?.[0]
  ensureImages()
  const image = props.form.images[index]
  if (!file || !image) return
  image.__upload.uploading = true; image.__upload.error = null
  try {
    const url = await doUpload(file)
    if (url) image.url = url
  } catch (e) {
    image.__upload.error = e?.message ?? 'Import impossible'
  } finally {
    image.__upload.uploading = false
    if (event?.target) event.target.value = ''
  }
}

const handleQuickGalleryAdd = async (event) => {
  const file = event?.target?.files?.[0]
  if (!file) return
  ensureImages()
  const img = { url: '', alt: '', position: props.form.images.length, __upload: { uploading: true, error: null } }
  props.form.images.push(img)
  const index = props.form.images.length - 1
  try {
    const url = await doUpload(file)
    if (url) props.form.images[index].url = url
  } catch (e) {
    props.form.images[index].__upload.error = e?.message ?? 'Import impossible'
  } finally {
    props.form.images[index].__upload.uploading = false
    if (event?.target) event.target.value = ''
  }
}

// ── Brouillon ──────────────────────────────────────────────────────────────
const saveDraft = () => {
  const prev = props.form.status
  props.form.status = 'draft'
  props.onSubmit()
  // Restaure si annulé — Inertia gère l'erreur
}

const onSubmit = () => props.onSubmit()

// ── Init ───────────────────────────────────────────────────────────────────
const form = props.form
ensureImages()

watch(() => form.ingredients, val => {
  if (!Array.isArray(val)) form.ingredients = parseList(val)
})
</script>
