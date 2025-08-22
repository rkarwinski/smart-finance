<template>
  <Dialog v-model:visible="showModal" modal header="Copy Recurring Expenses" :style="{width: '80vw'}" :closable="true">
    <div class="mb-3">
      <label for="targetMonth" class="block mb-2">Target Month:</label>
      <Calendar 
        id="targetMonth" 
        v-model="targetDate" 
        view="month" 
        dateFormat="mm/yy" 
        :showIcon="true" 
        placeholder="Select month"
        class="w-full md:w-auto"
      />
    </div>
    
    <div class="mb-3">
      <p class="text-lg font-semibold">
        Previous Month Expenses ({{ formatMonthYear(previousMonth) }})
      </p>
      <p class="text-sm text-gray-600 mb-3">
        Select the expenses you want to copy to {{ formatMonthYear(targetDate) }}
      </p>
    </div>

    <div class="mb-3">
      <Button 
        label="Select All" 
        icon="pi pi-check" 
        @click="selectAll" 
        class="p-button-sm p-button-outlined mr-2"
      />
      <Button 
        label="Deselect All" 
        icon="pi pi-times" 
        @click="deselectAll" 
        class="p-button-sm p-button-outlined"
      />
    </div>

    <DataTable 
      :value="previousMonthExpenses" 
      v-model:selection="selectedExpenses" 
      dataKey="id"
      :loading="loading"
      responsiveLayout="scroll"
      class="mb-4"
    >
      <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
      <Column field="title" header="Title" sortable></Column>
      <Column field="category" header="Category" sortable></Column>
      <Column field="place" header="Place" sortable></Column>
      <Column field="amount" header="Amount" sortable>
        <template #body="slotProps">
          {{ slotProps.data.amount }} {{ slotProps.data.currency }}
        </template>
      </Column>
      <Column field="date" header="Date" sortable>
        <template #body="slotProps">
          {{ formatDate(slotProps.data.date) }}
        </template>
      </Column>
    </DataTable>

    <template #footer>
      <div class="flex justify-content-between">
        <span class="text-sm text-gray-600">
          {{ selectedExpenses.length }} of {{ previousMonthExpenses.length }} expenses selected
        </span>
        <div>
          <Button 
            label="Cancel" 
            icon="pi pi-times" 
            @click="closeModal" 
            class="p-button-text mr-2"
          />
          <Button 
            label="Copy Selected" 
            icon="pi pi-copy" 
            @click="copyExpenses" 
            :disabled="selectedExpenses.length === 0 || !targetDate"
            :loading="copying"
          />
        </div>
      </div>
    </template>
  </Dialog>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import axios from 'axios';

const props = defineProps({
  visible: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:visible', 'expenses-copied']);

const showModal = computed({
  get: () => props.visible,
  set: (value) => emit('update:visible', value)
});

const previousMonthExpenses = ref([]);
const selectedExpenses = ref([]);
const targetDate = ref(new Date());
const loading = ref(false);
const copying = ref(false);

// Calcular o mês anterior baseado no target month selecionado
const previousMonth = computed(() => {
  if (!targetDate.value) {
    const now = new Date();
    return new Date(now.getFullYear(), now.getMonth() - 1, 1);
  }
  
  const target = new Date(targetDate.value);
  return new Date(target.getFullYear(), target.getMonth() - 1, 1);
});

const formatMonthYear = (date) => {
  if (!date) return '';
  return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('pt-BR');
};

const fetchPreviousMonthExpenses = async () => {
  loading.value = true;
  try {
    const prevMonth = previousMonth.value;
    const month = prevMonth.getMonth() + 1;
    const year = prevMonth.getFullYear();
    
    console.log(`Fetching expenses for previous month: ${month}/${year}`);
    
    const { data } = await axios.get(`/api/expenses/filter/${month}/${year}`);
    previousMonthExpenses.value = Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Error fetching previous month expenses:', error);
    previousMonthExpenses.value = [];
  } finally {
    loading.value = false;
  }
};

const selectAll = () => {
  selectedExpenses.value = [...previousMonthExpenses.value];
};

const deselectAll = () => {
  selectedExpenses.value = [];
};

const copyExpenses = async () => {
  if (selectedExpenses.value.length === 0 || !targetDate.value) return;
  
  copying.value = true;
  try {
    const targetMonth = targetDate.value.getMonth() + 1;
    const targetYear = targetDate.value.getFullYear();
    
    const copyPromises = selectedExpenses.value.map(expense => {
      const newExpense = {
        title: expense.title,
        note: expense.note,
        place: expense.place,
        category: expense.category,
        amount: expense.amount,
        currency: expense.currency,
        amount_eur: expense.amount_eur,
        date: formatDateForAPI(targetYear, targetMonth, new Date(expense.date).getDate())
      };
      
      return axios.post('/api/expenses', newExpense);
    });

    await Promise.all(copyPromises);
    
    emit('expenses-copied', {
      count: selectedExpenses.value.length,
      month: targetMonth,
      year: targetYear
    });
    
    closeModal();
  } catch (error) {
    console.error('Error copying expenses:', error);
    alert('Error occurred while copying expenses. Please try again.');
  } finally {
    copying.value = false;
  }
};

const formatDateForAPI = (year, month, day) => {
  const formattedMonth = String(month).padStart(2, '0');
  const formattedDay = String(day).padStart(2, '0');
  return `${year}-${formattedMonth}-${formattedDay}`;
};

const closeModal = () => {
  selectedExpenses.value = [];
  showModal.value = false;
};

// Buscar gastos do mês anterior quando o modal for aberto
watch(() => props.visible, (newValue) => {
  if (newValue) {
    fetchPreviousMonthExpenses();
    // Definir o mês atual como padrão para a cópia
    targetDate.value = new Date();
  }
});

// Recarregar dados quando o target month mudar
watch(() => targetDate.value, (newDate) => {
  if (newDate && props.visible) {
    selectedExpenses.value = []; // Limpar seleções
    fetchPreviousMonthExpenses();
  }
});
</script>
