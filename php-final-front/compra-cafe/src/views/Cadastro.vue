<script lang="ts" setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { UsuarioService } from '../Controller/api'
import { setUser } from '../stores/auth'

const nome = ref('')
const email = ref('')
const senha = ref('')
const loading = ref(false)
const router = useRouter()

async function cadastrar() {
  if (!nome.value || !email.value || !senha.value) {
    alert('Por favor, preencha todos os campos.')
    return
  }

  loading.value = true
  try {    
    const payload = {
      nome: nome.value,
      email: email.value,
      senha_hash: senha.value
    }

    const { data } = await UsuarioService.registro(payload)
    console.log('Usuário cadastrado com sucesso:', data)
    
    if (data.token) {
      console.log('[Auth] Salvando auth_token no localStorage:', data.token)
      localStorage.setItem('auth_token', data.token)
      console.log('[Auth] auth_token salvo')
    }
    if (data.user) {
      console.log('[Auth] Salvando usuario no localStorage:', data.user)
      localStorage.setItem('usuario', JSON.stringify(data.user))
      
      setUser(data.user)
      console.log('[Auth] usuario salvo e store atualizado')
    }
    
    alert('Cadastro realizado com sucesso!')
    
    nome.value = ''
    email.value = ''
    senha.value = ''
    
    window.dispatchEvent(new Event('auth-changed'))
    router.push('/')
  } catch (error: any) {
    console.error('Erro ao cadastrar usuário:', error)
    alert(error.response?.data?.message || 'Erro ao cadastrar usuário. Veja console para detalhes.')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <v-container>
    <h1>Cadastro</h1>

    <v-form @submit.prevent="cadastrar" ref="formRef">
      <v-row>
        <v-col cols="12">
          <v-text-field v-model="nome" label="Nome" required />
        </v-col>

        <v-col cols="12">
          <v-text-field v-model="email" label="Email" type="email" required />
        </v-col>

        <v-col cols="12">
          <v-text-field v-model="senha" label="Senha" type="password" required />
        </v-col>

        <v-col cols="12" class="d-flex">
          <v-btn color="primary" type="submit" :loading="loading">Cadastrar</v-btn>
          <v-spacer />
          <v-btn text @click="() => { nome = ''; email = ''; senha = '' }">Limpar</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>