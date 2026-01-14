<template>
  <AppLayout>
    <Head :title="title" />

    <section class="bg-white py-16">
      <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-14 max-w-3xl mx-auto">
          <p class="text-sm uppercase tracking-[0.3em] text-amber-500 mb-3">Nos valeurs</p>
          <h1 class="text-4xl font-bold text-emerald-900 mb-4">{{ title }}</h1>
          <p class="text-lg text-gray-600 leading-relaxed">
            {{ description }}
          </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
          <article
            v-for="valueItem in values"
            :key="valueItem.title"
            class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 rounded-3xl p-8 shadow-sm hover:shadow-xl transition"
          >
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 text-2xl mr-4">
                <i :class="valueItem.icon"></i>
              </div>
              <h2 class="text-2xl font-semibold text-emerald-900">{{ valueItem.title }}</h2>
            </div>
            <p class="text-gray-600 leading-relaxed">
              {{ valueItem.description }}
            </p>
          </article>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  title: {
    type: String,
    default: 'Nos valeurs'
  },
  description: {
    type: String,
    default: ''
  },
  values: {
    type: Array,
    default: () => []
  }
});
</script>
