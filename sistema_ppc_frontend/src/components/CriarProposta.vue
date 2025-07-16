<template>
  <div>
    <h2>Nova Proposta de Curso</h2>

    <form @submit.prevent="enviarProposta">
      <div>
        <label>Nome do curso:</label>
        <input v-model="nome" required />
      </div>

      <div>
        <label>Carga horária total:</label>
        <input v-model.number="cargaHoraria" type="number" required />
      </div>

      <div>
        <label>Quantidade de semestres:</label>
        <input v-model.number="quantidadeSemestres" type="number" required />
      </div>

      <div>
        <label>Justificativa:</label><br />
        <textarea v-model="justificativa" required />
      </div>

      <div>
        <label>Impacto social:</label><br />
        <textarea v-model="impactoSocial" required />
      </div>

      <hr />
      <h3>Disciplinas</h3>

      <div>
        <label>Nome:</label>
        <input v-model="disciplinaNome" />
      </div>
      <div>
        <label>Carga horária:</label>
        <input v-model.number="disciplinaCarga" type="number" />
      </div>
      <div>
        <label>Semestre:</label>
        <input v-model.number="disciplinaSemestre" type="number" />
      </div>
      <button type="button" @click="adicionarDisciplina">Adicionar disciplina</button>

      <ul>
        <li v-for="(d, index) in disciplinas" :key="index">
          {{ d.nome }} ({{ d.carga_horaria }}h - {{ d.semestre }}º semestre)
          <button type="button" @click="removerDisciplina(index)">Remover</button>
        </li>
      </ul>

      <br />
      <button type="submit">Enviar Proposta</button>
    </form>

    <p v-if="mensagem">{{ mensagem }}</p>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import axios from 'axios'

const nome = ref('')
const cargaHoraria = ref(0)
const quantidadeSemestres = ref(0)
const justificativa = ref('')
const impactoSocial = ref('')
const mensagem = ref('')

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
    // limpa campos
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
      return
    }

    const resposta = await axios.post(
      'http://127.0.0.1:8000/api/propostas',
      {
        nome: nome.value,
        carga_horaria_total: cargaHoraria.value,
        quantidade_semestres: quantidadeSemestres.value,
        justificativa: justificativa.value,
        impacto_social: impactoSocial.value,
        disciplinas: disciplinas.value, // importante!
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )

    mensagem.value = 'Proposta enviada com sucesso!'
  } catch (erro) {
    console.error(erro)
    mensagem.value = 'Erro ao enviar proposta.'
  }
}
</script>
