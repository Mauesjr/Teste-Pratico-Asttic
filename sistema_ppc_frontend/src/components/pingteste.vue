<template>
  <div>
    <button @click="testarApi">Testar API</button>
    <p v-if="resposta">{{ resposta }}</p>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import axios from 'axios'

const resposta = ref('')

async function testarApi() {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/ping')
    resposta.value = res.data.message
  } catch (error) {
    resposta.value = 'Erro ao conectar com API'
    console.error(error)
  }
}
</script>
