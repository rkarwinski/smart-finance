<template>
  <DataTable :value="items" :sortField="sortField" :sortOrder="sortOrder" class="p-datatable-sm" responsiveLayout="scroll">
    <template #header>
      <slot name="header" />
    </template>
    <Column v-for="col in columns" :key="col.field" :field="col.field" :header="col.header" :sortable="col.sortable !== false" :body="col.body" />
    <Column v-if="showActions" header="Ações" :exportable="false" style="min-width:8rem">
      <template #body="slotProps">
        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="$emit('edit', slotProps.data)" />
        <Button icon="pi pi-trash" class="p-button-rounded p-button-warning" @click="$emit('delete', slotProps.data)" />
      </template>
    </Column>
  </DataTable>
</template>

<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

const props = defineProps({
  items: Array,
  columns: Array,
  sortField: String,
  sortOrder: Number,
  showActions: {
    type: Boolean,
    default: false
  }
});

defineEmits(['edit', 'delete']);
</script>

<style scoped>
.p-datatable-sm {
  font-size: 0.95rem;
}
</style>
