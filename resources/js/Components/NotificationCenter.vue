<template>
  <div v-if="toasts.length" class="fixed top-6 left-6 z-[70] space-y-3 max-w-sm pointer-events-none">
    <transition-group name="toast" tag="div">
      <article
        v-for="toast in toasts"
        :key="toast.id"
        class="pointer-events-auto rounded-2xl border px-4 py-3 shadow-lg flex items-start gap-3 text-sm"
        :class="toastClass(toast.type)"
      >
        <div class="text-lg">
          <i :class="iconClass(toast.type)"></i>
        </div>
        <div class="flex-1">
          <p class="font-semibold">{{ toast.title }}</p>
          <p class="text-xs opacity-80 whitespace-pre-line">{{ toast.message }}</p>
        </div>
        <button
          type="button"
          class="text-xs opacity-60 hover:opacity-100"
          @click="dismiss(toast.id)"
        >
          <i class="bi bi-x-lg"></i>
        </button>
      </article>
    </transition-group>
  </div>
  <slot />
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const AUTO_HIDE = 4500
const page = usePage()
const flash = computed(() => page.props.flash || {})
const toasts = ref([])

let seed = 0

const pushToast = (type, message) => {
  if (!message) {
    return
  }

  const id = `${Date.now()}-${seed++}`
  const title = {
    success: 'Succès',
    error: 'Erreur',
    info: 'Information',
  }[type] || 'Notification'

  toasts.value.push({ id, type, message, title })
  setTimeout(() => dismiss(id), AUTO_HIDE)
}

const dismiss = (id) => {
  toasts.value = toasts.value.filter((toast) => toast.id !== id)
}

watch(
  flash,
  (value) => {
    if (!value) return
    pushToast('success', value.success)
    pushToast('error', value.error)
    pushToast('info', value.info)
  },
  { immediate: true, deep: true }
)

const toastClass = (type) => {
  switch (type) {
    case 'error':
      return 'bg-rose-50 border-rose-100 text-rose-700'
    case 'info':
      return 'bg-sky-50 border-sky-100 text-sky-700'
    default:
      return 'bg-emerald-50 border-emerald-100 text-emerald-700'
  }
}

const iconClass = (type) => {
  switch (type) {
    case 'error':
      return 'bi bi-exclamation-octagon-fill'
    case 'info':
      return 'bi bi-info-circle-fill'
    default:
      return 'bi bi-check-circle-fill'
  }
}
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.25s ease;
}
.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
