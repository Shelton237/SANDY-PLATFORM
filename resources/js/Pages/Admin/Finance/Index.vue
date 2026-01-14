<template>
  <AdminLayout title="Comptabilité">
    <Head title="Comptabilité" />

    <div class="grid gap-4 md:grid-cols-4 mb-8">
      <article v-for="card in summaryCards" :key="card.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ card.label }}</p>
        <p class="mt-3 text-2xl font-semibold text-[#254a29]">{{ card.value }}</p>
        <p class="text-sm text-slate-500">{{ card.caption }}</p>
      </article>
    </div>

    <section class="grid gap-4 lg:grid-cols-3 mb-6">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Flux</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Pipeline financier</h2>
          </div>
          <form class="flex gap-3 text-sm" @submit.prevent="applyFilters">
            <select v-model="filters.stage" class="rounded-2xl border border-slate-200 px-3 py-2">
              <option value="">Étape</option>
              <option v-for="stage in stageOptions" :key="stage" :value="stage">
                {{ stageLabel(stage) }}
              </option>
            </select>
            <select v-model="filters.account" class="rounded-2xl border border-slate-200 px-3 py-2">
              <option value="">Compte</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.name }}
              </option>
            </select>
            <button type="submit" class="px-3 py-2 rounded-2xl border border-slate-200 text-xs uppercase tracking-[0.2em] text-slate-500">
              Filtrer
            </button>
          </form>
        </div>
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="stage in pipelineCards" :key="stage.label" class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
            <p class="text-xs uppercase text-slate-400">{{ stage.label }}</p>
            <p class="mt-2 text-lg font-semibold text-[#254a29]">{{ stage.value }}</p>
            <p class="text-xs text-slate-500">{{ stage.caption }}</p>
          </article>
        </div>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Alertes</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Budgets</h2>
          </div>
        </div>
        <ul class="space-y-3 text-sm" v-if="alerts.length">
          <li v-for="alert in alerts" :key="alert.id" class="rounded-2xl border border-amber-200 bg-amber-50 p-3">
            <div class="flex items-center justify-between">
              <p class="font-semibold text-[#b45309]">{{ alert.name }}</p>
              <span class="text-xs font-semibold text-[#b45309]">+{{ formatCurrency(alert.month_spent - alert.allocated_budget) }}</span>
            </div>
            <p class="text-xs text-slate-500">
              Budget {{ formatCurrency(alert.allocated_budget) }} • Déjà utilisé {{ formatCurrency(alert.month_spent) }}
            </p>
          </li>
        </ul>
        <p v-else class="text-sm text-slate-500">Aucune alerte budgétaire ce mois-ci.</p>
      </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2 mb-6">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Nouveau compte analytique</h2>
        <form class="grid gap-3" @submit.prevent="submitAccount">
          <input v-model="accountForm.name" type="text" placeholder="Nom du compte" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model="accountForm.code" type="text" placeholder="Code (ex: supply_costs)" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <select v-model="accountForm.type" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
              <option value="expense">Dépense</option>
              <option value="revenue">Revenu</option>
              <option value="asset">Actif</option>
            </select>
          </div>
          <div class="grid gap-3 md:grid-cols-2">
            <select v-model="accountForm.stage" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
              <option value="">Étape liée</option>
              <option v-for="stage in stageOptions" :key="stage" :value="stage">
                {{ stageLabel(stage) }}
              </option>
            </select>
            <input v-model.number="accountForm.allocated_budget" type="number" min="0" placeholder="Budget mensuel (FCFA)" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <textarea v-model="accountForm.description" rows="2" placeholder="Notes" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
          <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#254a29] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1d3c22]" :disabled="accountForm.processing">
            Créer le compte
          </button>
        </form>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-[#254a29] mb-4">Nouvelle écriture</h2>
        <form class="grid gap-3" @submit.prevent="submitTransaction">
          <div class="grid gap-3 md:grid-cols-2">
            <select v-model="transactionForm.finance_account_id" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
              <option value="">Compte</option>
              <option v-for="account in accounts" :key="account.id" :value="account.id">
                {{ account.name }}
              </option>
            </select>
            <select v-model="transactionForm.direction" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
              <option value="debit">Dépense (débit)</option>
              <option value="credit">Recette (crédit)</option>
            </select>
          </div>
          <input v-model="transactionForm.label" type="text" placeholder="Libellé" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
          <div class="grid gap-3 md:grid-cols-2">
            <input v-model.number="transactionForm.amount" type="number" min="0.1" step="0.01" placeholder="Montant" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30" required>
            <input v-model="transactionForm.occurred_on" type="date" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <div class="grid gap-3 md:grid-cols-2">
            <select v-model="transactionForm.stage" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
              <option value="">Étape</option>
              <option v-for="stage in stageOptions" :key="stage" :value="stage">
                {{ stageLabel(stage) }}
              </option>
            </select>
            <input v-model="transactionForm.payment_method" type="text" placeholder="Mode de paiement" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30">
          </div>
          <textarea v-model="transactionForm.notes" rows="2" placeholder="Notes" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#f49926]/30"></textarea>
          <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#f49926] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#f28700]" :disabled="transactionForm.processing">
            Enregistrer
          </button>
        </form>
      </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-3">
      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Flux</p>
            <h2 class="text-xl font-semibold text-[#254a29]">Transactions récentes</h2>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-left text-slate-400 uppercase tracking-widest text-xs border-b border-slate-100">
                <th class="py-2">Réf</th>
                <th class="py-2">Libellé</th>
                <th class="py-2">Compte</th>
                <th class="py-2">Montant</th>
                <th class="py-2">Statut</th>
                <th class="py-2 text-right">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transaction in transactionRows" :key="transaction.id" class="border-b border-slate-50 last:border-none">
                <td class="py-3 font-semibold text-[#254a29]">{{ transaction.reference }}</td>
                <td class="py-3">
                  <p class="font-medium text-slate-700">{{ transaction.label }}</p>
                  <p class="text-xs text-slate-500">
                    {{ stageLabel(transaction.stage) }} • {{ formatDate(transaction.occurred_on) }}
                  </p>
                </td>
                <td class="py-3 text-slate-500">{{ transaction.account?.name }}</td>
                <td class="py-3 font-semibold" :class="transaction.direction === 'credit' ? 'text-emerald-600' : 'text-rose-600'">
                  {{ transaction.direction === 'credit' ? '+' : '-' }}{{ formatCurrency(transaction.amount) }}
                </td>
                <td class="py-3">
                  <span class="px-3 py-1 text-xs rounded-full" :class="statusBadge(transaction.status)">
                    {{ statusLabel(transaction.status) }}
                  </span>
                </td>
                <td class="py-3 text-right">
                  <button
                    v-if="transaction.status !== 'cleared'"
                    class="text-xs font-semibold text-emerald-600"
                    @click="updateTransaction(transaction.id, 'cleared')"
                  >
                    Valider
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 flex items-center justify-between text-sm text-slate-500" v-if="transactions.links">
          <button
            class="px-3 py-2 rounded-2xl border border-slate-200"
            :disabled="!transactions.prev_page_url"
            @click="navigate(transactions.prev_page_url)"
          >
            Précédent
          </button>
          <p>Page {{ transactions.current_page }} / {{ transactions.last_page }}</p>
          <button
            class="px-3 py-2 rounded-2xl border border-slate-200"
            :disabled="!transactions.next_page_url"
            @click="navigate(transactions.next_page_url)"
          >
            Suivant
          </button>
        </div>
      </article>

      <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-[#254a29] mb-4">Comptes & soldes</h2>
        <ul class="space-y-3 text-sm">
          <li v-for="account in accounts" :key="account.id" class="flex items-center justify-between">
            <div>
              <p class="font-semibold text-[#254a29]">{{ account.name }}</p>
              <p class="text-xs text-slate-500">{{ stageLabel(account.stage) }}</p>
            </div>
            <div class="text-right">
              <p class="font-semibold" :class="account.type === 'revenue' ? 'text-emerald-600' : 'text-[#254a29]'">
                {{ formatCurrency(account.balance) }}
              </p>
              <p class="text-xs text-slate-500">
                {{ account.type === 'expense' ? 'Dép.' : 'Flux' }} {{ formatCurrency(account.debit_total || account.credit_total) }}
              </p>
            </div>
          </li>
        </ul>
      </article>
    </section>
  </AdminLayout>
</template>

<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import { computed, reactive } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  accounts: {
    type: Array,
    default: () => []
  },
  summary: {
    type: Object,
    default: () => ({})
  },
  pipelines: {
    type: Array,
    default: () => []
  },
  alerts: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  transactions: {
    type: Object,
    default: () => ({ data: [] })
  }
})

