<template>
  <AppLayout>
    <Head :title="title" />

    <section class="bg-gradient-to-b from-emerald-50 to-white py-16">
      <div class="container mx-auto px-4 max-w-5xl">
        <header class="mb-12 text-center">
          <p class="text-sm uppercase tracking-[0.3em] text-amber-500 mb-3">Démarche qualité</p>
          <h1 class="text-4xl font-bold text-emerald-900 mb-4">{{ title }}</h1>
          <p class="text-lg text-gray-600 leading-relaxed">{{ description }}</p>
        </header>

        <div class="grid md:grid-cols-2 gap-10">
          <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-emerald-900 mb-6 flex items-center">
              <i class="bi bi-patch-check-fill text-amber-500 mr-3 text-2xl"></i>
              Certifications
            </h2>
            <ul class="space-y-4">
              <li
                v-for="certification in certifications"
                :key="certification"
                class="flex items-start text-gray-600"
              >
                <span class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-700 rounded-full mr-4 text-sm font-semibold">
                  {{ certification.slice(0, 1) }}
                </span>
                <span class="pt-1">{{ certification }}</span>
              </li>
            </ul>
          </div>

          <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-emerald-900 mb-6 flex items-center">
              <i class="bi bi-diagram-3-fill text-amber-500 mr-3 text-2xl"></i>
              Notre processus
            </h2>
            <ol class="space-y-4">
              <li
                v-for="(step, index) in processSteps"
                :key="step"
                class="flex items-start text-gray-600"
              >
                <span class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-full mr-4 text-sm font-semibold">
                  {{ index + 1 }}
                </span>
                <span class="pt-1">{{ step }}</span>
              </li>
            </ol>
          </div>
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
    default: 'Notre Démarche Qualité'
  },
  description: {
    type: String,
    default: ''
  },
  certifications: {
    type: Array,
    default: () => []
  },
  processSteps: {
    type: Array,
    default: () => []
  }
});
</script>
