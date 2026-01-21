<template>
  <v-container class="admin-panel-container">
    <h1 class="admin-title">Painel de Admin</h1>
    <v-row class="mb-4">
      <v-col cols="12" class="d-flex">
        <v-btn :color="tab === 'usuarios' ? 'primary' : undefined" @click="select('usuarios')">Usuários</v-btn>
        <v-btn :color="tab === 'cafes' ? 'primary' : undefined" @click="select('cafes')">Cafés</v-btn>
        <v-btn :color="tab === 'pedidos' ? 'primary' : undefined" @click="select('pedidos')">Pedidos</v-btn>
        <v-spacer />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <div v-if="tab === 'usuarios'">
          <h3 class="section-title">Usuários</h3>
          <v-simple-table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in usuarios" :key="u.id">
                <td>{{ u.id }}</td>
                <td>{{ u.nome }}</td>
                <td>{{ u.email }}</td>
                <td>
                  <v-btn small color="error" @click="deletarUsuario(u.id)">Excluir</v-btn>
                </td>
              </tr>
            </tbody>
          </v-simple-table>
          <div v-if="usuarios.length === 0" class="empty-state">Nenhum usuário encontrado.</div>
        </div>
        <div v-if="tab === 'filas'">
          <h3 class="section-title">Fila de Café</h3>
          <v-simple-table class="admin-table">
            <thead>
              <tr>
                <th>Posição</th>
                <th>Usuário</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(f, idx) in filas" :key="f.id">
                <td>{{ idx + 1 }}</td>
                <td>{{ f.usuario?.nome || f.usuario_id }}</td>
                <td>{{ f.status }}</td>
              </tr>
            </tbody>
          </v-simple-table>
          <div v-if="filas.length === 0" class="empty-state">Nenhuma fila encontrada.</div>
        </div>
        <div v-if="tab === 'cafes'">
          <h3 class="section-title">Cafés</h3>
          <v-simple-table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Marca</th>
                <th>Preço</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="cafe in cafes" :key="cafe.id">
                <td>{{ cafe.id }}</td>
                <td>{{ cafe.nome }}</td>
                <td>{{ cafe.marca }}</td>
                <td>R$ {{ cafe.preco }}</td>
              </tr>
            </tbody>
          </v-simple-table>
          <div v-if="cafes.length === 0" class="empty-state">Nenhum café encontrado.</div>
        </div>

        <div v-if="tab === 'pedidos'">
          <h3 class="section-title">Pedidos / Compras</h3>
          <v-simple-table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Café</th>
                <th>Preço</th>
                <th>Criado em</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="p in pedidos" :key="p.id">
                <td>{{ p.id }}</td>
                <td>{{ p.usuario?.nome || p.usuario_id }}</td>
                <td>{{ p.cafe?.nome || p.cafe_id }}</td>
                <td>R$ {{ p.preco }}</td>
                <td>{{ new Date(p.created_at).toLocaleString() }}</td>
              </tr>
            </tbody>
          </v-simple-table>
          <div v-if="pedidos.length === 0" class="empty-state">Nenhum pedido encontrado.</div>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { UsuarioService, FilaService, CafeService } from '../Controller/api'
import { usuario, initAuthFromStorage } from '../stores/auth'

const tab = ref('usuarios')
const usuarios = ref<any[]>([])
const filas = ref<any[]>([])
const cafes = ref<any[]>([])
const pedidos = ref<any[]>([])
const router = useRouter()

onMounted(() => {
  initAuthFromStorage()
  if (!usuario.value || !usuario.value.admin) {
    router.push('/')
    return
  }
  loadCurrent()
})

function select(t: string) {
  tab.value = t
  loadCurrent()
}

function voltarDashboard() {
  router.push('/')
}

async function loadUsuarios() {
  try {
    const { data } = await UsuarioService.listar()
    usuarios.value = data?.data || data || []
  } catch (e) {
    console.error('Erro ao buscar usuários', e)
    usuarios.value = []
  }
}

async function loadFilas() {
  try {
    const { data } = await FilaService.listar()
    filas.value = data?.data || data || []
  } catch (e) {
    console.warn('/fila não disponível ou erro', e)
    filas.value = []
  }
}

async function loadCafes() {
  try {
    const { data } = await CafeService.listar()
    cafes.value = data?.data || data || []
  } catch (e) {
    console.error('Erro ao buscar cafés', e)
    cafes.value = []
  }
}

async function loadPedidos() {
  try {
    const { data } = await import('../Controller/api').then(m => m.PedidoService.listar())
    pedidos.value = data?.data || data || []
  } catch (e) {
    console.error('Erro ao buscar pedidos', e)
    pedidos.value = []
  }
}

async function loadCurrent() {
  if (tab.value === 'usuarios') await loadUsuarios()
  if (tab.value === 'filas') await loadFilas()
  if (tab.value === 'cafes') await loadCafes()
  if (tab.value === 'pedidos') await loadPedidos()
}

async function deletarUsuario(id: number) {
  if (!confirm('Confirmar exclusão do usuário?')) return
  try {
    await UsuarioService.excluir(id)
    await loadUsuarios()
  } catch (e) {
    console.error('Erro ao excluir usuário', e)
    alert('Erro ao excluir usuário. Veja console.')
  }
}

function refresh() {
  loadCurrent()
}
</script>

<style scoped>
.admin-panel-container {
  background: linear-gradient(135deg, #000000 0%, #b30000 100%);
  min-height: 100vh;
  padding: 2rem;
}
.admin-panel-container .v-btn {
  border-radius: 0.5rem;
  font-weight: 600;
  text-transform: none;
}
.admin-panel-container .v-btn.v-btn--text {
  color: #ffd6d6;
}
.admin-panel-container .v-btn.v-btn--contained {
  box-shadow: none;
}
.admin-title {
  font-size: 2.2rem;
  font-weight: 700;
  color: #ffd6d6;
  margin-bottom: 2rem;
  text-align: center;
  text-shadow: 0 2px 12px rgba(179,0,0,0.25);
}
.section-title {
  font-size: 1.3rem;
  color: #ffffff;
  margin-bottom: 1rem;
  font-weight: 700;
}
.admin-table {
  background: #0b0b0b;
  border-radius: 1rem;
  box-shadow: 0 10px 40px rgba(0,0,0,0.6);
  border: 1px solid rgba(179,0,0,0.08);
  margin-bottom: 2rem;
  color: #e5e7eb;
}
.admin-table thead {
  background: linear-gradient(135deg, #1a1a1a 0%, #2b0000 100%);
  color: #e5e7eb;
}
.admin-table th {
  padding: 0.8rem;
  text-align: left;
  font-weight: 700;
  color: #c7c7c7;
  border-bottom: 1px solid rgba(255,255,255,0.04);
}
.admin-table td {
  padding: 0.9rem;
  color: #d1d5db;
  border-bottom: 1px solid rgba(255,255,255,0.04);
}
.admin-table tr:hover {
  background: linear-gradient(135deg, rgba(179,0,0,0.04), rgba(0,0,0,0.18));
}
.empty-state {
  text-align: center;
  color: #c7c7c7;
  font-size: 1rem;
  margin-top: 1.5rem;
  background: transparent;
  padding: 1rem 0;
}
</style>
