<template>
  <Dialog v-model:visible="visible" :header="header" :modal="true" :style="{ width: '400px' }" :closable="true" @hide="onHide">
    <slot />
  </Dialog>
</template>

<script setup>
import { ref, watch } from 'vue';
import Dialog from 'primevue/dialog';

const props = defineProps({
  modelValue: Boolean,
  header: String
});
const emit = defineEmits(['update:modelValue']);
const visible = ref(props.modelValue);

watch(() => props.modelValue, v => visible.value = v);
watch(visible, v => emit('update:modelValue', v));

function onHide() {
  visible.value = false;
}
</script>
