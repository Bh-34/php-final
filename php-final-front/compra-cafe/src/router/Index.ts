import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Cadastro from '../views/Cadastro.vue'
import Login from '../views/Login.vue'
import Admin from '../views/AdminPanel.vue'
import CafeDetalhes from '../views/CafeDetalhes.vue'

const routes = [
  { path: '/', name: 'Dashboard', component: Dashboard },
  { path: '/cadastro', name: 'Cadastro', component: Cadastro },
  { path: '/login', name: 'Login', component: Login },
  { path: '/admin', name: 'Admin', component: Admin },
  { path: '/cafe/:id', name: 'CafeDetalhes', component: CafeDetalhes },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
