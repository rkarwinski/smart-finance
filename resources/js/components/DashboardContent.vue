<template>
  <div class="grid">
    <!-- Seção 1: Savings Evolution -->
    <div class="col-12">
      <Card class="mb-4">
        <template #title>
          💰 Savings Evolution
        </template>
        <template #content>
          <SavingsEvolutionChart :period="period" :currentMonth="currentMonth" :currentYear="currentYear" />
        </template>
      </Card>
    </div>

    <!-- Seção 2: Expenses by Category -->
    <div class="col-12 md:col-6">
      <Card class="mb-4">
        <template #title>
          💸 Expenses by Category
        </template>
        <template #content>
          <ExpensesCategoryChart :period="period" :currentMonth="currentMonth" :currentYear="currentYear" />
        </template>
      </Card>
    </div>

    <!-- Seção 3: Expenses vs Incomes -->
    <div class="col-12 md:col-6">
      <Card class="mb-4">
        <template #title>
          ⚖️ Expenses vs Incomes
        </template>
        <template #content>
          <ExpensesVsIncomesChart :period="period" :currentMonth="currentMonth" :currentYear="currentYear" />
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import Card from 'primevue/card';
import SavingsEvolutionChart from './SavingsEvolutionChart.vue';
import ExpensesCategoryChart from './ExpensesCategoryChart.vue';
import ExpensesVsIncomesChart from './ExpensesVsIncomesChart.vue';

defineProps({
  period: {
    type: String,
    required: true
  },
  currentMonth: Number,
  currentYear: Number
});
</script>
