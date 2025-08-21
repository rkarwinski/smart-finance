<template>
  <div>
    <div class="grid mb-4">
      <div class="col-6">
        <div class="text-center p-3 border-round bg-green-50">
          <h3 class="text-green-600 m-0">{{ totalBRL.toFixed(2) }} BRL</h3>
          <p class="text-sm text-gray-500 m-0">Total Savings (BRL)</p>
          <small class="text-xs text-gray-400">{{ periodMovements.BRL.deposits }} deposits, {{ periodMovements.BRL.withdrawals }} withdrawals</small>
        </div>
      </div>
      <div class="col-6">
        <div class="text-center p-3 border-round bg-blue-50">
          <h3 class="text-blue-600 m-0">{{ totalEUR.toFixed(2) }} EUR</h3>
          <p class="text-sm text-gray-500 m-0">Total Savings (EUR)</p>
          <small class="text-xs text-gray-400">{{ periodMovements.EUR.deposits }} deposits, {{ periodMovements.EUR.withdrawals }} withdrawals</small>
        </div>
      </div>
    </div>
    
    <div class="grid">
      <div class="col-6">
        <Chart type="line" :data="chartDataBRL" :options="chartOptions" />
        <p class="text-center text-sm text-gray-500">BRL Evolution</p>
      </div>
      <div class="col-6">
        <Chart type="line" :data="chartDataEUR" :options="chartOptions" />
        <p class="text-center text-sm text-gray-500">EUR Evolution</p>
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

const totalBRL = ref(0);
const totalEUR = ref(0);
const savingsData = ref([]);
const periodMovements = ref({
  BRL: { deposits: 0, withdrawals: 0 },
  EUR: { deposits: 0, withdrawals: 0 }
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: function(value) {
          return value.toFixed(2);
        }
      }
    }
  },
  plugins: {
    legend: {
      display: false
    }
  }
};

const chartDataBRL = computed(() => {
  const brlData = savingsData.value.filter(s => s.currency === 'BRL');
  let runningTotal = 0;
  const data = brlData.map(s => {
    runningTotal += s.transaction_type === 'deposit' ? s.amount : -s.amount;
    return runningTotal;
  });

  return {
    labels: brlData.map((_, index) => `Transaction ${index + 1}`),
    datasets: [{
      label: 'BRL Total',
      data: data,
      borderColor: '#22c55e',
      backgroundColor: 'rgba(34, 197, 94, 0.1)',
      tension: 0.4
    }]
  };
});

const chartDataEUR = computed(() => {
  const eurData = savingsData.value.filter(s => s.currency === 'EUR');
  let runningTotal = 0;
  const data = eurData.map(s => {
    runningTotal += s.transaction_type === 'deposit' ? s.amount : -s.amount;
    return runningTotal;
  });

  return {
    labels: eurData.map((_, index) => `Transaction ${index + 1}`),
    datasets: [{
      label: 'EUR Total',
      data: data,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59, 130, 246, 0.1)',
      tension: 0.4
    }]
  };
});

const fetchData = async () => {
  try {
    // Buscar totais atuais
    const { data: totals } = await axios.get('/api/savings/totals');
    totalBRL.value = totals.BRL || 0;
    totalEUR.value = totals.EUR || 0;

    // Buscar dados do período para os gráficos
    let url = '/api/savings';
    if (props.period === 'month') {
      url = `/api/savings/filter/${props.currentMonth}/${props.currentYear}`;
    } else if (props.period === 'year') {
      // Implementar busca por ano
      url = `/api/savings`; // Por enquanto, buscar todos
    }

    const { data: savings } = await axios.get(url);
    savingsData.value = Array.isArray(savings) ? savings : [];

    // Calcular movimentações do período
    const movements = {
      BRL: { deposits: 0, withdrawals: 0 },
      EUR: { deposits: 0, withdrawals: 0 }
    };

    savingsData.value.forEach(s => {
      if (s.transaction_type === 'deposit') {
        movements[s.currency].deposits++;
      } else {
        movements[s.currency].withdrawals++;
      }
    });

    periodMovements.value = movements;

  } catch (error) {
    console.error('Error fetching savings data:', error);
  }
};

onMounted(fetchData);
</script>
