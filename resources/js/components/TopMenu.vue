<template>
  <Menubar :model="items" class="mb-4 shadow-sm border-none" style="background: #f9fafb; color: #222;">
    <template #start>
      <span class="font-bold text-xl tracking-wide ml-2" style="color:#2563eb;">Smart Finance</span>
    </template>
    <template #end>
      <Button icon="pi pi-user" class="p-button-text p-button-plain" style="color: #2563eb;" />
    </template>
  </Menubar>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
const items = ref([
  { label: 'Dashboard', icon: 'pi pi-home', command: () => router.push('/') },
  { label: 'Savings', icon: 'pi pi-wallet', command: () => router.push('/savings') },
  { label: 'Expenses', icon: 'pi pi-arrow-down', command: () => router.push('/expenses') },
  { label: 'Incomes', icon: 'pi pi-arrow-up', command: () => router.push('/incomes') }
]);
</script>

<style scoped>
.p-menubar {
  border-radius: 0;
  background: #f9fafb;
  color: #222;
  border: none;
  box-shadow: 0 2px 8px 0 rgba(60,60,60,0.04);
}
.p-menubar .p-menuitem-link {
  color: #222;
  font-weight: 500;
  border-radius: 0;
  transition: background 0.2s;
}
.p-menubar .p-menuitem-link:hover {
  background: #f1f5f9;
}
.p-menubar .p-menubar-end .p-button {
  color: #2563eb;
  background: transparent;
  border-radius: 0;
}
</style>
