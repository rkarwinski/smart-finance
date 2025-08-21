<template>
  <div class="flex gap-2 items-center mb-2">
    <Dropdown v-model="selectedMonth" :options="months" optionLabel="label" optionValue="value" class="w-8rem" />
    <Dropdown v-model="selectedYear" :options="years" optionLabel="label" optionValue="value" class="w-6rem" />
    <Button icon="pi pi-filter" @click="$emit('filter', selectedMonth, selectedYear)" label="Filtrar" class="p-button-sm" />
    <small class="text-gray-600 ml-2">
      Período: 25/{{ String(selectedMonth - 1 || 12).padStart(2, '0') }} até 25/{{ String(selectedMonth).padStart(2, '0') }}
    </small>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';

const now = new Date();
const props = defineProps({
  month: Number,
  year: Number
});
const emit = defineEmits(['filter']);

const months = [
  { label: 'Janeiro', value: 1 },
  { label: 'Fevereiro', value: 2 },
  { label: 'Março', value: 3 },
  { label: 'Abril', value: 4 },
  { label: 'Maio', value: 5 },
  { label: 'Junho', value: 6 },
  { label: 'Julho', value: 7 },
  { label: 'Agosto', value: 8 },
  { label: 'Setembro', value: 9 },
  { label: 'Outubro', value: 10 },
  { label: 'Novembro', value: 11 },
  { label: 'Dezembro', value: 12 }
];
const years = Array.from({ length: 5 }, (_, i) => {
  const y = now.getFullYear() - i;
  return { label: y.toString(), value: y };
});

const selectedMonth = ref(props.month || now.getMonth() + 1);
const selectedYear = ref(props.year || now.getFullYear());
</script>
