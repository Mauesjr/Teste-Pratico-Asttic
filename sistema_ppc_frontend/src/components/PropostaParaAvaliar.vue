<template>
  <div class="container py-5">
    <div class="text-center mb-4">
      <img src="/logo_asttic.png" alt="Logo ASTTIC" style="max-width: 100px;" />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Propostas Disponíveis para Avaliação</h2>

      <div v-if="propostas.length > 0">
        <table class="table table-bordered table-hover">
          <thead class="table-light text-center">
            <tr>
              <th>Nome do Curso</th>
              <th>Autor</th>
              <th>Justificativa</th>
              <th>Impacto Social</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in propostas" :key="p.id" class="text-center align-middle">
              <td>{{ p.nome }}</td>
              <td>{{ p.autor?.nome || 'N/A' }}</td>
              <td>{{ p.justificativa }}</td>
              <td>{{ p.impacto_social }}</td>
              <td>
                <span class="badge bg-info text-dark">
                  {{ formatarStatus(p.status) }}
                </span>
              </td>
              <td>
                <button class="btn btn-secondary btn-sm" @click="avaliarProposta(p.id)">
                  Visualizar
                </button>
                <button
                  v-if="p.status === 'em_aprovacao'"
                  class="btn btn-primary btn-sm ms-2"
                  @click="avaliarProposta(p.id)"
                >
                  Avaliar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="alert alert-info text-center">
        Nenhuma proposta aguardando avaliação.
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

async function avaliarProposta(id) {
  router.push(`/avaliar/${id}`)
}

const formatarStatus = (status) => {
  const map = {
    em_aprovacao: 'Aguardando Avaliação',
    approved: 'Aprovada',
    rejected: 'Reprovada',
    encaminhada_decisor: 'Encaminhada ao Decisor',
  }
  return map[status] || status
}

onMounted(async () => {
  try {
    const token = localStorage.getItem('token')
    const response = await axios.get('http://localhost:8000/api/propostas-para-avaliar', {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
      }
    })
    propostas.value = response.data
  } catch (error) {
    console.error('Erro ao buscar propostas para avaliar:', error)
  }
})
</script>
