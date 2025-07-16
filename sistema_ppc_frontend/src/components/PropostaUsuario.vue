<template>
  <div class="container py-5">
    <!-- Logo da ASTTIC -->
    <div class="text-center mb-4">
      <img
        src="/logo_asttic.png"
        alt="Logo da ASTTIC"
        style="max-width: 80px; height: auto;"
        class="mb-2"
      />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Minhas Propostas de Curso</h2>

      <div v-if="propostas.length > 0">
        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>Nome do Curso</th>
              <th>Carga Horária</th>
              <th>Semestres</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="proposta in propostas" :key="proposta.id">
              <td>{{ proposta.nome }}</td>
              <td>{{ proposta.carga_horaria_total }}h</td>
              <td>{{ proposta.quantidade_semestres }}</td>
              <td>
                <span
                  class="badge"
                  :class="{
                    'bg-secondary': proposta.status === 'submitted',
                    'bg-warning text-dark': proposta.status === 'mudanças_requeridas',
                    'bg-info text-dark': proposta.status === 'em_aprovacao',
                    'bg-success': proposta.status === 'approved',
                    'bg-danger': proposta.status === 'rejected'
                  }"
                >
                  {{ statusLegivel(proposta.status) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="alert alert-info text-center">
        Nenhuma proposta cadastrada ainda.
      </div>

      <div class="text-center mt-4">
        <button @click="router.push('/criar-proposta')" class="btn btn-primary">
          Criar Nova Proposta
        </button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const propostas = ref([])
const router = useRouter()

// Função para traduzir o status para algo mais legível
const statusLegivel = (status) => {
  switch (status) {
    case 'submitted':
      return 'Aguardando avaliação'
    case 'mudanças_requeridas':
      return 'Retornada para correção'
    case 'em_aprovacao':
      return 'Em aprovação final'
    case 'approved':
      return 'Aprovada'
    case 'rejected':
      return 'Reprovada'
    default:
      return status
  }
}

// Busca as propostas do usuário logado ao carregar a página
onMounted(async () => {
  try {
    const token = localStorage.getItem('token')
    const response = await axios.get('http://localhost:8000/api/minhas-propostas', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    propostas.value = response.data
  } catch (error) {
    console.error('Erro ao buscar propostas:', error)
    alert('Erro ao buscar suas propostas. Verifique seu login.')
  }
})
</script>

