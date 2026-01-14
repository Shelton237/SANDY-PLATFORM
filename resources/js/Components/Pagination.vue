<template>
  <nav v-if="links.length > 3" class="flex items-center justify-end gap-2 text-sm">
    <template v-for="(link, index) in links" :key="index">
      <span
        v-if="link.label === '&hellip;' || link.label === '...'"
        class="px-3 py-2 text-slate-400"
        v-html="link.label"
      />
      <Link
        v-else
        :href="link.url || ''"
        class="px-3 py-2 rounded-lg border text-slate-600 hover:text-[#254a29]"
        :class="{
          'border-[#f49926] bg-[#fef4e7] text-[#f49926] font-semibold': link.active,
          'pointer-events-none opacity-50': !link.url
        }"
        v-html="link.label"
      />
    </template>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: {
    type: Array,
    default: () => []
  }
})
</script>
