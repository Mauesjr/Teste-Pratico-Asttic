<template>
  <div class="container py-5">
    <div class="text-center mb-4">
      <img src="/logo_asttic.png" alt="Logo" style="max-width: 100px;" />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Propostas para Decisão Final</h2>

      <div v-if="propostas.length > 0">
        <table class="table table-bordered table-hover">
          <thead class="table-light text-center">
            <tr>
              <th>Curso</th>
              <th>Semestres</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="proposta in propostas" :key="proposta.id" class="text-center align-middle">
              <td>{{ proposta.nome }}</td>
              <td>{{ proposta.quantidade_semestres }}</td>
              <td>
                <span class="badge bg-info text-dark">
                  {{ formatarStatus(proposta.status) }}
                </span>
              </td>
              <td>
                <button class="btn btn-outline-success btn-sm" @click="verDetalhes(proposta.id)">
                  Ver Detalhes
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="alert alert-info text-center">
        Nenhuma proposta aguardando decisão no momento.
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

function verDetalhes(id) {
  router.push(`/decidir/${id}`)
}

function formatarStatus(status) {
  const map = {
    em_aprovacao: 'Em aprovação',
    approved: 'Aprovada',
    rejected: 'Rejeitada',
  }
  return map[status] || status
}

onMounted(async () => {
  try {
    const token = localStorage.getItem('token')
    const response = await axios.get('http://localhost:8000/api/propostas-para-decisao', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    propostas.value = response.data
  } catch (error) {
    console.error('Erro ao buscar propostas para decisão:', error)
    alert('Erro ao buscar propostas para decisão.')
  }
})
</script>


