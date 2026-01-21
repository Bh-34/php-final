<template>
  <div class="cafe-detalhes-container">
    <div v-if="loading" class="loading">Carregando...</div>
    <div v-else-if="cafe" class="cafe-detalhes-card">
      <h1 class="cafe-nome">{{ cafe.nome }}</h1>
      <p class="cafe-marca">Marca: {{ cafe.marca }}</p>
      <p class="cafe-preco">Preço: <strong>R$ {{ cafe.preco }}</strong></p>
      <p v-if="cafe.descricao" class="cafe-desc">Descrição: {{ cafe.descricao }}</p>
      <button class="btn-voltar" @click="voltar">Voltar</button>
    </div>
    <div v-else class="empty-state">Café não encontrado.</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { CafeService } from '../Controller/api'

const route = useRoute()
const router = useRouter()
const cafe = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  const id = route.params.id
  try {
    const res = await CafeService.buscar(Number(id))
    cafe.value = res.data.data
  } catch (e) {
    cafe.value = null
  } finally {
    loading.value = false
  }
})

function voltar() {
  router.push('/')
}
</script>

<style scoped>
.cafe-detalhes-container {
  max-width: 480px;
  margin: 2rem auto;
  background: #0b0b0b;
  color: #ffffff;
  border-radius: 1.5rem;
  box-shadow: 0 10px 60px rgba(0,0,0,0.8);
  padding: 2rem;
  text-align: center;
}
.cafe-nome {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
}
.cafe-marca, .cafe-preco, .cafe-desc {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}
.btn-voltar {
  background: #b30000;
  color: #fff;
  border: none;
  border-radius: 0.5rem;
  padding: 0.5rem 1.2rem;
  font-weight: 600;
  cursor: pointer;
  margin-top: 1.5rem;
}
.btn-voltar:hover {
  background: #8b0000;
}
.loading {
  text-align: center;
  font-size: 1.2rem;
  color: #ff3333;
}
.empty-state {
  text-align: center;
  color: #9ca3af;
  font-size: 1.1rem;
  margin-top: 2rem;
}
</style>
