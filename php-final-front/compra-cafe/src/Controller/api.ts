import axios from 'axios';

export const api = axios.create({
  baseURL: 'http://127.0.0.1:8001/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  try {
    const fullUrl = (config.baseURL || '') + (config.url || '')
    console.log('[API] Request ->', (config.method || 'GET').toUpperCase(), fullUrl)
  } catch (e) {
    //
  }

  return config
}, (error) => {
  console.error('[API] Request error ->', error?.message || error)
  return Promise.reject(error)
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    try {
      const reqUrl = error.config ? ((error.config.baseURL || '') + (error.config.url || '')) : 'unknown'
      console.error('[API] Response error ->', reqUrl, error.message || error)
    } catch (e) {
      //
    }

    return Promise.reject(error)
  }
)

export const CafeService = {
  listar: () => api.get('/cafes'),
  buscar: (id: number) => api.get(`/cafes/${id}`),
  criar: (data: any) => api.post('/cafes', data),
  excluir: (id: number) => api.delete(`/cafes/${id}`),
  comprar: (id: number) => api.post(`/cafes/${id}/comprar`)
}

export const UsuarioService = {
  registro: (data: any) => api.post('/registro', data),
  login: (email: string, senha: string) => api.post('/login', { email, senha }),
  listar: () => api.get('/usuarios'),
  buscar: (id: number) => api.get(`/usuarios/${id}`),
  atualizar: (id: number, data: any) => api.put(`/usuarios/${id}`, data),
  excluir: (id: number) => api.delete(`/usuarios/${id}`),
}

export const PedidoService = {
  listar: () => api.get('/pedidos'),
  obter: (id: number) => api.get(`/pedidos/${id}`),
  criar: (data: any) => api.post('/pedidos', data),
  atualizar: (id: number, data: any) => api.put(`/pedidos/${id}`, data)
}

export const FilaService = {
  listar: () => api.get('/fila'),
}

export default api;