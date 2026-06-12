<template>
  <AdminLayout title="Nouveau produit">
    <Head title="Ajouter un produit — Sandy Juice" />

    <!-- En-tête -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-xl font-bold text-[#254a29]">Nouveau produit</h1>
        <p class="text-sm text-slate-500 mt-0.5">Remplissez les infos puis publiez dans le catalogue.</p>
      </div>
      <Link :href="route('admin.products.index')" class="text-sm text-slate-500 hover:text-[#254a29] flex items-center gap-1.5 transition">
        <i class="bi bi-arrow-left"></i>
        Catalogue
      </Link>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

      <!-- Formulaire -->
      <div class="lg:col-span-2">
        <ProductForm
          :form="form"
          :statuses="statuses"
          :categories="categories"
          submit-label="Publier le produit"
          :on-submit="submit"
        />
      </div>

      <!-- Preview live -->
      <aside class="lg:col-span-1">
        <div class="sticky top-24 space-y-4">

          <!-- Carte aperçu -->
          <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center gap-2">
              <i class="bi bi-eye text-slate-400 text-sm"></i>
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Aperçu catalogue</span>
            </div>
            <!-- Image -->
            <div class="aspect-[4/3] bg-slate-50 overflow-hidden relative">
              <img
                v-if="form.image_path"
                :src="form.image_path"
                class="w-full h-full object-cover"
                alt="Aperçu"
              >
              <div v-else class="w-full h-full flex items-center justify-center">
                <i class="bi bi-image text-5xl text-slate-200"></i>
              </div>
              <!-- Badge statut -->
              <span
                class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full"
                :class="isPublished ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white'"
              >
                {{ isPublished ? 'Publié' : 'Brouillon' }}
              </span>
              <!-- Badge catégorie -->
              <span v-if="displayCategory" class="absolute top-3 left-3 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-white/80 backdrop-blur-sm text-[#254a29] border border-slate-200">
                {{ displayCategory }}
              </span>
            </div>
            <!-- Infos -->
            <div class="p-4">
              <h3 class="font-semibold text-[#254a29] text-base leading-snug">
                {{ form.name || 'Nom du produit' }}
              </h3>
              <p class="text-xs text-slate-400 mt-1 line-clamp-2">
                {{ form.tagline || 'Tagline du produit…' }}
              </p>
              <div class="mt-3 flex items-center justify-between">
                <span class="text-lg font-bold text-[#E85A14]">
                  {{ form.price ? formatPrice(form.price) : '— FCFA' }}
                </span>
                <span v-if="form.size" class="text-xs text-slate-400">{{ form.size }}</span>
              </div>
              <div class="mt-3 flex gap-2">
                <div class="flex-1 h-9 rounded-xl bg-[#E85A14]/10 flex items-center justify-center text-xs font-semibold text-[#E85A14]">
                  <i class="bi bi-cart-plus mr-1"></i> Ajouter
                </div>
                <div class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400">
                  <i class="bi bi-eye text-sm"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Checklist -->
          <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Checklist publication</p>
            <ul class="space-y-2">
              <li v-for="item in checklist" :key="item.label" class="flex items-center gap-2.5 text-sm">
                <i
                  class="bi text-base shrink-0"
                  :class="item.done ? 'bi-check-circle-fill text-emerald-500' : 'bi-circle text-slate-300'"
                ></i>
                <span :class="item.done ? 'text-slate-700' : 'text-slate-400'">{{ item.label }}</span>
              </li>
            </ul>
          </div>

        </div>
      </aside>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ProductForm from './Partials/ProductForm.vue'

const props = defineProps({
  product:    { type: Object, required: true },
  statuses:   { type: Object, default: () => ({}) },
  categories: { type: Array,  default: () => [] },
})

const form = useForm({ ...props.product })

const isPublished   = computed(() => form.status === 'published')
const displayCategory = computed(() => {
  const found = props.categories.find(c => c.slug === form.category)
  return found ? found.name : form.category || null
})

const formatPrice = (value) => `${(Number(value) || 0).toFixed(0)} FCFA`

const checklist = computed(() => [
  { label: 'Nom du produit',     done: Boolean(form.name) },
  { label: 'Catégorie choisie',  done: Boolean(form.category) },
  { label: 'Prix renseigné',     done: Boolean(form.price) },
  { label: 'Photo principale',   done: Boolean(form.image_path) },
  { label: 'Description',        done: Boolean(form.description || form.tagline) },
])

const submit = () => form.post(route('admin.products.store'))
</script>
