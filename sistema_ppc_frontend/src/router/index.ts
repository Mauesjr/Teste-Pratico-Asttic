import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import CriarProposta from '../components/CriarProposta.vue'
import PropostaUsuario from '../components/PropostaUsuario.vue'
import AvaliarProposta from '../components/AvaliarProposta.vue'
import PropostaParaAvaliar from '@/components/PropostaParaAvaliar.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/avaliar/:id', component: AvaliarProposta },
  { path: '/criar-proposta', component: CriarProposta },
  { path: '/minhas-propostas', component: () => PropostaUsuario },
  { path: '/avaliacoes', component: PropostaParaAvaliar }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router