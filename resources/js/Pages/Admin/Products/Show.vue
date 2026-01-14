<template>
  <AdminLayout :title="product.name">
    <Head :title="product.name" />

    <div class="grid gap-6 lg:grid-cols-3">
      <section class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ product.category }}</p>
            <h1 class="text-3xl font-semibold text-[#254a29]">{{ product.name }}</h1>
            <p class="text-sm text-slate-500">{{ product.tagline }}</p>
          </div>
          <Link :href="route('admin.products.edit', product.id)" class="text-sm font-semibold text-[#f49926] hover:text-[#f28700]">
            Editer
          </Link>
        </div>
        <dl class="grid gap-4 md:grid-cols-2 text-sm">
          <div>
            <dt class="text-slate-400 uppercase">Statut</dt>
            <dd class="text-lg font-semibold text-[#254a29]">{{ statuses[product.status] || product.status }}</dd>
          </div>
          <div>
            <dt class="text-slate-400 uppercase">Prix</dt>
            <dd class="text-lg font-semibold text-[#f49926]">{{ formatPrice(product.price) }}</dd>
          </div>
          <div>
            <dt class="text-slate-400 uppercase">Stock</dt>
            <dd class="text-lg font-semibold">{{ product.stock ?? 0 }}</dd>
          </div>
          <div>
            <dt class="text-slate-400 uppercase">Batch</dt>
            <dd class="text-lg font-semibold">{{ product.availability || 'N/A' }}</dd>
          </div>
        </dl>

        <article class="space-y-4">
          <h2 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-2">Description</h2>
          <p class="text-slate-600 leading-relaxed">
            {{ product.description }}
          </p>
          <div>
            <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-2">Galerie</h3>
            <div v-if="gallery.length" class="grid gap-3 md:grid-cols-3">
              <div v-for="image in gallery" :key="image.id || image.url" class="rounded-2xl overflow-hidden border border-slate-100 bg-white">
                <img :src="image.url" :alt="image.alt || product.name" class="w-full h-40 object-cover" />
                <p class="text-xs text-slate-500 px-3 py-2">{{ image.alt || 'Visuel produit' }}</p>
              </div>
            </div>
            <p v-else class="text-xs text-slate-500">Aucune image associée.</p>
          </div>
        </article>

        <div class="grid gap-6 md:grid-cols-2">
          <article>
            <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-2">Ingredients</h3>
            <ul class="space-y-1 text-sm text-slate-600">
              <li v-for="(ingredient, index) in (product.ingredients || [])" :key="index">- {{ ingredient }}</li>
            </ul>
          </article>
          <article>
            <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-2">Bienfaits</h3>
            <ul class="space-y-1 text-sm text-slate-600">
              <li v-for="(benefit, index) in (product.benefits || [])" :key="index">- {{ benefit }}</li>
            </ul>
          </article>
        </div>
      </section>

      <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h2 class="text-lg font-semibold text-[#254a29]">Performances</h2>
        <dl class="space-y-3 text-sm">
          <div class="flex items-center justify-between">
            <dt class="text-slate-500">Commandes liées</dt>
            <dd class="text-lg font-semibold">{{ product.orders_count ?? 0 }}</dd>
          </div>
          <div class="flex items-center justify-between">
            <dt class="text-slate-500">Edition limitée</dt>
            <dd class="text-lg font-semibold">{{ product.is_limited ? 'Oui' : 'Non' }}</dd>
          </div>
          <div class="flex items-center justify-between">
            <dt class="text-slate-500">Nouveauté</dt>
            <dd class="text-lg font-semibold">{{ product.is_new ? 'Oui' : 'Non' }}</dd>
          </div>
        </dl>
      </section>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  statuses: {
    type: Object,
    default: () => ({})
  }
})

const gallery = computed(() => props.product.images ?? [])

const formatPrice = (value) => {
  const number = Number(value) || 0
  return `${number.toFixed(0)} FCFA`
}
</script>
