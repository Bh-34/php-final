import { ref } from 'vue'

export const usuario = ref<any | null>(null)

export function setUser(u: any | null) {
  usuario.value = u
}

export function clearUser() {
  usuario.value = null
}

export function initAuthFromStorage() {
  const usuarioJSON = localStorage.getItem('usuario')
  if (usuarioJSON) {
    try {
      usuario.value = JSON.parse(usuarioJSON)
      return
    } catch (e) {
      console.error('auth.init: falha ao parsear usuario', e)
    }
  }
  usuario.value = null
}

export default {
  usuario,
  setUser,
  clearUser,
  initAuthFromStorage
}
