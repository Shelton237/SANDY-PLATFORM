<template>
  <section class="bg-white">
    <div class="container mx-auto px-4 py-12 lg:py-20 space-y-8">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-[#f49926]">Nos recettes</p>
          <h2 class="text-3xl font-semibold text-[#254a29]">Best-sellers du moment</h2>
          <p class="text-sm text-slate-500">Pressés le matin à Yaoundé, livrés dans la journée.</p>
        </div>
        <Link :href="route('products')" class="inline-flex items-center gap-2 text-[#f49926] font-semibold">
          Voir le catalogue
          <i class="bi bi-arrow-up-right"></i>
        </Link>
      </div>

      <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <article
          v-for="product in products"
          :key="product.id"
          class="rounded-3xl border border-slate-100 bg-white shadow-sm hover:shadow-lg transition flex flex-col"
        >
          <div class="relative">
            <img :src="product.image" :alt="product.name" class="w-full h-48 object-cover rounded-t-3xl" />
            <span
              v-if="product.is_new"
              class="absolute top-4 left-4 rounded-full bg-white/90 text-[#254a29] px-3 py-1 text-xs font-semibold"
            >
              Nouveauté
            </span>
          </div>
          <div class="p-5 flex flex-col flex-1">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ product.tagline }}</p>
            <h3 class="text-xl font-semibold text-[#254a29] mt-2">{{ product.name }}</h3>
            <p class="text-[#f49926] text-lg font-semibold mt-4">{{ formatPrice(product.price) }}</p>
            <div class="mt-auto pt-4 flex items-center justify-between">
              <Link :href="route('products.show', product.slug)" class="text-sm font-semibold text-[#f49926]">
                Découvrir
              </Link>
              <Link
                :href="route('products.show', product.slug)"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-[#f49926]/30 text-[#f49926]"
              >
                <i class="bi bi-plus-lg"></i>
              </Link>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  products: {
    type: Array,
    default: () => [],
  },
})

const formatPrice = (price) => {
  const value = Number(price) || 0
  return `${value.toFixed(0)} FCFA`
}
</script>
