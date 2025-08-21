<template>
  <form @submit.prevent="submit">
    <div class="p-fluid grid">
      <div class="col-6">
        <label for="amount">Amount</label>
        <InputNumber id="amount" v-model="form.amount" mode="decimal" :minFractionDigits="2" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="currency">Currency</label>
        <Dropdown id="currency" v-model="form.currency" :options="currencies" optionLabel="label" optionValue="value" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="category">Category</label>
        <Dropdown id="category" v-model="form.category" :options="categories" optionLabel="label" optionValue="value" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="date">Date</label>
        <Calendar id="date" v-model="form.date" dateFormat="yy-mm-dd" :showIcon="true" class="w-full" />
      </div>
      <div class="col-12">
        <label for="note">Note</label>
        <InputText id="note" v-model="form.note" class="w-full" />
      </div>
    </div>
    <Button type="submit" label="Save" class="mt-3" />
  </form>
</template>

<script setup>
import { ref, watch } from 'vue';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import axios from 'axios';

const props = defineProps({
  editingId: {
    type: Number,
    default: null
  }
});

const emit = defineEmits(['saved']);
const form = ref({
  amount: null,
  currency: 'BRL',
  note: '',
  category: '',
  date: new Date()
});
const currencies = [
  { label: 'Real (BRL)', value: 'BRL' },
  { label: 'Euro (EUR)', value: 'EUR' }
];
const categories = [
  { label: 'Salary', value: 'salary' },
  { label: 'Investments', value: 'investments' },
  { label: 'Others', value: 'others' }
];

const fetchIncome = async () => {
  if (props.editingId) {
    try {
      const { data } = await axios.get(`/api/incomes/${props.editingId}`);
      form.value.amount = data.amount;
      form.value.currency = data.currency;
      form.value.note = data.note;
      form.value.category = data.category;
      form.value.date = data.date ? new Date(data.date) : new Date();
    } catch (error) {
      console.error('Error fetching income:', error);
    }
  } else {
    form.value.amount = null;
    form.value.currency = 'BRL';
    form.value.note = '';
    form.value.category = '';
    form.value.date = new Date();
  }
};

const submit = async () => {
  try {
    const submitData = {
      ...form.value,
      date: form.value.date ? formatDate(form.value.date) : null
    };
    
    if (props.editingId) {
      await axios.put(`/api/incomes/${props.editingId}`, submitData);
    } else {
      await axios.post('/api/incomes', submitData);
    }
    form.value.amount = null;
    form.value.currency = 'BRL';
    form.value.note = '';
    form.value.category = '';
    form.value.date = new Date();
    emit('saved');
  } catch (error) {
    console.error('Error saving income:', error);
  }
};

// Função para formatar a data corretamente sem problemas de timezone
const formatDate = (date) => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

watch(() => props.editingId, fetchIncome, { immediate: true });
</script>
