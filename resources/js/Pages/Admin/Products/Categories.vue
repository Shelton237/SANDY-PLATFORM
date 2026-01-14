<template>
  <AdminLayout title="Catégories catalogue">
    <Head title="Catégories produits" />

    <section class="grid gap-6 lg:grid-cols-3 mb-8">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-1">
        <h2 class="text-xl font-semibold text-[#254a29] mb-4">Nouvelle catégorie</h2>
        <form class="space-y-4" @submit.prevent="createCategory">
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Nom</label>
            <input v-model="createForm.name" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required />
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Slug (optionnel)</label>
            <input v-model="createForm.slug" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="ex: jus" />
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Tagline</label>
            <input v-model="createForm.tagline" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" />
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Description</label>
            <textarea v-model="createForm.description" rows="2" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
          </div>
          <div class="grid gap-4 md:grid-cols-2">
            <div>
              <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Icone</label>
              <input v-model="createForm.icon" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="bi-droplet" />
            </div>
            <div>
              <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Accent</label>
              <input v-model="createForm.accent" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="emerald" />
            </div>
          </div>
          <div class="grid gap-4 md:grid-cols-2">
            <div>
              <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Image hero</label>
              <input v-model="createForm.hero_image" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="/img/cat.jpg" />
            </div>
            <div>
              <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Position</label>
              <input v-model.number="createForm.position" type="number" min="0" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" />
            </div>
          </div>
          <label class="text-xs text-slate-500 flex items-center">
            <input v-model="createForm.is_active" type="checkbox" class="mr-2" />
            Active
          </label>
          <label class="text-xs text-slate-500 flex items-center">
            <input v-model="createForm.is_featured" type="checkbox" class="mr-2" />
            A mettre en avant
          </label>
          <button type="submit" class="rounded-2xl bg-[#254a29] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1d3c22] w-full" :disabled="createForm.processing">
            Ajouter
          </button>
        </form>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Catalogue</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Catégories existantes</h2>
          </div>
          <p class="text-sm text-slate-500">{{ editableCategories.length }} entrées</p>
        </div>
        <div v-if="editableCategories.length" class="space-y-4">
          <section
            v-for="category in editableCategories"
            :key="category.id"
            class="border border-slate-100 rounded-2xl p-4 flex flex-col gap-3 bg-slate-50/60"
          >
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div>
                <input v-model="category.name" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30 font-semibold" />
                <p class="text-xs text-slate-500">Slug: <input v-model="category.slug" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30 mt-1" /></p>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <label class="text-xs text-slate-500 flex items-center gap-2">
                  <input v-model="category.is_active" type="checkbox" />
                  Active
                </label>
                <label class="text-xs text-slate-500 flex items-center gap-2">
                  <input v-model="category.is_featured" type="checkbox" />
                  En avant
                </label>
                <input
                  v-model.number="category.position"
                  type="number"
                  min="0"
                  class="rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30 w-20 text-center"
                />
              </div>
            </div>
            <div class="grid gap-3 md:grid-cols-2">
              <div>
                <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Tagline</label>
                <input v-model="category.tagline" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" />
              </div>
              <div>
                <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Icone / Accent</label>
                <div class="grid grid-cols-2 gap-2">
                  <input v-model="category.icon" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="bi-droplet" />
                  <input v-model="category.accent" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="emerald" />
                </div>
              </div>
            </div>
            <div>
              <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Description</label>
              <textarea v-model="category.description" rows="2" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
            </div>
            <div class="flex items-center justify-between">
              <input v-model="category.hero_image" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" placeholder="Visuel hero (optionnel)" />
              <div class="flex items-center gap-3">
                <button class="text-sm text-[#254a29] hover:text-[#f49926]" @click="saveCategory(category)">
                  Enregistrer
                </button>
                <button class="text-sm text-red-500 hover:text-red-600" @click="deleteCategory(category)">
                  Supprimer
                </button>
              </div>
            </div>
          </section>
        </div>
        <p v-else class="text-sm text-slate-500">Aucune catégorie pour le moment.</p>
      </article>
    </section>
  </AdminLayout>
</template>

<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  }
})

const editableCategories = ref(props.categories.map((category) => ({ ...category })))

watch(
  () => props.categories,
  (value) => {
    editableCategories.value = (value || []).map((category) => ({ ...category }))
  }
)

const createForm = useForm({
  name: '',
  slug: '',
  tagline: '',
  description: '',
  icon: '',
  accent: '',
  hero_image: '',
  position: (props.categories?.length ?? 0) + 1,
  is_active: true,
  is_featured: false
})

const createCategory = () => {
  createForm.post(route('admin.product-categories.store'), {
    preserveScroll: true,
    onSuccess: () => createForm.reset('name', 'slug', 'tagline', 'description')
  })
}

const saveCategory = (category) => {
  router.patch(route('admin.product-categories.update', category.id), {
    name: category.name,
    slug: category.slug,
    tagline: category.tagline,
    description: category.description,
    icon: category.icon,
    accent: category.accent,
    hero_image: category.hero_image,
    position: category.position,
    is_active: category.is_active,
    is_featured: category.is_featured
  }, {
    preserveScroll: true
  })
}

const deleteCategory = (category) => {
  if (!confirm(`Supprimer ${category.name} ?`)) {
    return
  }

  router.delete(route('admin.product-categories.destroy', category.id), {
    preserveScroll: true
  })
}
</script>

