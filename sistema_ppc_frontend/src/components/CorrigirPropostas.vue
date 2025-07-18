<template>
  <div class="container py-5">
    <div class="text-center mb-4">
      <img
        src="/logo_asttic.png"
        alt="Logo da ASTTIC"
        style="max-width: 100px; height: auto"
        class="mb-3"
      />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Corrigir Proposta de Curso</h2>

      <div v-if="proposta">
        <div class="alert alert-warning fw-bold fs-5 text-center">
          Comentário do Avaliador: {{ proposta.comentario_avaliador }}
        </div>

        <form @submit.prevent="enviarPropostaCorrigida">
          <div class="mb-3">
            <label class="form-label">Nome do Curso</label>
            <input v-model="proposta.nome" type="text" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Carga Horária Total</label>
            <input v-model.number="proposta.carga_horaria_total" type="number" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Quantidade de Semestres</label>
            <input v-model.number="proposta.quantidade_semestres" type="number" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Justificativa</label>
            <textarea v-model="proposta.justificativa" class="form-control" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Impacto Social</label>
            <textarea v-model="proposta.impacto_social" class="form-control" rows="3" required></textarea>
          </div>

          <div v-for="(disciplina, index) in proposta.disciplinas" :key="index" class="mb-4 border p-3 rounded">
            <h5 class="mb-3">Disciplina {{ index + 1 }}</h5>
            <div class="mb-2">
              <label class="form-label">Nome</label>
              <input v-model="disciplina.nome" type="text" class="form-control" required />
            </div>
            <div class="mb-2">
              <label class="form-label">Carga Horária</label>
              <input v-model.number="disciplina.carga_horaria" type="number" class="form-control" required />
            </div>
            <div class="mb-2">
              <label class="form-label">Semestre</label>
              <input v-model.number="disciplina.semestre" type="number" class="form-control" required />
            </div>
          </div>

          <p class="text-center mt-4 text-danger">
            Esta correção é única. Após o envio, não será possível realizar novas alterações.<br />
            Você poderá apenas visualizar o resultado na aba "Minhas Propostas".
          </p>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">
              Enviar para Câmara de Decisão
            </button>
          </div>
        </form>
      </div>

      <div v-else class="text-center">Carregando proposta...</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const proposta = ref(null)
const route = useRoute()
const router = useRouter()

onMounted(async () => {
  const id = route.params.id
  const token = localStorage.getItem('token')

  try {
    const response = await axios.get(`http://localhost:8000/api/propostas/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    proposta.value = response.data
  } catch (error) {
    console.error('Erro ao carregar proposta:', error)
    alert('Erro ao carregar proposta para correção.')
  }
})

const enviarPropostaCorrigida = async () => {
  const id = route.params.id
  const token = localStorage.getItem('token')

  try {
    const response = await axios.put(`http://localhost:8000/api/propostas/${id}/corrigir`, proposta.value, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    alert('Proposta corrigida e enviada com sucesso!')
    router.push('/propostas-para-decisao')
  } catch (error) {
    console.error('Erro ao enviar proposta corrigida:', error)
    alert('Erro ao enviar proposta corrigida.')
  }
}
</script>

<style scoped>
label {
  font-weight: bold;
}
</style>
