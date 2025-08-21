
<template>
  <div class="dashboard p-4">
    <div class="flex flex-row gap-4 mb-4">
      <Card class="flex-1">
        <template #title>Saldo Atual</template>
        <template #content>
          <div class="text-2xl font-bold text-green-600">R$ 12.500,00</div>
        </template>
      </Card>
      <Card class="flex-1">
        <template #title>Receitas</template>
        <template #content>
          <div class="text-2xl font-bold text-blue-600">R$ 8.000,00</div>
        </template>
      </Card>
      <Card class="flex-1">
        <template #title>Despesas</template>
        <template #content>
          <div class="text-2xl font-bold text-red-600">R$ 3.200,00</div>
        </template>
      </Card>
    </div>
    <div class="flex flex-row gap-4">
      <Card class="flex-1">
        <template #title>Resumo Gráfico</template>
        <template #content>
          <Chart type="pie" :data="chartData" :options="chartOptions" style="min-height:300px" />
        </template>
      </Card>
      <Card class="flex-1">
        <template #title>Últimas Movimentações</template>
        <template #content>
          <ul>
            <li v-for="item in transactions" :key="item.id" class="mb-2 flex justify-between">
              <span>{{ item.desc }}</span>
              <span :class="item.value > 0 ? 'text-green-600' : 'text-red-600'">{{ item.value > 0 ? '+' : '' }}R$ {{ Math.abs(item.value).toFixed(2) }}</span>
            </li>
          </ul>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>

import Card from 'primevue/card';
import Chart from 'primevue/chart';
import { ref } from 'vue';

const chartData = ref({
  labels: ['Receitas', 'Despesas'],
  datasets: [
    {
      data: [8000, 3200],
      backgroundColor: ['#2563eb', '#dc2626'],
      hoverBackgroundColor: ['#1d4ed8', '#b91c1c']
    }
  ]
});
const chartOptions = ref({
  plugins: {
    legend: {
      position: 'bottom',
      labels: { color: '#334155' }
    }
  }
});
const transactions = ref([
  { id: 1, desc: 'Salário', value: 8000 },
  { id: 2, desc: 'Aluguel', value: -2000 },
  { id: 3, desc: 'Supermercado', value: -800 },
  { id: 4, desc: 'Transporte', value: -400 },
  { id: 5, desc: 'Internet', value: -200 }
]);
</script>

<style scoped>
.dashboard {
  max-width: 1200px;
  margin: 0 auto;
}
</style>
