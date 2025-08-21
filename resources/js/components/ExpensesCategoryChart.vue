<template>
  <div>
    <Chart type="pie" :data="chartData" :options="chartOptions" @click="onChartClick" />
    
    <!-- Modal para mostrar detalhes da categoria -->
    <Dialog v-model:visible="showModal" modal :header="`${selectedCategory} Expenses`" :style="{width: '50vw'}">
      <DataTable :value="selectedExpenses" responsiveLayout="scroll">
        <Column field="title" header="Title"></Column>
        <Column field="amount" header="Amount">
          <template #body="slotProps">
            {{ slotProps.data.amount }} {{ slotProps.data.currency }}
          </template>
        </Column>
        <Column field="place" header="Place"></Column>
        <Column field="date" header="Date"></Column>
      </DataTable>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Chart from 'primevue/chart';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import axios from 'axios';

const props = defineProps({
  period: String,
  currentMonth: Number,
  currentYear: Number
});

const expensesData = ref([]);
const showModal = ref(false);
const selectedCategory = ref('');
const selectedExpenses = ref([]);

const categoryColors = {
  'rent': '#ff6b6b',
  'supermarket': '#4ecdc4',
  'clothes': '#45b7d1',
  'shoes': '#96ceb4',
  'entertainment': '#ffeaa7',
  'transport': '#dda0dd',
  'courses/education': '#98d8c8'
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom'
    }
  },
  onClick: (event, elements) => {
    if (elements.length > 0) {
      const index = elements[0].index;
      const category = chartData.value.labels[index];
      openCategoryModal(category);
    }
  }
};

const chartData = computed(() => {
  const categoryTotals = {};
  
  expensesData.value.forEach(expense => {
    if (!categoryTotals[expense.category]) {
      categoryTotals[expense.category] = 0;
    }
    categoryTotals[expense.category] += parseFloat(expense.amount);
  });

  const labels = Object.keys(categoryTotals);
  const data = Object.values(categoryTotals);
  const colors = labels.map(label => categoryColors[label] || '#999999');

  return {
    labels: labels,
    datasets: [{
      data: data,
      backgroundColor: colors,
      borderColor: colors,
      borderWidth: 2
    }]
  };
});

const openCategoryModal = (category) => {
  selectedCategory.value = category;
  selectedExpenses.value = expensesData.value.filter(expense => expense.category === category);
  showModal.value = true;
};

const onChartClick = (event) => {
  // Este método será chamado pelo evento do template
};

const fetchData = async () => {
  try {
    let url = '/api/expenses';
    if (props.period === 'month') {
      url = `/api/expenses/filter/${props.currentMonth}/${props.currentYear}`;
    } else if (props.period === 'year') {
      // Implementar busca por ano
      url = `/api/expenses`; // Por enquanto, buscar todos
    }

    const { data } = await axios.get(url);
    expensesData.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Error fetching expenses data:', error);
  }
};

onMounted(fetchData);
</script>
