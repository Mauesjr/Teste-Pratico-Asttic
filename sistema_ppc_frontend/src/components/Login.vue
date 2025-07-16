<template>
  <div>
    <h2>Login</h2>

    <form @submit.prevent="fazerLogin">
      <div>
        <label>Email:</label>
        <input v-model="email" type="email" required />
      </div>

      <div>
        <label>Senha:</label>
        <input v-model="senha" type="password" required />
      </div>

      <button type="submit">Entrar</button>
    </form>

    <p v-if="mensagem">{{ mensagem }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const email = ref('')
const senha = ref('')
const mensagem = ref('')
const router = useRouter()

async function fazerLogin() {
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/login', {
      email: email.value.trim(),
      senha: senha.value,
    }, {
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    })

    // Salva token e tipo
    const token = res.data.token
    const tipo = res.data.usuario.tipo

    localStorage.setItem('token', token)
    localStorage.setItem('tipo', tipo)

    mensagem.value = 'Login realizado com sucesso!'

    // Redireciona conforme o tipo de usuário
    if (tipo === 'submissor') {
      router.push('/criar-proposta')
    } else if (tipo === 'avaliador') {
      router.push('/avaliacoes')
    } else if (tipo === 'decisor') {
      router.push('/decisao') // só se você quiser
    } else {
      mensagem.value = 'Tipo de usuário não reconhecido.'
    }

  } catch (erro) {
    console.error(erro)
    mensagem.value = 'Erro ao fazer login.'
  }
}
</script>


