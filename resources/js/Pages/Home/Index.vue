<template>
  <AppLayout>
    <SeoHead
      title="Jus naturels pressés à froid — Sandy Juice"
      description="Découvrez nos jus 100% naturels pressés à froid. Commandez en ligne et recevez à domicile."
    />

    <!-- Hero -->
    <section class="bg-gradient-to-br from-[#254a29] to-[#3a6b3f] text-white py-20 px-4">
      <div class="container mx-auto max-w-4xl text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight">
          100% Naturel.<br/>
          <span class="text-[#f49926]">Pressé à froid.</span>
        </h1>
        <p class="text-lg md:text-xl text-white/80 mb-8 max-w-xl mx-auto">
          Des jus frais livrés directement chez vous, sans conservateurs, sans compromis.
        </p>
        <Link
          :href="route('products')"
          class="inline-flex items-center gap-2 bg-[#f49926] hover:bg-[#f7a345] text-white font-semibold py-4 px-8 rounded-xl text-lg transition-all duration-300 shadow-lg hover:shadow-[#f49926]/40 hover:scale-105"
        >
          <i class="bi bi-grid"></i>
          Voir tous les produits
          <i class="bi bi-arrow-right"></i>
        </Link>
      </div>
    </section>

    <!-- Catégories -->
    <section v-if="categories.length" class="py-14 px-4 bg-white">
      <div class="container mx-auto max-w-6xl">
        <h2 class="text-2xl font-bold text-[#254a29] mb-8 text-center">Nos catégories</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
          <Link
            v-for="cat in categories"
            :key="cat.id"
            :href="route('products.category', cat.slug)"
            class="flex flex-col items-center p-4 rounded-2xl border border-gray-100 hover:border-[#f49926]/40 hover:bg-[#fef4e7] transition-all group text-center"
          >
            <div class="w-12 h-12 rounded-full bg-[#fef4e7] flex items-center justify-center mb-3 group-hover:bg-[#f49926]/20 transition-colors">
              <i :class="cat.icon" class="text-xl text-[#254a29]"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-[#254a29]">{{ cat.name }}</span>
          </Link>
        </div>
      </div>
    </section>

    <!-- Produits vedettes -->
    <section class="py-14 px-4 bg-slate-50">
      <div class="container mx-auto max-w-6xl">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-2xl font-bold text-[#254a29]">Nos produits</h2>
          <Link :href="route('products')" class="text-sm text-[#f49926] hover:underline font-medium flex items-center gap-1">
            Tout voir <i class="bi bi-arrow-right"></i>
          </Link>
        </div>

        <div v-if="products.length" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          <Link
            v-for="product in products"
            :key="product.id"
            :href="route('products.show', product.slug)"
            class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all group border border-gray-100"
          >
            <div class="relative aspect-square overflow-hidden bg-[#fef4e7]">
              <img
                v-if="product.image"
                :src="product.image"
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div v-else class="w-full h-full flex items-center justify-center">
                <i class="bi bi-cup-straw text-4xl text-[#f49926]/40"></i>
              </div>
              <span v-if="product.is_new" class="absolute top-3 left-3 bg-[#f49926] text-white text-xs font-semibold px-2 py-1 rounded-full">
                Nouveau
              </span>
            </div>
            <div class="p-4">
              <h3 class="font-semibold text-[#254a29] text-sm mb-1 group-hover:text-[#f49926] transition-colors">{{ product.name }}</h3>
              <p v-if="product.tagline" class="text-xs text-gray-500 mb-3 line-clamp-1">{{ product.tagline }}</p>
              <div class="flex items-center justify-between">
                <span class="font-bold text-[#254a29]">{{ product.price.toLocaleString() }} FCFA</span>
                <span class="text-xs text-white bg-[#254a29] px-3 py-1 rounded-full group-hover:bg-[#f49926] transition-colors">
                  Commander
                </span>
              </div>
            </div>
          </Link>
        </div>

        <div v-else class="text-center py-12 text-gray-500">
          <i class="bi bi-cup-straw text-5xl text-gray-200 block mb-3"></i>
          Aucun produit disponible pour le moment.
        </div>
      </div>
    </section>

    <!-- CTA bas de page -->
    <section class="bg-[#254a29] text-white py-16 px-4 text-center">
      <div class="max-w-xl mx-auto">
        <h2 class="text-3xl font-bold mb-3">Prêt à commander ?</h2>
        <p class="text-white/70 mb-8">Livraison rapide à Yaoundé et Douala. Paiement à la livraison disponible.</p>
        <Link
          :href="route('products')"
          class="inline-flex items-center gap-2 bg-[#f49926] hover:bg-[#f7a345] text-white font-semibold py-4 px-8 rounded-xl transition-all hover:scale-105 shadow-lg"
        >
          <i class="bi bi-cart3"></i>
          Commander maintenant
        </Link>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SeoHead from '@/Components/Common/SeoHead.vue'

const props = defineProps({
  featuredProducts:   { type: Array, default: () => [] },
  featuredCategories: { type: Array, default: () => [] },
})

const products   = computed(() => props.featuredProducts ?? [])
const categories = computed(() => props.featuredCategories ?? [])
</script>
