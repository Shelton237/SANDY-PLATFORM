<template>
  <AppLayout>
    <Head :title="title" />

    <section class="bg-white py-16">
      <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-14">
          <p class="text-sm uppercase tracking-[0.3em] text-amber-500 mb-3">Notre histoire</p>
          <h1 class="text-4xl font-bold text-emerald-900 mb-4">{{ title }}</h1>
          <p class="text-lg text-gray-600 leading-relaxed">
            {{ description }}
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <article
            v-for="member in teamMembers"
            :key="member.name"
            class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-lg transition-shadow"
          >
            <p class="text-xs uppercase tracking-[0.3em] text-[#f49926] mb-1">{{ member.position }}</p>
            <h2 class="text-xl font-semibold text-[#254a29] mb-3">{{ member.name }}</h2>
            <p class="text-sm text-slate-600 leading-relaxed">{{ member.bio }}</p>
          </article>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6 mt-10 space-y-3 text-center">
          <p class="text-xs uppercase tracking-[0.3em] text-[#f49926]">Notre mission</p>
          <h3 class="text-2xl font-semibold text-[#254a29]">Créer le pipeline complet du jus naturel camerounais</h3>
          <p class="text-sm text-slate-500">
            Approvisionnement local, production quotidienne à Yaoundé, commercialisation omnicanale et livraison express.
          </p>
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
    default: 'À propos de Sandy Juice'
  },
  description: {
    type: String,
    default: ''
  },
  teamMembers: {
    type: Array,
    default: () => []
  }
});
</script>
