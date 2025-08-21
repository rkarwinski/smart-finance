<template>
  <Card class="mb-4">
    <template #title>
      Savings
      <Button icon="pi pi-plus" class="ml-2 p-button-sm" @click="$emit('add')" />
    </template>
    <template #content>
      <MonthFilter :month="month" :year="year" @filter="onFilter" />
      <StyledTable
        :items="savings"
        :columns="columns"
        :sortField="sortField"
        :sortOrder="sortOrder"
        :showActions="true"
        @edit="$emit('edit', $event)"
        @delete="$emit('delete', $event)"
      >
        <template #header>
          <span class="font-semibold">Poupança</span>
        </template>
      </StyledTable>
      <div v-if="!savings.length" class="text-gray-500 mt-2">Nenhum registro encontrado</div>
    </template>
  </Card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import axios from 'axios';
import StyledTable from './StyledTable.vue';
import MonthFilter from './MonthFilter.vue';
import { getCurrentFinancialMonth } from '../utils/financialMonth.js';

const financialMonth = getCurrentFinancialMonth();
const month = ref(financialMonth.month);
const year = ref(financialMonth.year);
const sortField = ref('created_at');
const sortOrder = ref(-1);
const savings = ref([]);
const columns = [
  { field: 'transaction_type', header: 'Type', sortable: true, body: s => s.transaction_type === 'deposit' ? '➕ Deposit' : '➖ Withdrawal' },
  { field: 'amount', header: 'Valor', sortable: true, body: s => `${s.amount} ${s.currency}` },
  { field: 'currency', header: 'Moeda', sortable: false },
  { field: 'reason', header: 'Reason', sortable: false, body: s => s.reason || '-' },
  { field: 'created_at', header: 'Data', sortable: true },
];

const fetchSavings = async () => {
  const { data } = await axios.get(`/api/savings/filter/${month.value}/${year.value}`);
  savings.value = Array.isArray(data) ? data : [];
};

function onFilter(m, y) {
  month.value = m;
  year.value = y;
  fetchSavings();
}

onMounted(fetchSavings);

defineEmits(['add', 'edit', 'delete']);
defineExpose({ fetchSavings });
</script>
