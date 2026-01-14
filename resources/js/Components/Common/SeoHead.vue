<template>
  <Head>
    <title>{{ fullTitle }}</title>
    <meta name="description" :content="resolvedDescription" />
    <meta name="keywords" :content="resolvedKeywords" />
    <meta name="author" :content="siteName" />
    <meta property="og:type" :content="type" />
    <meta property="og:title" :content="fullTitle" />
    <meta property="og:description" :content="resolvedDescription" />
    <meta property="og:image" :content="resolvedImage" />
    <meta property="og:url" :content="canonicalUrl" />
    <meta property="og:site_name" :content="siteName" />
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:title" :content="fullTitle" />
    <meta property="twitter:description" :content="resolvedDescription" />
    <meta property="twitter:image" :content="resolvedImage" />
    <link rel="canonical" :href="canonicalUrl" />
    <script v-if="structuredData" type="application/ld+json">
      {{ structuredJson }}
    </script>
  </Head>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: null
  },
  description: {
    type: String,
    default: null
  },
  keywords: {
    type: String,
    default: null
  },
  image: {
    type: String,
    default: null
  },
  url: {
    type: String,
    default: null
  },
  type: {
    type: String,
    default: 'website'
  },
  structuredData: {
    type: [Object, Array],
    default: null
  }
})

const page = usePage()
const defaults = computed(() => page.props.seo || {})
const siteName = computed(() => defaults.value.site_name || 'Sandy Juice')

const resolveOrigin = () => {
  if (typeof window !== 'undefined' && window.location) {
    return window.location.origin
  }
  return defaults.value.base_url || ''
}

const baseUrl = computed(() => defaults.value.base_url || resolveOrigin())

const fullTitle = computed(() => {
  if (props.title) {
    return `${props.title} | ${siteName.value}`
  }
  return siteName.value
})

const resolvedDescription = computed(() => props.description || defaults.value.description || siteName.value)
const resolvedKeywords = computed(() => props.keywords || defaults.value.keywords || '')
const resolvedImage = computed(() => props.image || defaults.value.image || '/images/logo.png')

const canonicalUrl = computed(() => {
  if (props.url) return props.url
  const relative = page.url || '/'
  return `${baseUrl.value}${relative}`
})

const structuredJson = computed(() => {
  if (!props.structuredData) return null
  try {
    return JSON.stringify(props.structuredData, null, 2)
  } catch (error) {
    return null
  }
})
</script>
