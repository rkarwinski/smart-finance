<template>
  <div class="welcome-container">
    <div class="welcome-card">
      <h1>Bem-vindo ao Smart Finance!</h1>
      <p class="subtitle">Sua plataforma de finanças inteligente</p>
      <p class="api-message">{{ message }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const message = ref('Carregando mensagem...')

onMounted(async () => {
  try {
    const res = await fetch('/api/welcome')
    const data = await res.json()
    message.value = data.message
  } catch (e) {
    message.value = 'Erro ao carregar mensagem.'
  }
})
</script>

<style scoped>
.welcome-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
}
.welcome-card {
  background: #fff;
  border-radius: 1.5rem;
  box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);
  padding: 3rem 2.5rem;
  text-align: center;
  max-width: 400px;
}
.welcome-card h1 {
  font-size: 2.2rem;
  color: #1e293b;
  margin-bottom: 0.5rem;
}
.subtitle {
  color: #64748b;
  font-size: 1.1rem;
  margin-bottom: 1.5rem;
}
.api-message {
  color: #0ea5e9;
  font-weight: 500;
  font-size: 1.1rem;
}
</style>
