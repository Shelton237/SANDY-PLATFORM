<template>
  <div class="space-y-2">
    <div class="flex flex-wrap items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
      <button v-for="action in actions" :key="action.label" type="button" class="rounded-lg border border-transparent px-2 py-1 font-semibold uppercase transition hover:border-slate-300 hover:bg-white" @click="applyAction(action)">
        <span v-if="action.icon === 'B'" class="font-bold">B</span>
        <span v-else-if="action.icon === 'I'" class="italic">I</span>
        <span v-else-if="action.icon === 'U'" class="underline">U</span>
        <span v-else>{{ action.icon }}</span>
      </button>
    </div>

    <div
      ref="editorRef"
      class="rich-text-editor min-h-[160px] w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus-within:border-[#f49926] focus-within:ring-1 focus-within:ring-[#f49926]/30"
      contenteditable="true"
      :data-placeholder="placeholder"
      :style="{ minHeight }"
      @input="onInput"
      @blur="$emit('blur')"
    ></div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  minHeight: {
    type: String,
    default: '180px'
  }
})

const emit = defineEmits(['update:modelValue', 'blur'])
const editorRef = ref(null)
const localValue = ref(props.modelValue ?? '')

const actions = [
  { icon: 'B', command: 'bold', label: 'Gras' },
  { icon: 'I', command: 'italic', label: 'Italique' },
  { icon: 'U', command: 'underline', label: 'Souligner' },
  { icon: '•', command: 'insertUnorderedList', label: 'Liste' },
  { icon: 'Lien', command: 'link', label: 'Lien' },
  { icon: 'Clr', command: 'removeFormat', label: 'Nettoyer' }
]

const setEditorContent = (value) => {
  if (editorRef.value && editorRef.value.innerHTML !== value) {
    editorRef.value.innerHTML = value || ''
  }
}

onMounted(() => setEditorContent(localValue.value))

watch(
  () => props.modelValue,
  (value) => {
    const nextValue = value ?? ''
    if (nextValue !== localValue.value) {
      localValue.value = nextValue
      setEditorContent(nextValue)
    }
  }
)

const syncValue = () => {
  if (!editorRef.value) return
  const html = editorRef.value.innerHTML
  localValue.value = html
  emit('update:modelValue', html)
}

const onInput = () => {
  syncValue()
}

const applyAction = (action) => {
  if (!editorRef.value) return
  editorRef.value.focus()

  if (action.command === 'link') {
    const url = prompt('Lien (https://...)')
    if (url) {
      document.execCommand('createLink', false, url)
    }
  } else if (action.command === 'removeFormat') {
    document.execCommand('removeFormat')
    document.execCommand('unlink')
  } else {
    document.execCommand(action.command, false, null)
  }

  syncValue()
}
</script>

<style scoped>
.rich-text-editor:focus-visible {
  outline: none;
}

.rich-text-editor:empty:before {
  content: attr(data-placeholder);
  color: #94a3b8;
  pointer-events: none;
}

.rich-text-editor ul {
  list-style: disc;
  margin-left: 1.25rem;
}
</style>
