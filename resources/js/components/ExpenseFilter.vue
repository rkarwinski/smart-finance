<template>
  <Card class="mb-4">
    <template #title>Expense Filter</template>
    <template #content>
      <div class="p-fluid grid mb-3">
        <div class="col-6">
          <label for="month">Month</label>
          <Dropdown id="month" v-model="month" :options="months" optionLabel="label" optionValue="value" class="w-full" />
        </div>
        <div class="col-6">
          <label for="year">Year</label>
          <Dropdown id="year" v-model="year" :options="years" optionLabel="label" optionValue="value" class="w-full" />
        </div>
        <div class="col-12">
          <Button label="Filter" @click="fetchExpenses" class="mt-2" />
        </div>
      </div>
      <div v-if="expenses.length">
        <h4 class="mb-2">Expenses for {{ monthLabel }} {{ year }}</h4>
        <ul>
          <li v-for="e in expenses" :key="e.id">
            {{ e.date }} - {{ e.title }} - {{ e.amount }} {{ e.currency }} ({{ e.category }})
          </li>
        </ul>
      </div>
      <div v-else class="text-gray-500">No expenses found for this period.</div>
    </template>
  </Card>
</template>

<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import axios from 'axios';

const now = new Date();
const month = ref(now.getMonth() + 1);
const year = ref(now.getFullYear());
const months = [
  { label: 'January', value: 1 },
  { label: 'February', value: 2 },
  { label: 'March', value: 3 },
  { label: 'April', value: 4 },
  { label: 'May', value: 5 },
  { label: 'June', value: 6 },
  { label: 'July', value: 7 },
  { label: 'August', value: 8 },
  { label: 'September', value: 9 },
  { label: 'October', value: 10 },
  { label: 'November', value: 11 },
  { label: 'December', value: 12 }
];
const years = Array.from({ length: 5 }, (_, i) => {
  const y = now.getFullYear() - i;
  return { label: y.toString(), value: y };
});
const expenses = ref([]);

const fetchExpenses = async () => {
  const { data } = await axios.get(`/api/expenses/filter/${month.value}/${year.value}`);
  expenses.value = data;
};

const monthLabel = computed(() => months.find(m => m.value === month.value)?.label || '');
</script>
