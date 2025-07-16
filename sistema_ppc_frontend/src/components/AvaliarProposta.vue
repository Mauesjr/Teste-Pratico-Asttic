<template>
  <div v-if="proposta">
    <h2>Análise da Proposta</h2>

    <p><strong>Nome do curso:</strong> {{ proposta.nome }}</p>
    <p><strong>Carga horária total:</strong> {{ proposta.carga_horaria_total }}</p>
    <p><strong>Semestres:</strong> {{ proposta.quantidade_semestres }}</p>
    <p><strong>Justificativa:</strong> {{ proposta.justificativa }}</p>
    <p><strong>Impacto social:</strong> {{ proposta.impacto_social }}</p>

    <h3>Disciplinas:</h3>
    <ul>
      <li v-for="(d, index) in proposta.disciplinas" :key="index">
        {{ d.nome }} ({{ d.carga_horaria }}h - {{ d.semestre }}º semestre)
      </li>
    </ul>

    <hr />

    <form @submit.prevent="enviarAvaliacao">
      <label>Comentário do avaliador:</label><br />
      <textarea v-model="comentario" required></textarea>

      <div>
        <label>Ação:</label><br />
        <select v-model="acao" required>
          <option disabled value="">Selecione</option>
          <option value="retornar">Retornar para ajustes</option>
          <option value="encaminhar">Encaminhar para decisão final</option>
        </select>
      </div>

      <button type="submit">Enviar Avaliação</button>
    </form>

    <p v-if="mensagem">{{ mensagem }}</p>
  </div>

  <div v-else>
    <p>Carregando proposta...</p>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const propostaId = route.params.id

const proposta = ref<any>(null)
const comentario = ref('')
const acao = ref('')
const mensagem = ref('')

async function carregarProposta() {
  const token = localStorage.getItem('token')
  const resposta = await axios.get(`http://127.0.0.1:8000/api/propostas/${propostaId}`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
  proposta.value = resposta.data
}

async function enviarAvaliacao() {
  try {
    const token = localStorage.getItem('token')
    const resposta = await axios.put(
      `http://127.0.0.1:8000/api/propostas/${propostaId}/avaliar`,
      {
        comentario_avaliador: comentario.value,
        acao: acao.value,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )
    mensagem.value = 'Avaliação enviada com sucesso!'
  } catch (erro) {
    console.error(erro)
    mensagem.value = 'Erro ao enviar avaliação.'
  }
}

onMounted(() => {
  carregarProposta()
})
</script>
