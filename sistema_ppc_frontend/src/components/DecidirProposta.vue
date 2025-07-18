<template>
  <div class="container py-5">
    <div class="text-center mb-4">
      <img src="/logo_asttic.png" alt="Logo ASTTIC" style="max-width: 100px" />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Decidir Proposta</h2>

      <div v-if="proposta">
        <p><strong>Nome do Curso:</strong> {{ proposta.nome }}</p>
        <p><strong>Justificativa:</strong> {{ proposta.justificativa }}</p>
        <p><strong>Impacto Social:</strong> {{ proposta.impacto_social }}</p>

        <!-- Accordion para disciplinas -->
        <details class="my-3">
          <summary class="text-primary">Ver disciplinas ({{ proposta.disciplinas.length }})</summary>
          <table class="table table-sm mt-2">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Semestre</th>
                <th>Carga Horária</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="disciplina in proposta.disciplinas" :key="disciplina.id">
                <td>{{ disciplina.nome }}</td>
                <td>{{ disciplina.semestre }}</td>
                <td>{{ disciplina.carga_horaria }}h</td>
              </tr>
            </tbody>
          </table>
        </details>

        <div class="form-group my-3">
          <label for="comentario">Comentário do Decisor</label>
          <textarea id="comentario" v-model="comentario" class="form-control" rows="3"></textarea>
        </div>

        <div class="text-center mt-4">
          <button class="btn btn-success me-2" @click="decidir('aprovar')">Aprovar</button>
          <button class="btn btn-danger" @click="decidir('reprovar')">Reprovar</button>
        </div>
      </div>

      <div v-else class="text-center">
        Carregando proposta...
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const proposta = ref(null)
const comentario = ref('')

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
    alert('Erro ao carregar proposta para decisão.')
  }
})

const decidir = async (decisao) => {
  const id = route.params.id
  const token = localStorage.getItem('token')
  try {
    await axios.put(`http://localhost:8000/api/propostas/${id}/decidir`, {
      decisao,
      comentario_decisor: comentario.value
    }, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    alert('Decisão registrada com sucesso.')
    router.push('/propostas-para-decisao')
  } catch (error) {
    console.error('Erro ao registrar decisão:', error)
    alert('Erro ao registrar decisão.')
  }
}
</script>
