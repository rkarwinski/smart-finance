<template>
  <IncomeList ref="list" @add="showModal = true" @edit="handleEdit" @delete="handleDelete" />
  <ModalFormWrapper v-model="showModal" :header="editingId ? 'Edit Income' : 'Add Income'">
    <IncomeForm :editingId="editingId" @saved="onSaved" />
  </ModalFormWrapper>
  <Dialog v-model:visible="showDeleteConfirm" modal header="Confirm Delete" :style="{width: '450px'}">
    <div class="flex align-items-center justify-content-center">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <span>Are you sure you want to delete this income?</span>
    </div>
    <template #footer>
      <Button label="No" icon="pi pi-times" class="p-button-text" @click="showDeleteConfirm = false" />
      <Button label="Yes" icon="pi pi-check" class="p-button-danger" @click="confirmDelete" />
    </template>
  </Dialog>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import IncomeList from '../components/IncomeList.vue';
import IncomeForm from '../components/IncomeForm.vue';
import ModalFormWrapper from '../components/ModalFormWrapper.vue';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';

const showModal = ref(false);
const showDeleteConfirm = ref(false);
const editingId = ref(null);
const deletingId = ref(null);
const list = ref();

function onSaved() {
  showModal.value = false;
  editingId.value = null;
  list.value.fetchIncomes();
}

function handleEdit(income) {
  editingId.value = income.id;
  showModal.value = true;
}

function handleDelete(income) {
  deletingId.value = income.id;
  showDeleteConfirm.value = true;
}

async function confirmDelete() {
  try {
    await axios.delete(`/api/incomes/${deletingId.value}`);
    list.value.fetchIncomes();
  } catch (error) {
    console.error('Error deleting income:', error);
  } finally {
    showDeleteConfirm.value = false;
    deletingId.value = null;
  }
}
</script>
