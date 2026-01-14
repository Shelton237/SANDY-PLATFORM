<template>
  <AdminLayout title="Commandes">
    <Head title="Commandes" />

    <div class="grid gap-4 md:grid-cols-4 mb-6">
      <article v-for="card in statCards" :key="card.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ card.value }}</p>
      </article>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 mb-6">
      <form class="flex flex-wrap gap-3 items-center" @submit.prevent="applyFilters">
        <input v-model="search" type="search" placeholder="Numero commande ou client" class="px-4 py-2 rounded-2xl border border-slate-200" />
        <select v-model="status" class="px-4 py-2 rounded-2xl border border-slate-200">
          <option value="">Statut</option>
          <option v-for="(label, value) in statusOptions" :key="value" :value="value">
            {{ label }}
          </option>
        </select>
        <select v-model="payment" class="px-4 py-2 rounded-2xl border border-slate-200">
          <option value="">Paiement</option>
          <option v-for="(label, value) in paymentOptions" :key="value" :value="value">
            {{ label }}
          </option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-2xl bg-[#f49926] text-white font-semibold">
          Filtrer
        </button>
      </form>
    </div>

    <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Backoffice</p>
          <h2 class="text-xl font-semibold text-[#254a29]">Creer une commande</h2>
          <p class="text-sm text-slate-500">Renseignez une commande B2B ou retail sans passer par le tunnel client.</p>
        </div>
        <div class="text-right">
          <p class="text-xs text-slate-500">Total estime</p>
          <p class="text-2xl font-semibold text-[#f49926]">{{ formatPrice(grandTotal) }}</p>
        </div>
      </div>

      <form v-if="hasProducts" class="mt-6 space-y-6" @submit.prevent="submitOrder">
        <div class="grid gap-4 md:grid-cols-3">
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Nom du client</label>
            <input
              v-model="orderForm.customer_name"
              type="text"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="Entreprise ou particulier"
              required
            />
            <p v-if="orderForm.errors.customer_name" class="text-xs text-red-500 mt-1">{{ orderForm.errors.customer_name }}</p>
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Email</label>
            <input
              v-model="orderForm.customer_email"
              type="email"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="client@exemple.com"
              required
            />
            <p v-if="orderForm.errors.customer_email" class="text-xs text-red-500 mt-1">{{ orderForm.errors.customer_email }}</p>
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Telephone</label>
            <input
              v-model="orderForm.customer_phone"
              type="text"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="+242..."
            />
            <p v-if="orderForm.errors.customer_phone" class="text-xs text-red-500 mt-1">{{ orderForm.errors.customer_phone }}</p>
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Adresse</label>
            <input
              v-model="orderForm.address_line1"
              type="text"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="Adresse de livraison"
            />
            <p v-if="orderForm.errors.address_line1" class="text-xs text-red-500 mt-1">{{ orderForm.errors.address_line1 }}</p>
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Ville</label>
            <input
              v-model="orderForm.city"
              type="text"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="Ville"
            />
            <p v-if="orderForm.errors.city" class="text-xs text-red-500 mt-1">{{ orderForm.errors.city }}</p>
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Statut</label>
            <select
              v-model="orderForm.status"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
            >
              <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
            <p v-if="orderForm.errors.status" class="text-xs text-red-500 mt-1">{{ orderForm.errors.status }}</p>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <div>
              <p class="text-sm font-semibold text-slate-700">Produits</p>
              <p class="text-xs text-slate-500">Composez le panier et ajustez les quantites.</p>
            </div>
            <button type="button" class="text-sm font-semibold text-[#f49926]" @click="addItem">
              + Ajouter une ligne
            </button>
          </div>
          <p v-if="orderForm.errors.items" class="text-xs text-red-500 mb-3">{{ orderForm.errors.items }}</p>

          <div
            v-for="(item, index) in orderForm.items"
            :key="`order-item-${index}`"
            class="grid gap-3 md:grid-cols-5 items-start border border-slate-100 rounded-2xl bg-white p-3 mb-3 last:mb-0"
          >
            <div class="md:col-span-3">
              <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Produit</label>
              <select
                v-model="item.product_id"
                class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              >
                <option v-for="product in products" :key="product.id" :value="product.id">
                  {{ product.name }} - {{ formatPrice(product.price) }}
                </option>
              </select>
              <p v-if="orderForm.errors[`items.${index}.product_id`]" class="text-xs text-red-500 mt-1">
                {{ orderForm.errors[`items.${index}.product_id`] }}
              </p>
            </div>
            <div>
              <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Quantite</label>
              <input
                v-model.number="item.quantity"
                type="number"
                min="1"
                class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              />
              <p v-if="orderForm.errors[`items.${index}.quantity`]" class="text-xs text-red-500 mt-1">
                {{ orderForm.errors[`items.${index}.quantity`] }}
              </p>
            </div>
            <div class="space-y-2">
              <p class="text-xs uppercase tracking-wide text-slate-500">Total</p>
              <p class="font-semibold text-[#254a29]">{{ formatPrice(lineTotal(item)) }}</p>
              <button
                type="button"
                class="text-xs text-red-500"
                @click="removeItem(index)"
                v-if="orderForm.items.length > 1"
              >
                Supprimer
              </button>
            </div>
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Paiement</label>
            <select
              v-model="orderForm.payment_status"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
            >
              <option v-for="(label, value) in paymentOptions" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
            <p v-if="orderForm.errors.payment_status" class="text-xs text-red-500 mt-1">{{ orderForm.errors.payment_status }}</p>
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Frais de livraison</label>
            <input
              v-model.number="orderForm.delivery_fee"
              type="number"
              min="0"
              step="100"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="0 FCFA"
            />
            <p v-if="orderForm.errors.delivery_fee" class="text-xs text-red-500 mt-1">{{ orderForm.errors.delivery_fee }}</p>
          </div>
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Date de livraison</label>
            <input
              v-model="orderForm.delivery.scheduled_date"
              type="date"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
            />
            <p v-if="orderForm.errors['delivery.scheduled_date']" class="text-xs text-red-500 mt-1">
              {{ orderForm.errors['delivery.scheduled_date'] }}
            </p>
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <div>
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Creneau</label>
            <input
              v-model="orderForm.delivery.time_window"
              type="text"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="10h-12h"
            />
            <p v-if="orderForm.errors['delivery.time_window']" class="text-xs text-red-500 mt-1">
              {{ orderForm.errors['delivery.time_window'] }}
            </p>
          </div>
          <div class="md:col-span-2">
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Notes</label>
            <textarea
              v-model="orderForm.notes"
              rows="2"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="Instructions internes, conditions commerciales..."
            ></textarea>
            <p v-if="orderForm.errors.notes" class="text-xs text-red-500 mt-1">{{ orderForm.errors.notes }}</p>
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <div class="md:col-span-2">
            <label class="text-xs uppercase tracking-wide text-slate-500 block mb-1">Notes livraison</label>
            <textarea
              v-model="orderForm.delivery.notes"
              rows="2"
              class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"
              placeholder="Precisions pour le coursier"
            ></textarea>
            <p v-if="orderForm.errors['delivery.notes']" class="text-xs text-red-500 mt-1">
              {{ orderForm.errors['delivery.notes'] }}
            </p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            <div class="flex items-center justify-between">
              <span>Sous-total</span>
              <span class="font-semibold text-[#254a29]">{{ formatPrice(subtotal) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Livraison</span>
              <span class="font-semibold text-[#254a29]">{{ formatPrice(orderForm.delivery_fee || 0) }}</span>
            </div>
            <div class="flex items-center justify-between text-base font-semibold text-[#f49926] mt-2">
              <span>Total</span>
              <span>{{ formatPrice(grandTotal) }}</span>
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            class="inline-flex items-center justify-center rounded-2xl bg-[#254a29] px-6 py-2 text-sm font-semibold text-white transition hover:bg-[#1d3c22] disabled:opacity-60"
            :disabled="orderForm.processing"
          >
            {{ orderForm.processing ? 'Enregistrement...' : 'Creer la commande' }}
          </button>
        </div>
      </form>

      <p v-else class="mt-6 text-sm text-red-500">
        Ajoutez d'abord des produits pour pouvoir saisir une commande depuis le backoffice.
      </p>
    </section>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-widest text-slate-500">
          <tr>
            <th class="px-6 py-3 text-left">Commande</th>
            <th class="px-6 py-3 text-left">Client</th>
            <th class="px-6 py-3 text-left">Statut</th>
            <th class="px-6 py-3 text-left">Paiement</th>
            <th class="px-6 py-3 text-left">Total</th>
            <th class="px-6 py-3 text-left">Livraison</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders.data" :key="order.id" class="border-b border-slate-100 last:border-none">
            <td class="px-6 py-4">
              <p class="font-semibold text-[#254a29]">{{ order.number }}</p>
              <p class="text-xs text-slate-500">{{ order.placed_at }}</p>
            </td>
            <td class="px-6 py-4">
              <p class="font-semibold text-slate-700">{{ order.customer_name }}</p>
              <p class="text-xs text-slate-500">{{ order.customer_email }}</p>
            </td>
            <td class="px-6 py-4">
              <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                {{ statusOptions[order.status] || order.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-slate-500">{{ paymentOptions[order.payment_status] }}</td>
            <td class="px-6 py-4 font-semibold text-[#f49926]">{{ formatPrice(order.total) }}</td>
            <td class="px-6 py-4 text-slate-500">
              {{ order.delivery?.status ? deliveryStatuses[order.delivery.status] : 'Non planifiee' }}
            </td>
            <td class="px-6 py-4 text-right">
              <Link :href="route('admin.orders.show', order.id)" class="text-[#f49926] font-semibold">
                Details
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-slate-100">
        <Pagination :links="orders.links" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  orders: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  stats: {
    type: Object,
    default: () => ({})
  },
  statusOptions: {
    type: Object,
    default: () => ({})
  },
  paymentOptions: {
    type: Object,
    default: () => ({})
  },
  deliveryStatuses: {
    type: Object,
    default: () => ({})
  },
  products: {
    type: Array,
    default: () => []
  }
})

const search = ref(props.filters.search ?? '')
const status = ref(props.filters.status ?? '')
const payment = ref(props.filters.payment_status ?? '')

const products = computed(() => props.products)

const formatPrice = (value) => {
  const number = Number(value) || 0
  return `${number.toFixed(0)} FCFA`
}

const statCards = computed(() => [
  { label: 'En attente', value: props.stats.pending ?? 0 },
  { label: 'Production', value: props.stats.production ?? 0 },
  { label: 'Pretes', value: props.stats.ready ?? 0 },
  { label: 'Revenu total', value: formatPrice(props.stats.revenue ?? 0) }
])

const applyFilters = () => {
  router.get(
    route('admin.orders.index'),
    {
      search: search.value || undefined,
      status: status.value || undefined,
      payment_status: payment.value || undefined
    },
    {
      preserveState: true,
      replace: true
    }
  )
}

const firstStatusKey = Object.keys(props.statusOptions ?? {})[0] ?? 'pending'
const firstPaymentKey = Object.keys(props.paymentOptions ?? {})[0] ?? 'pending'

const createItem = () => ({
  product_id: products.value[0]?.id ?? '',
  quantity: 1
})

const createDelivery = () => ({
  scheduled_date: '',
  time_window: '',
  notes: ''
})

const orderForm = useForm({
  customer_name: '',
  customer_email: '',
  customer_phone: '',
  address_line1: '',
  city: '',
  notes: '',
  delivery_fee: 0,
  status: firstStatusKey,
  payment_status: firstPaymentKey,
  items: [createItem()],
  delivery: createDelivery()
})

const hasProducts = computed(() => products.value.length > 0)

const productMap = computed(() =>
  products.value.reduce((acc, product) => {
    acc[product.id] = product
    return acc
  }, {})
)

const lineTotal = (item) => {
  const product = productMap.value[item.product_id]
  const quantity = Number(item.quantity) || 0
  if (!product) {
    return 0
  }
  return Number(product.price) * quantity
}

const subtotal = computed(() => orderForm.items.reduce((sum, item) => sum + lineTotal(item), 0))
const grandTotal = computed(() => subtotal.value + (Number(orderForm.delivery_fee) || 0))

const addItem = () => {
  if (!hasProducts.value) {
    return
  }
  orderForm.items.push(createItem())
}

const removeItem = (index) => {
  if (orderForm.items.length === 1) {
    return
  }
  orderForm.items.splice(index, 1)
}

const resetOrderForm = () => {
  orderForm.reset()
  orderForm.items = [createItem()]
  orderForm.delivery = createDelivery()
  orderForm.delivery_fee = 0
}

const submitOrder = () => {
  if (!hasProducts.value) {
    return
  }
  orderForm.post(route('admin.orders.store'), {
    preserveScroll: true,
    onSuccess: () => resetOrderForm()
  })
}
</script>
