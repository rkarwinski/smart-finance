<template>
  <form @submit.prevent="submit">
    <div class="p-fluid grid">
      <div class="col-6">
        <label for="transaction_type">Transaction Type</label>
        <Dropdown 
          id="transaction_type" 
          v-model="form.transaction_type" 
          :options="transactionTypes" 
          optionLabel="label" 
          optionValue="value" 
          required 
          class="w-full" 
        />
      </div>
      <div class="col-6">
        <label for="amount">Amount</label>
        <InputNumber id="amount" v-model="form.amount" mode="decimal" :minFractionDigits="2" required class="w-full" />
      </div>
      <div class="col-6">
        <label for="currency">Currency</label>
        <Dropdown id="currency" v-model="form.currency" :options="currencies" optionLabel="label" optionValue="value" required class="w-full" />
      </div>
      <div class="col-6" v-if="form.transaction_type === 'withdrawal'">
        <label for="reason">Reason *</label>
        <InputText id="reason" v-model="form.reason" required class="w-full" />
      </div>
    </div>
    <Button type="submit" label="Save" class="mt-3" />
  </form>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
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
  transaction_type: 'deposit',
  reason: ''
});

const transactionTypes = [
  { label: 'Deposit (Save Money)', value: 'deposit' },
  { label: 'Withdrawal (Take Money Out)', value: 'withdrawal' }
];

const currencies = [
  { label: 'Real (BRL)', value: 'BRL' },
  { label: 'Euro (EUR)', value: 'EUR' }
];

const fetchSaving = async () => {
  if (props.editingId) {
    try {
      const { data } = await axios.get(`/api/savings/${props.editingId}`);
      form.value.amount = data.amount;
      form.value.currency = data.currency;
      form.value.transaction_type = data.transaction_type;
      form.value.reason = data.reason || '';
    } catch (error) {
      console.error('Error fetching saving:', error);
    }
  } else {
    form.value.amount = null;
    form.value.currency = 'BRL';
    form.value.transaction_type = 'deposit';
    form.value.reason = '';
  }
};

const submit = async () => {
  try {
    if (props.editingId) {
      await axios.put(`/api/savings/${props.editingId}`, form.value);
    } else {
      await axios.post('/api/savings', form.value);
    }
    form.value.amount = null;
    form.value.currency = 'BRL';
    form.value.transaction_type = 'deposit';
    form.value.reason = '';
    emit('saved');
  } catch (error) {
    console.error('Error saving:', error);
  }
};

watch(() => props.editingId, fetchSaving, { immediate: true });
</script>
