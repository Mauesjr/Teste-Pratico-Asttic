import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import CriarProposta from '../components/CriarProposta.vue'
import PropostaUsuario from '../components/PropostaUsuario.vue'
import AvaliarProposta from '../components/AvaliarProposta.vue'
import PropostaParaAvaliar from '@/components/PropostaParaAvaliar.vue'
import CorrigirPropostas from '@/components/CorrigirPropostas.vue'
import PropostaParaDecidir from '@/components/PropostaParaDecidir.vue'
import DecidirProposta from '@/components/DecidirProposta.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/avaliar/:id', component: AvaliarProposta },
  { path: '/criar-proposta', component: CriarProposta },
  { path: '/minhas-propostas', component: () => PropostaUsuario },
  { path: '/avaliacoes', component: PropostaParaAvaliar },
  { path: '/propostas/:id/corrigir-proposta', component: () => CorrigirPropostas },
  { path: '/propostas-para-decisao', component: PropostaParaDecidir },
  { path: '/decidir/:id', component: () => DecidirProposta }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router