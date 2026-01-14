<template>
  <AdminLayout :title="`Edition ${form.name}`">
    <Head :title="`Editer ${form.name}`" />

    <div class="max-w-5xl mx-auto bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-[#254a29]">Editer {{ form.name }}</h1>
        <Link
          as="button"
          method="delete"
          :href="route('admin.products.destroy', product.id)"
          class="text-sm text-red-500 hover:text-red-600"
        >
          Supprimer
        </Link>
      </div>
      <ProductForm :form="form" :statuses="statuses" :categories="categories" submit-label="Mettre à jour" :on-submit="submit" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ProductForm from './Partials/ProductForm.vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  statuses: {
    type: Object,
    default: () => ({})
  },
  categories: {
    type: Array,
    default: () => []
  }
})

const form = useForm({ ...props.product })

const submit = () => {
  form.put(route('admin.products.update', props.product.id))
}
</script>
