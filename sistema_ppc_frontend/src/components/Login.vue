<template>
  <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg border-0 p-4" style="width: 100%; max-width: 420px; border-radius: 1rem;">
      <!-- Logo da ASTTIC -->
      <div class="text-center mb-4">
        <img
          src="/logo_asttic.png"
          alt="Logo da ASTTIC"
          class="mb-2"
          style="max-width: 100px; height: auto;"
        />
        <h3 class="mt-2">Bem-vindo</h3>
        <p class="text-muted">Faça login para continuar</p>
      </div>

      <!-- Formulário de Login -->
      <form @submit.prevent="fazerLogin">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            v-model="email"
            type="email"
            id="email"
            class="form-control"
            placeholder="seu@email.com"
            required
          />
        </div>

        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input
            v-model="senha"
            type="password"
            id="senha"
            class="form-control"
            placeholder="Digite sua senha"
            required
          />
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>

      <!-- Mensagem de erro ou status -->
      <p v-if="mensagem" class="mt-3 text-danger text-center fw-semibold">
        {{ mensagem }}
      </p>
    </div>
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
      router.push('/minhas-propostas')
    } else if (tipo === 'avaliador') {
      router.push('/avaliacoes')
    } else if (tipo === 'decisor') {
      router.push('/propostas-para-decisao') 
    } else {
      mensagem.value = 'Tipo de usuário não reconhecido.'
    }

  } catch (erro) {
    console.error(erro)
    mensagem.value = 'Erro ao fazer login.'
  }
}
</script>