const stageOptions = ['supply', 'inventory', 'production', 'sales', 'delivery', 'admin']

const filters = reactive({
  stage: props.filters?.stage ?? '',
  account: props.filters?.account ?? ''
})

const accountForm = useForm({
  name: '',
  code: '',
  type: 'expense',
  stage: '',
  allocated_budget: '',
  description: ''
})

const transactionForm = useForm({
  finance_account_id: '',
  label: '',
  direction: 'debit',
  amount: 10000,
  occurred_on: new Date().toISOString().slice(0, 10),
  stage: '',
  payment_method: '',
  notes: '',
  status: 'cleared'
})

const summaryCards = computed(() => [
  {
    label: 'Revenu mensuel',
    value: formatCurrency(props.summary.month_revenue ?? 0),
    caption: 'Entrées confirmées'
  },
  {
    label: 'Dépenses mensuelles',
    value: formatCurrency(props.summary.month_expenses ?? 0),
    caption: 'Achats & opérations'
  },
  {
    label: 'Net cashflow',
    value: formatCurrency(props.summary.net_cash ?? 0),
    caption: 'Revenus - Dépenses'
  },
  {
    label: 'Dépenses du jour',
    value: formatCurrency(props.summary.today_spent ?? 0),
    caption: 'Sorties < 24h'
  }
])

