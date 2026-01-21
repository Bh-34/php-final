<template>
  <v-app-bar app color="primary" dark>
    <v-toolbar-title>Café dos Guri </v-toolbar-title>
    <v-spacer />
    <v-btn to="/" text>Dashboard</v-btn>
    <v-btn to="/admin" text>Painel Admin</v-btn>

    <!-- Mostrar Menu de Usuário (se autenticado) ou Botões de Cadastro/Login (se não) -->
    <template v-if="usuarioAutenticado && usuarioRef.value">
      <v-menu>
        <template #activator="{ props }">
          <v-btn icon v-bind="props" text>
            <v-avatar size="32" color="secondary">
              <span class="text-white font-weight-bold">{{ iniciais }}</span>
            </v-avatar>
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title class="font-weight-bold">{{ usuarioRef.value.nome }}</v-list-item-title>
            <v-list-item-subtitle>{{ usuarioRef.value.email }}</v-list-item-subtitle>
          </v-list-item>
          <v-divider class="my-2"></v-divider>
          <v-list-item @click="logout">
            <template #prepend>
              <v-icon>mdi-logout</v-icon>
            </template>
            <v-list-item-title>Sair</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </template>

    <template v-else>
      <v-btn to="/cadastro" text>Cadastro</v-btn>
      <v-btn to="/login" text>Login</v-btn>
    </template>
  </v-app-bar>
</template>

<script lang="ts" setup>
import { computed, onMounted, onUnmounted, watch, ref } from 'vue'
import { useRouter } from 'vue-router'
import { usuario, initAuthFromStorage, clearUser } from '../stores/auth'

const router = useRouter()
const usuarioRef = usuario
const isAuthenticated = ref(false)
const usuarioAutenticado = computed(() => isAuthenticated.value)

const iniciais = computed(() => {
  if (!usuarioRef.value?.nome) return 'U'
  return usuarioRef.value.nome
    .split(' ')
    .map((word: string) => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

function onAuthChanged() {
  initAuthFromStorage()
  isAuthenticated.value = !!localStorage.getItem('auth_token') && !!usuarioRef.value
}

onMounted(() => {
  initAuthFromStorage()
  isAuthenticated.value = !!localStorage.getItem('auth_token') && !!usuarioRef.value
  watch(usuarioRef, (val) => {
    isAuthenticated.value = !!localStorage.getItem('auth_token') && !!val
  })
  window.addEventListener('auth-changed', onAuthChanged)
})

onUnmounted(() => {
  window.removeEventListener('auth-changed', onAuthChanged)
})

function logout() {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('usuario')
  clearUser()
  window.dispatchEvent(new Event('auth-changed'))
  router.push('/login')
}
</script>

<style scoped>
.v-avatar {
  cursor: pointer;
}
</style>
