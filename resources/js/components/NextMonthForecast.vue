<template>
  <div class="next-month-forecast">
    <div class="mb-4">
      <h2 class="text-2xl font-bold text-center mb-2">
        🔮 Next Month Forecast - {{ formatMonthYear(nextMonth, nextYear) }}
      </h2>
      <p class="text-center text-gray-600">
        Expense predictions based on future scheduled expenses
      </p>
    </div>

    <div class="grid">
      <!-- Expenses by Category Chart -->
      <div class="col-12 lg:col-6">
        <Card class="mb-4">
          <template #title>
            💸 Forecasted Expenses by Category
          </template>
          <template #content>
            <ExpensesCategoryChart 
              period="forecast" 
              :currentMonth="nextMonth" 
              :currentYear="nextYear" 
            />
          </template>
        </Card>
      </div>

      <!-- Expenses vs Incomes -->
      <div class="col-12 lg:col-6">
        <Card class="mb-4">
          <template #title>
            ⚖️ Forecasted Expenses vs Incomes
          </template>
          <template #content>
            <ExpensesVsIncomesChart 
              period="forecast" 
              :currentMonth="nextMonth" 
              :currentYear="nextYear" 
            />
          </template>
        </Card>
      </div>

      <!-- Summary Cards -->
      <div class="col-12">
        <Card>
          <template #title>
            📊 Forecast Summary
          </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-4">
                <div class="text-center p-3 border-round bg-blue-50">
                  <h3 class="text-blue-600 m-0">{{ totalForecastedExpenses.toFixed(2) }} €</h3>
                  <p class="text-sm text-gray-600 m-0">Total Forecasted Expenses</p>
                </div>
              </div>
              <div class="col-12 md:col-4">
                <div class="text-center p-3 border-round bg-green-50">
                  <h3 class="text-green-600 m-0">{{ totalForecastedIncomes.toFixed(2) }} €</h3>
                  <p class="text-sm text-gray-600 m-0">Total Forecasted Incomes</p>
                </div>
              </div>
              <div class="col-12 md:col-4">
                <div class="text-center p-3 border-round" :class="forecastBalance >= 0 ? 'bg-green-50' : 'bg-red-50'">
                  <h3 :class="forecastBalance >= 0 ? 'text-green-600' : 'text-red-600'" class="m-0">
                    {{ forecastBalance >= 0 ? '+' : '' }}{{ forecastBalance.toFixed(2) }} €
                  </h3>
                  <p class="text-sm text-gray-600 m-0">Forecasted Balance</p>
                </div>
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Card from 'primevue/card';
import ExpensesCategoryChart from './ExpensesCategoryChart.vue';
import ExpensesVsIncomesChart from './ExpensesVsIncomesChart.vue';
import axios from 'axios';

const props = defineProps({
  nextMonth: {
    type: Number,
    required: true
  },
  nextYear: {
    type: Number,
    required: true
  }
});

const forecastedExpenses = ref([]);
const forecastedIncomes = ref([]);

const totalForecastedExpenses = computed(() => {
  return forecastedExpenses.value.reduce((total, expense) => {
    const amount = expense.currency === 'BRL' ? parseFloat(expense.amount_eur || 0) : parseFloat(expense.amount);
    return total + amount;
  }, 0);
});

const totalForecastedIncomes = computed(() => {
  return forecastedIncomes.value.reduce((total, income) => {
    const amount = income.currency === 'BRL' ? parseFloat(income.amount_eur || 0) : parseFloat(income.amount);
    return total + amount;
  }, 0);
});

const forecastBalance = computed(() => {
  return totalForecastedIncomes.value - totalForecastedExpenses.value;
});

const formatMonthYear = (month, year) => {
  const date = new Date(year, month - 1);
  return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
};

const fetchForecastData = async () => {
  try {
    // Buscar despesas do próximo mês
    const expensesResponse = await axios.get(`/api/expenses/filter/${props.nextMonth}/${props.nextYear}`);
    forecastedExpenses.value = Array.isArray(expensesResponse.data) ? expensesResponse.data : [];

    // Buscar receitas do próximo mês  
    const incomesResponse = await axios.get(`/api/incomes/filter/${props.nextMonth}/${props.nextYear}`);
    forecastedIncomes.value = Array.isArray(incomesResponse.data) ? incomesResponse.data : [];

  } catch (error) {
    console.error('Error fetching forecast data:', error);
  }
};

onMounted(fetchForecastData);
</script>

<style scoped>
.next-month-forecast {
  padding: 1rem;
}
</style>
