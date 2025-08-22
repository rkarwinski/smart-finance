<template>
  <Card class="mb-4">
    <template #title>
      Expenses
      <Button icon="pi pi-plus" class="ml-2 p-button-sm" @click="$emit('add')" />
      <Button 
        icon="pi pi-copy" 
        label="Copy Recurring" 
        class="ml-2 p-button-sm p-button-outlined" 
        @click="$emit('copy-recurring')" 
      />
    </template>
    <template #content>
      <MonthFilter :month="month" :year="year" @filter="onFilter" />
      <StyledTable
        :items="expenses"
        :columns="columns"
        :sortField="sortField"
        :sortOrder="sortOrder"
        :showActions="true"
        @edit="$emit('edit', $event)"
        @delete="$emit('delete', $event)"
      >
        <template #header>
          <span class="font-semibold">Despesas do mês</span>
        </template>
      </StyledTable>
      <div v-if="!expenses.length" class="text-gray-500 mt-2">Nenhum registro encontrado</div>
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
const sortField = ref('date');
const sortOrder = ref(-1);
const expenses = ref([]);
const columns = [
  { field: 'title', header: 'Título', sortable: true },
  { field: 'amount', header: 'Valor', sortable: true, body: e => `${e.amount} ${e.currency}` },
  { field: 'category', header: 'Categoria', sortable: true },
  { field: 'place', header: 'Local', sortable: true },
  { field: 'date', header: 'Data', sortable: true },
];

const fetchExpenses = async () => {
  const { data } = await axios.get(`/api/expenses/filter/${month.value}/${year.value}`);
  expenses.value = Array.isArray(data) ? data : [];
};

function onFilter(m, y) {
  month.value = m;
  year.value = y;
  fetchExpenses();
}

onMounted(fetchExpenses);

defineEmits(['add', 'edit', 'delete', 'copy-recurring']);
defineExpose({ fetchExpenses });
</script>
