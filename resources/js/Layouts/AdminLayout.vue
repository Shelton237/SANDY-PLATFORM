<template>
  <div class="min-h-screen bg-slate-50 flex">
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-slate-200">
      <div class="px-6 py-5 border-b border-slate-200">
        <Link :href="route('admin.dashboard')" class="flex items-center gap-3">
          <span class="w-10 h-10 rounded-xl bg-[#fef4e7] flex items-center justify-center text-[#f49926] font-bold">SJ</span>
          <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Backoffice</p>
            <p class="text-lg font-semibold text-[#254a29]">Sandy Platform</p>
          </div>
        </Link>
      </div>
      <nav class="flex-1 py-4">
        <Link
          v-for="item in navigation"
          :key="item.route"
          :href="route(item.route)"
          class="flex items-center gap-3 px-6 py-3 text-sm font-medium transition"
          :class="isActive(item.routePrefix) ? 'text-[#f49926] bg-[#fef4e7]' : 'text-slate-500 hover:text-[#254a29]'"
        >
          <i :class="item.icon" class="text-lg"></i>
          {{ item.label }}
        </Link>
      </nav>
    </aside>

    <div class="flex-1 flex flex-col">
      <header class="bg-white border-b border-slate-200 px-4 lg:px-8 py-4 flex items-center justify-between gap-3">
        <button
          type="button"
          class="lg:hidden inline-flex items-center justify-center h-10 w-10 rounded-2xl border border-slate-200 text-slate-500 hover:text-[#254a29]"
          @click="mobileNavOpen = true"
        >
          <i class="bi bi-list text-lg"></i>
        </button>
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Espace admin</p>
          <h1 class="text-2xl font-semibold text-[#254a29]">
            {{ computedTitle }}
          </h1>
        </div>
        <div class="flex items-center gap-4">
          <div class="hidden sm:block text-right">
            <p class="text-sm font-semibold text-[#254a29]">{{ user?.name }}</p>
            <p class="text-xs text-slate-500">{{ user?.email }}</p>
          </div>
          <Link :href="route('logout')" method="post" as="button" class="px-3 py-2 text-sm text-slate-500 hover:text-[#f49926]">
            <i class="bi bi-box-arrow-right mr-1"></i>
            Déconnexion
          </Link>
        </div>
      </header>

      <main class="flex-1 p-4 lg:p-8">
        <slot />
      </main>
    </div>

    <transition>
      <div
        v-if="mobileNavOpen"
        class="fixed inset-0 z-40 bg-black/30 lg:hidden"
        @click.self="mobileNavOpen = false"
      >
        <div class="absolute inset-y-0 left-0 w-72 bg-white p-6 shadow-xl">
          <div class="flex items-center justify-between mb-6">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Backoffice</p>
              <p class="text-lg font-semibold text-[#254a29]">Sandy Platform</p>
            </div>
            <button type="button" class="text-slate-400 hover:text-[#254a29]" @click="mobileNavOpen = false">
              <i class="bi bi-x-lg text-lg"></i>
            </button>
          </div>
          <nav class="space-y-2">
            <Link
              v-for="item in navigation"
              :key="item.route"
              :href="route(item.route)"
              class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition"
              :class="[
                isActive(item.routePrefix) ? 'text-[#f49926] bg-[#fef4e7]' : 'text-slate-500 hover:text-[#254a29] hover:bg-slate-100'
              ]"
              @click="mobileNavOpen = false"
            >
              <i :class="item.icon" class="text-lg"></i>
              {{ item.label }}
            </Link>
          </nav>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: 'Dashboard',
  },
})

const navigation = [
  { label: 'Dashboard', route: 'admin.dashboard', routePrefix: 'admin/dashboard', icon: 'bi bi-speedometer2' },
  { label: 'Produits', route: 'admin.products.index', routePrefix: 'admin/products', icon: 'bi bi-cup-straw' },
  { label: 'Catégories', route: 'admin.product-categories.index', routePrefix: 'admin/product-categories', icon: 'bi bi-tags' },
  { label: 'Commandes', route: 'admin.orders.index', routePrefix: 'admin/orders', icon: 'bi bi-receipt' },
]

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

const isActive = (prefix) => page.url.startsWith(`/${prefix}`)
const computedTitle = computed(() => props.title)
const mobileNavOpen = ref(false)
</script>
