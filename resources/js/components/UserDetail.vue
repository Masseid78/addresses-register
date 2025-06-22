<template>
    <div class="user-detail-container">
      <div v-if="loading" class="loading-message">Carregando detalhes...</div>
      <div v-if="error"   class="error-message">{{ error }}</div>
  
      <div v-if="user" class="user-content">
        <div class="detail-header">
          <h3>Detalhes de {{ user.name }}</h3>
          <button @click="$emit('close')" class="back-button">
            ← Voltar para a lista
          </button>
        </div>
  
        <div class="detail-grid">
          <div class="detail-item"><strong>ID:</strong> {{ user.id }}</div>
          <div class="detail-item"><strong>Nome:</strong> {{ user.name }}</div>
          <div class="detail-item"><strong>E-mail:</strong> {{ user.email }}</div>
          <div class="detail-item"><strong>CEP:</strong> {{ user.zipcode }}</div>
          <div class="detail-item"><strong>Rua:</strong> {{ user.street }}</div>
          <div class="detail-item"><strong>Número:</strong> {{ user.number || 'S/N' }}</div>
          <div class="detail-item"><strong>Bairro:</strong> {{ user.district }}</div>
          <div class="detail-item"><strong>Cidade:</strong> {{ user.city }}</div>
          <div class="detail-item"><strong>Estado:</strong> {{ user.state }}</div>
          <div class="detail-item span-2">
            <strong>Cadastrado em:</strong>
            {{ new Date(user.created_at).toLocaleString('pt-BR') }}
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import axios from 'axios';
  
  const props = defineProps({
    userId: { type: Number, required: true }
  });
  const emit = defineEmits(['close']);
  
  const user    = ref(null);
  const loading = ref(false);
  const error   = ref('');
  
  const fetchUser = async () => {
    loading.value = true;
    error.value   = '';
    try {
      const { data } = await axios.get(`/api/v1/users/${props.userId}`);
      user.value = data;
    } catch {
      error.value = 'Falha ao carregar detalhes.';
    } finally {
      loading.value = false;
    }
  };
  
  // Sempre que mudar o ID, buscar de novo (immediate já dispara a 1ª vez)
  watch(() => props.userId, fetchUser, { immediate: true });
  </script>
  
  <style scoped>
  .user-detail-container {
    padding: 1.5rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
  }
  
  .loading-message,
  .error-message {
    text-align: center;
    padding: 2rem;
    font-size: 1rem;
    color: #64748b;
  }
  .error-message {
    color: #ef4444;
  }
  
  .detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 0.75rem;
  }
  .detail-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #4f46e5;
  }
  .back-button {
    background: none;
    border: 1px solid #e2e8f0;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    color: #475569;
    font-weight: 500;
    transition: background-color 0.2s, color 0.2s;
  }
  .back-button:hover {
    background-color: #f1f5f9;
  }
  
  .detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    background-color: #f8fafc;
    padding: 1.5rem;
    border-radius: 6px;
  }
  .detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  .detail-item strong {
    font-weight: 500;
    color: #334155;
  }
  .detail-item span {
    color: #475569;
  }
  .detail-item.span-2 {
    grid-column: span 2;
  }
  
  @media (max-width: 640px) {
    .detail-grid {
      grid-template-columns: 1fr;
    }
    .detail-item.span-2 {
      grid-column: span 1;
    }
    .detail-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
  }
  </style>
  