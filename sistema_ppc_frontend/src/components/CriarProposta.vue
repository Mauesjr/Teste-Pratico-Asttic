<template>
  <div class="container py-5">
    <!-- Logo da ASTTIC centralizado -->
    <div class="text-center mb-4">
      <img
        src="/logo_asttic.png"
        alt="Logo da ASTTIC"
        style="max-width: 100px; height: auto;"
        class="mb-2"
      />
    </div>

    <div class="card shadow p-4">
      <h2 class="text-center mb-4">Nova Proposta de Curso</h2>

      <form @submit.prevent="enviarProposta">
        <div class="mb-3">
          <label class="form-label">Nome do curso:</label>
          <input v-model="nome" type="text" class="form-control" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Carga horária total:</label>
          <input v-model.number="cargaHoraria" type="number" class="form-control" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Quantidade de semestres:</label>
          <input v-model.number="quantidadeSemestres" type="number" class="form-control" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Justificativa:</label>
          <textarea v-model="justificativa" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-4">
          <label class="form-label">Impacto social:</label>
          <textarea v-model="impactoSocial" class="form-control" rows="3" required></textarea>
        </div>

        <hr class="my-4" />
        <h3 class="mb-3">Disciplinas</h3>

        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Nome:</label>
            <input v-model="disciplinaNome" type="text" class="form-control" />
          </div>

          <div class="col-md-3">
            <label class="form-label">Carga horária:</label>
            <input v-model.number="disciplinaCarga" type="number" class="form-control" />
          </div>

          <div class="col-md-3">
            <label class="form-label">Semestre:</label>
            <input v-model.number="disciplinaSemestre" type="number" class="form-control" />
          </div>

          <div class="col-md-2">
            <button type="button" @click="adicionarDisciplina" class="btn btn-success w-100">
              Adicionar
            </button>
          </div>
        </div>

        <ul class="list-group my-4">
          <li
            v-for="(d, index) in disciplinas"
            :key="index"
            class="list-group-item d-flex justify-content-between align-items-center"
          >
            {{ d.nome }} ({{ d.carga_horaria }}h - {{ d.semestre }}º semestre)
            <button
              type="button"
              class="btn btn-sm btn-danger"
              @click="removerDisciplina(index)"
            >
              Remover
            </button>
          </li>
        </ul>

        <button type="submit" class="btn btn-primary w-100">Enviar Proposta</button>
      </form>

      <p v-if="mensagem" class="mt-3 text-center text-success fw-bold">{{ mensagem }}</p>
    </div>
  </div>
</template>


<script lang="ts" setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const nome = ref('')
const cargaHoraria = ref(0)
const quantidadeSemestres = ref(0)
const justificativa = ref('')
const impactoSocial = ref('')
const mensagem = ref('')
const tipoMensagem = ref<'sucesso' | 'erro' | ''>('')

// disciplinas
const disciplinas = ref<{ nome: string; carga_horaria: number; semestre: number }[]>([])
const disciplinaNome = ref('')
const disciplinaCarga = ref(0)
const disciplinaSemestre = ref(1)

function adicionarDisciplina() {
  if (disciplinaNome.value && disciplinaCarga.value > 0 && disciplinaSemestre.value > 0) {
    disciplinas.value.push({
      nome: disciplinaNome.value,
      carga_horaria: disciplinaCarga.value,
      semestre: disciplinaSemestre.value,
    })
    disciplinaNome.value = ''
    disciplinaCarga.value = 0
    disciplinaSemestre.value = 1
  }
}

function removerDisciplina(index: number) {
  disciplinas.value.splice(index, 1)
}

async function enviarProposta() {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      mensagem.value = 'Usuário não autenticado.'
      tipoMensagem.value = 'erro'
      return
    }

    await axios.post(
      'http://127.0.0.1:8000/api/propostas',
      {
        nome: nome.value,
        carga_horaria_total: cargaHoraria.value,
        quantidade_semestres: quantidadeSemestres.value,
        justificativa: justificativa.value,
        impacto_social: impactoSocial.value,
        disciplinas: disciplinas.value,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )

    mensagem.value = 'Proposta enviada com sucesso!'
    tipoMensagem.value = 'sucesso'

    // Espera 10 segundos, depois redireciona
    setTimeout(() => {
      mensagem.value = ''
      tipoMensagem.value = ''
      router.push('/minhas-propostas')
    }, 10000)
  } catch (erro) {
    console.error(erro)
    mensagem.value = 'Erro ao enviar proposta.'
    tipoMensagem.value = 'erro'

  }
}
</script>
