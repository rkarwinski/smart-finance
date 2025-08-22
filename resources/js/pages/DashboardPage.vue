<template>
  <div class="dashboard-container">
    <Card>
      <template #title>
        📊 Financial Dashboard
      </template>
      <template #content>
        <TabView>
          <TabPanel header="Current Month">
            <DashboardContent period="month" :currentMonth="currentMonth" :currentYear="currentYear" />
          </TabPanel>
          <TabPanel header="Current Year">
            <DashboardContent period="year" :currentYear="currentYear" />
          </TabPanel>
          <TabPanel header="All Time">
            <DashboardContent period="all" />
          </TabPanel>
          <TabPanel header="Next Month Forecast">
            <NextMonthForecast :nextMonth="nextMonth" :nextYear="nextYear" />
          </TabPanel>
        </TabView>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Card from 'primevue/card';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DashboardContent from '../components/DashboardContent.vue';
import NextMonthForecast from '../components/NextMonthForecast.vue';
import { getCurrentFinancialMonth } from '../utils/financialMonth.js';

const financialMonth = getCurrentFinancialMonth();
const currentMonth = ref(financialMonth.month);
const currentYear = ref(financialMonth.year);

// Calcular próximo mês financeiro
const nextMonth = computed(() => {
  if (currentMonth.value === 12) {
    return 1;
  }
  return currentMonth.value + 1;
});

const nextYear = computed(() => {
  if (currentMonth.value === 12) {
    return currentYear.value + 1;
  }
  return currentYear.value;
});
</script>

<style scoped>
.dashboard-container {
  padding: 1rem;
}
</style>
