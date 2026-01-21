<script lang="ts" setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { UsuarioService } from '../Controller/api'
import { setUser } from '../stores/auth'

const router = useRouter()
const email = ref('')
const senha = ref('')
const loading = ref(false)

async function fazerLogin() {
  if (!email.value || !senha.value) {
    alert('Por favor, preencha todos os campos.')
    return
  }

  loading.value = true
  try {
    const { data } = await UsuarioService.login(email.value, senha.value)
    const user = data.data?.user
    const token = data.data?.token
    if (token) {
      localStorage.setItem('auth_token', token)
    }
    if (user) {
      localStorage.setItem('usuario', JSON.stringify(user))
      setUser(user)
    }
    alert('Login realizado com sucesso!')
    window.dispatchEvent(new Event('auth-changed'))
    router.push('/')
  } catch (error: any) {
    console.error('Erro ao fazer login:', error)
    alert(error.response?.data?.message || 'Erro ao fazer login. Verifique suas credenciais.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card elevation="2">
          <v-card-title class="headline">Login</v-card-title>
          
          <v-card-text>
            <v-form @submit.prevent="fazerLogin" ref="formRef">
              <v-text-field v-model="email" label="Email" type="email" required />
              <v-text-field v-model="senha" label="Senha" type="password" required />
            </v-form>
          </v-card-text>
          
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="fazerLogin" :loading="loading">Entrar</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>