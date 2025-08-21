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
        </TabView>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DashboardContent from '../components/DashboardContent.vue';
import { getCurrentFinancialMonth } from '../utils/financialMonth.js';

const financialMonth = getCurrentFinancialMonth();
const currentMonth = ref(financialMonth.month);
const currentYear = ref(financialMonth.year);
</script>

<style scoped>
.dashboard-container {
  padding: 1rem;
}
</style>
