<template>
  <div class="p-inputgroup">
    <span class="p-inputgroup-addon">
      <i class="pi pi-search" />
    </span>
    <InputText v-model="search" :placeholder="placeholder" @input="$emit('search', search)" />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
const props = defineProps({
  placeholder: { type: String, default: 'Buscar...' }
});
const search = ref('');
</script>
