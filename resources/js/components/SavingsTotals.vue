<template>
  <Card class="mb-4">
    <template #title>
      💰 Savings Totals
    </template>
    <template #content>
      <div class="grid">
        <div class="col-6">
          <div class="text-center">
            <h3 class="text-green-600">{{ totals.BRL?.toFixed(2) || '0.00' }} BRL</h3>
            <p class="text-sm text-gray-500">Total em Reais</p>
          </div>
        </div>
        <div class="col-6">
          <div class="text-center">
            <h3 class="text-blue-600">{{ totals.EUR?.toFixed(2) || '0.00' }} EUR</h3>
            <p class="text-sm text-gray-500">Total em Euros</p>
          </div>
        </div>
      </div>
      <Button 
        icon="pi pi-refresh" 
        label="Refresh Totals" 
        class="p-button-sm p-button-outlined w-full mt-2" 
        @click="fetchTotals" 
      />
    </template>
  </Card>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import axios from 'axios';

const totals = ref({ BRL: 0, EUR: 0 });

const fetchTotals = async () => {
  try {
    const { data } = await axios.get('/api/savings/totals');
    totals.value = data;
  } catch (error) {
    console.error('Error fetching totals:', error);
  }
};

onMounted(fetchTotals);

defineExpose({ fetchTotals });
</script>
