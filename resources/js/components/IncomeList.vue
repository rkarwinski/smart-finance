<template>
  <Card class="mb-4">
    <template #title>
      Incomes
      <Button icon="pi pi-plus" class="ml-2 p-button-sm" @click="$emit('add')" />
    </template>
    <template #content>
      <MonthFilter :month="month" :year="year" @filter="onFilter" />
      <StyledTable
        :items="incomes"
        :columns="columns"
        :sortField="sortField"
        :sortOrder="sortOrder"
        :showActions="true"
        @edit="$emit('edit', $event)"
        @delete="$emit('delete', $event)"
      >
        <template #header>
          <span class="font-semibold">Receitas</span>
        </template>
      </StyledTable>
      <div v-if="!incomes.length" class="text-gray-500 mt-2">Nenhum registro encontrado</div>
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
const incomes = ref([]);
const columns = [
  { field: 'amount', header: 'Valor', sortable: true, body: i => `${i.amount} ${i.currency}` },
  { field: 'category', header: 'Categoria', sortable: true },
  { field: 'note', header: 'Nota', sortable: false },
  { field: 'date', header: 'Data', sortable: true },
];

const fetchIncomes = async () => {
  const { data } = await axios.get(`/api/incomes/filter/${month.value}/${year.value}`);
  incomes.value = Array.isArray(data) ? data : [];
};

function onFilter(m, y) {
  month.value = m;
  year.value = y;
  fetchIncomes();
}

onMounted(fetchIncomes);

defineEmits(['add', 'edit', 'delete']);
defineExpose({ fetchIncomes });
</script>
