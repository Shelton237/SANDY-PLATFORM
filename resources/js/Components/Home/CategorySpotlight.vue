<template>
  <section class="bg-gradient-to-br from-[#fef7ee] via-white to-[#f2faf0]">
    <div class="container mx-auto px-4 py-12 lg:py-16 space-y-8">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-[#f49926]">Collections</p>
          <h2 class="text-3xl font-semibold text-[#254a29]">Des recettes pour chaque moment</h2>
        </div>
        <Link :href="route('products')" class="text-sm font-semibold text-[#f49926]">Explorer toutes les catégories</Link>
      </div>

      <div class="grid md:grid-cols-3 gap-4">
        <article
          v-for="category in categories"
          :key="category.slug"
          class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col gap-3 hover:border-[#f49926]/40"
        >
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ category.slug }}</p>
          <h3 class="text-xl font-semibold text-[#254a29]">{{ category.name }}</h3>
          <p class="text-sm text-slate-500 flex-1">{{ category.description }}</p>
          <Link :href="route('products.category', category.slug)" class="text-sm font-semibold text-[#f49926]">
            Voir les jus
          </Link>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
})
</script>