const pipelineCards = computed(() =>
  props.pipelines.map((row) => ({
    label: stageLabel(row.stage) || 'Non affecté',
    value: `${formatCurrency(row.credits ?? 0)} / ${formatCurrency(row.debits ?? 0)}`,
    caption: 'Crédits / Débits'
  }))
)

const transactionRows = computed(() => props.transactions?.data ?? [])
const transactions = computed(() => props.transactions ?? {})

const submitAccount = () => {
  accountForm.post(route('admin.finance.accounts.store'), {
    preserveScroll: true,
    onSuccess: () => accountForm.reset('name', 'code')
  })
}

const submitTransaction = () => {
  transactionForm.post(route('admin.finance.transactions.store'), {
    preserveScroll: true,
    onSuccess: () => transactionForm.reset('label', 'amount', 'notes')
  })
}

const updateTransaction = (id, status) => {
  router.patch(route('admin.finance.transactions.update', id), { status }, { preserveScroll: true })
}

const applyFilters = () => {
  router.get(route('admin.finance'), filters, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
}

const navigate = (url) => {
  if (!url) return
  router.visit(url, { preserveState: true, preserveScroll: true })
}

const formatCurrency = (value) => {
  const amount = Number(value) || 0
  return `${amount.toFixed(0)} FCFA`
}

const formatDate = (value) => {
  if (!value) return '—'
  return new Date(value).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' })
}

const stageLabel = (stage) => {
  const map = {
    supply: 'Approvisionnement',
    inventory: 'Stockage',
    production: 'Production',
    sales: 'Commercialisation',
    delivery: 'Livraison',
    admin: 'Administration'
  }
  return map[stage] || stage || '—'
}

const statusLabel = (status) => {
  const map = {
    pending: 'En attente',
    cleared: 'Validé',
    flagged: 'Alerte'
  }
  return map[status] || status
}

const statusBadge = (status) => {
  switch (status) {
    case 'cleared':
      return 'bg-emerald-100 text-emerald-600'
    case 'flagged':
      return 'bg-amber-100 text-amber-600'
    default:
      return 'bg-slate-100 text-slate-500'
  }
}
</script>

