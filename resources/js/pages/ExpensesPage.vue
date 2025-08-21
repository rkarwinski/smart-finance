<template>
  <ExpenseList ref="list" @add="showModal = true" @edit="handleEdit" @delete="handleDelete" />
  <ModalFormWrapper v-model="showModal" :header="editingId ? 'Edit Expense' : 'Add Expense'">
    <ExpenseForm :editingId="editingId" @saved="onSaved" />
  </ModalFormWrapper>
  <Dialog v-model:visible="showDeleteConfirm" modal header="Confirm Delete" :style="{width: '450px'}">
    <div class="flex align-items-center justify-content-center">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <span>Are you sure you want to delete this expense?</span>
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
import ExpenseList from '../components/ExpenseList.vue';
import ExpenseForm from '../components/ExpenseForm.vue';
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
  list.value.fetchExpenses();
}

function handleEdit(expense) {
  editingId.value = expense.id;
  showModal.value = true;
}

function handleDelete(expense) {
  deletingId.value = expense.id;
  showDeleteConfirm.value = true;
}

async function confirmDelete() {
  try {
    await axios.delete(`/api/expenses/${deletingId.value}`);
    list.value.fetchExpenses();
  } catch (error) {
    console.error('Error deleting expense:', error);
  } finally {
    showDeleteConfirm.value = false;
    deletingId.value = null;
  }
}
</script>
