<template>
  <div class="container py-5">
    <!-- Logo da ASTTIC -->
    <div class="text-center mb-4">
      <img
        src="/logo_asttic.png"
        alt="Logo da ASTTIC"
        style="max-width: 100px; height: auto;"
        class="mb-2"
      />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Minhas Propostas de Curso</h2>

      <div v-if="propostas.length > 0">
        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th class="text-end">Nome do Curso</th>
              <th class="text-end">Carga Horária</th>
              <th class="text-end">Semestres</th>
              <th class="text-end">Status</th>
              <th class="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="proposta in propostas" :key="proposta.id">
              <td class="text-end">{{ proposta.nome }}</td>
              <td class="text-end">{{ proposta.carga_horaria_total }}h</td>
              <td class="text-end">{{ proposta.quantidade_semestres }}</td>
              <td class="text-end">
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
              <td class="text-center">
                <div v-if="proposta.status === 'mudanças_requeridas'" class="d-flex flex-column gap-2">
                  <button
                    class="btn btn-outline-primary btn-sm"
                    @click="verComentario(proposta.comentario_avaliador)"
                  >
                    Ver Comentário
                  </button>
                  <button
                    class="btn btn-outline-success btn-sm"
                    @click="router.push(`/propostas/${proposta.id}/corrigir-proposta`)"
                  >
                    Corrigir Proposta
                  </button>
                </div>
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

    <!-- Modal de Comentário -->
    <div
      class="modal fade show"
      tabindex="-1"
      style="display: block"
      v-if="showModal"
      @click.self="showModal = false"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Comentário do Avaliador</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body text-end">
            <p>{{ comentarioSelecionado }}</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" @click="showModal = false">
              Fechar
            </button>
          </div>
        </div>
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
const comentarioSelecionado = ref('')
const showModal = ref(false)

const verComentario = (comentario) => {
  comentarioSelecionado.value = comentario
  showModal.value = true
}

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
