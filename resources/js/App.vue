<template>
  <TopMenu />
  <div class="p-4">
    <router-view />
  </div>
</template>

<script setup>
import TopMenu from './components/TopMenu.vue';
</script>
