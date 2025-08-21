<template>
  <div>
    <Chart type="pie" :data="chartData" :options="chartOptions" />
    
    <div class="grid mt-3">
      <div class="col-6 text-center">
        <div class="p-2 border-round bg-red-50">
          <h4 class="text-red-600 m-0">{{ totalExpenses.toFixed(2) }}</h4>
          <p class="text-sm text-gray-500 m-0">Total Expenses</p>
        </div>
      </div>
      <div class="col-6 text-center">
        <div class="p-2 border-round bg-green-50">
          <h4 class="text-green-600 m-0">{{ totalIncomes.toFixed(2) }}</h4>
          <p class="text-sm text-gray-500 m-0">Total Incomes</p>
        </div>
      </div>
    </div>
    
    <div class="text-center mt-3">
      <div class="p-2 border-round" :class="balance >= 0 ? 'bg-green-50' : 'bg-red-50'">
        <h4 :class="balance >= 0 ? 'text-green-600' : 'text-red-600'" class="m-0">
          {{ balance >= 0 ? '+' : '' }}{{ balance.toFixed(2) }}
        </h4>
        <p class="text-sm text-gray-500 m-0">Balance</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Chart from 'primevue/chart';
import axios from 'axios';

const props = defineProps({
  period: String,
  currentMonth: Number,
  currentYear: Number
});

const expensesData = ref([]);
const incomesData = ref([]);

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
};

const totalExpenses = computed(() => {
  return expensesData.value.reduce((total, expense) => {
    // Usar amount_eur se a moeda for BRL, senão usar amount
    const amount = expense.currency === 'BRL' ? parseFloat(expense.amount_eur || 0) : parseFloat(expense.amount);
    return total + amount;
  }, 0);
});

const totalIncomes = computed(() => {
  return incomesData.value.reduce((total, income) => {
    // Usar amount_eur se a moeda for BRL, senão usar amount
    const amount = income.currency === 'BRL' ? parseFloat(income.amount_eur || 0) : parseFloat(income.amount);
    return total + amount;
  }, 0);
});

const balance = computed(() => {
  return totalIncomes.value - totalExpenses.value;
});

const chartData = computed(() => {
  const expenses = totalExpenses.value;
  const incomes = totalIncomes.value;

  if (expenses === 0 && incomes === 0) {
    return {
      labels: ['No Data'],
      datasets: [{
        data: [1],
        backgroundColor: ['#e5e7eb'],
        borderColor: ['#e5e7eb'],
        borderWidth: 1
      }]
    };
  }

  return {
    labels: ['Expenses', 'Incomes'],
    datasets: [{
      data: [expenses, incomes],
      backgroundColor: ['#ef4444', '#22c55e'],
      borderColor: ['#dc2626', '#16a34a'],
      borderWidth: 2
    }]
  };
});

const fetchData = async () => {
  try {
    let expensesUrl = '/api/expenses';
    let incomesUrl = '/api/incomes';
    
    if (props.period === 'month') {
      expensesUrl = `/api/expenses/filter/${props.currentMonth}/${props.currentYear}`;
      incomesUrl = `/api/incomes/filter/${props.currentMonth}/${props.currentYear}`;
    } else if (props.period === 'year') {
      expensesUrl = `/api/expenses/filter-year/${props.currentYear}`;
      incomesUrl = `/api/incomes/filter-year/${props.currentYear}`;
    }

    const [expensesResponse, incomesResponse] = await Promise.all([
      axios.get(expensesUrl),
      axios.get(incomesUrl)
    ]);

    expensesData.value = Array.isArray(expensesResponse.data) ? expensesResponse.data : [];
    incomesData.value = Array.isArray(incomesResponse.data) ? incomesResponse.data : [];
  } catch (error) {
    console.error('Error fetching financial data:', error);
  }
};

onMounted(fetchData);
</script>
