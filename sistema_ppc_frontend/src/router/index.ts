import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import CriarProposta from '../components/CriarProposta.vue'
import AvaliarProposta from '../components/AvaliarProposta.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/avaliar/:id', component: AvaliarProposta },
  { path: '/criar-proposta', component: CriarProposta },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router