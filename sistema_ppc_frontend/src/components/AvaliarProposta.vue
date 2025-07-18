<template>
  <div class="container py-5">
    <div class="text-center mb-4">
      <img src="/logo_asttic.png" alt="Logo ASTTIC" style="max-width: 100px;" />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Análise da Proposta</h2>

      <div v-if="proposta">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th>Nome do curso</th>
              <td>{{ proposta.nome }}</td>
            </tr>
            <tr>
              <th>Carga horária total</th>
              <td>{{ proposta.carga_horaria_total }} horas</td>
            </tr>
            <tr>
              <th>Semestres</th>
              <td>{{ proposta.quantidade_semestres }}</td>
            </tr>
            <tr>
              <th>Justificativa</th>
              <td>{{ proposta.justificativa }}</td>
            </tr>
            <tr>
              <th>Impacto social</th>
              <td>{{ proposta.impacto_social }}</td>
            </tr>
          </tbody>
        </table>

        <h4 class="mt-4">Disciplinas</h4>
        <ul class="list-group mb-4">
          <li class="list-group-item" v-for="(d, index) in proposta.disciplinas" :key="index">
            {{ d.nome }} — {{ d.carga_horaria }}h — {{ d.semestre }}º semestre
          </li>
        </ul>

        <form @submit.prevent="enviarAvaliacao">
          <div class="mb-3">
            <label class="form-label">Comentário do avaliador:</label>
            <textarea v-model="comentario" required class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Ação:</label>
            <select v-model="acao" required class="form-select">
              <option disabled value="">Selecione</option>
              <option value="retornar">Retornar para ajustes</option>
              <option value="encaminhar">Encaminhar para decisão final</option>
            </select>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
          </div>
        </form>

        <div
          v-if="mensagem"
          :class="[
            'mt-3',
            'text-center',
            mensagem.includes('sucesso') ? 'alert alert-success' : 'alert alert-danger'
          ]"
        >
          {{ mensagem }}
        </div>
      </div>

      <div v-else class="alert alert-warning text-center">
        Carregando proposta...
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const propostaId = route.params.id
const proposta = ref<any>(null)
const comentario = ref('')
const acao = ref('')
const mensagem = ref('')

async function carregarProposta() {
  try {
    const token = localStorage.getItem('token')
    const resposta = await axios.get(`http://127.0.0.1:8000/api/propostas/${propostaId}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    proposta.value = resposta.data
  } catch (erro) {
    console.error('Erro ao carregar proposta:', erro)
    mensagem.value = 'Erro ao carregar dados da proposta.'
  }
}

async function enviarAvaliacao() {
  try {
    const token = localStorage.getItem('token')
    await axios.put(
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
    setTimeout(() => {
      router.push('/avaliacoes')
    }, 1000)
  } catch (erro) {
    console.error('Erro ao enviar avaliação:', erro)
    mensagem.value = 'Erro ao enviar avaliação.'
  }
}

onMounted(carregarProposta)
</script>
