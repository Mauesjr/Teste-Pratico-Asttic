import { createApp } from 'vue'
import App from './App.vue'
import router from './router' // 👈 Importa o roteador

// import './style.css'

const app = createApp(App)

app.use(router) // 👈 Registra o roteador na aplicação
app.mount('#app')
