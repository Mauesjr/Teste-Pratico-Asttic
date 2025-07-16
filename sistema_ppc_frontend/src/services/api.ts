// src/services/api.ts
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // substitua com a URL real se necessário
  withCredentials: true // se for usar cookies com Laravel Sanctum
});

export default api;
