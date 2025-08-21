<template>
  <form @submit.prevent="submit">
    <div class="p-fluid grid">
      <div class="col-6">
        <label for="title">Title</label>
        <InputText id="title" v-model="form.title" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="place">Place</label>
        <InputText id="place" v-model="form.place" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="category">Category</label>
        <Dropdown id="category" v-model="form.category" :options="categories" optionLabel="label" optionValue="value" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="amount">Amount</label>
        <InputNumber id="amount" v-model="form.amount" mode="decimal" :minFractionDigits="2" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="currency">Currency</label>
        <Dropdown id="currency" v-model="form.currency" :options="currencies" optionLabel="label" optionValue="value" required class="w-full" />
      </div>
      <div class="col-6" v-if="form.currency === 'BRL'">
        <label for="eurRate">EUR Rate</label>
        <InputNumber id="eurRate" v-model="eurRate" mode="decimal" :minFractionDigits="4" required class="w-full" />
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
import { ref, onMounted, watch } from 'vue';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
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

const form = ref({
  title: '',
  note: '',
  place: '',
  category: '',
  amount: null,
  currency: 'BRL',
  amount_eur: null,
  date: new Date()
});
const eurRate = ref(null);
const currencies = [
  { label: 'Real (BRL)', value: 'BRL' },
  { label: 'Euro (EUR)', value: 'EUR' }
];
const categories = [
  { label: 'Rent', value: 'rent' },
  { label: 'Supermarket', value: 'supermarket' },
  { label: 'Clothes', value: 'clothes' },
  { label: 'Shoes', value: 'shoes' },
  { label: 'Entertainment', value: 'entertainment' },
  { label: 'Transport', value: 'transport' },
  { label: 'Courses/Education', value: 'courses/education' }
];

const fetchExpense = async () => {
  if (props.editingId) {
    try {
      const { data } = await axios.get(`/api/expenses/${props.editingId}`);
      form.value.title = data.title;
      form.value.note = data.note;
      form.value.place = data.place;
      form.value.category = data.category;
      form.value.amount = data.amount;
      form.value.currency = data.currency;
      form.value.amount_eur = data.amount_eur;
      form.value.date = data.date ? new Date(data.date) : new Date();
    } catch (error) {
      console.error('Error fetching expense:', error);
    }
  } else {
    form.value.title = '';
    form.value.note = '';
    form.value.place = '';
    form.value.category = '';
    form.value.amount = null;
    form.value.currency = 'BRL';
    form.value.amount_eur = null;
    form.value.date = new Date();
  }
};

watch([() => form.value.currency, eurRate], ([currency, rate]) => {
  if (currency === 'BRL' && form.value.amount && rate) {
    form.value.amount_eur = (form.value.amount / rate).toFixed(2);
  } else {
    form.value.amount_eur = null;
  }
});

const emit = defineEmits(['saved']);
const submit = async () => {
  try {
    const submitData = {
      ...form.value,
      date: form.value.date ? formatDate(form.value.date) : null
    };
    
    if (props.editingId) {
      await axios.put(`/api/expenses/${props.editingId}`, submitData);
    } else {
      await axios.post('/api/expenses', submitData);
    }
    form.value.title = '';
    form.value.note = '';
    form.value.place = '';
    form.value.category = '';
    form.value.amount = null;
    form.value.currency = 'BRL';
    form.value.amount_eur = null;
    form.value.date = new Date();
    eurRate.value = null;
    emit('saved');
  } catch (error) {
    console.error('Error saving expense:', error);
  }
};

// Função para formatar a data corretamente sem problemas de timezone
const formatDate = (date) => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

watch(() => props.editingId, fetchExpense, { immediate: true });
</script>
