<template>
  <div>
    <div class="list-header">
      <h3>Usuários Cadastrados ({{ pagination.total || 0 }})</h3>
      <span class="api-badge">API v1</span>
    </div>

    <div v-if="loading" class="loading-message">Carregando...</div>
    <div v-if="error" class="error-message">{{ error }}</div>

    <div v-if="users.length" class="user-cards-container">
      <div v-for="user in users" :key="user.id" class="user-card">
        <div class="card-header">
          <span class="id-badge">ID: {{ user.id }}</span>
        </div>
        <div class="user-main-row">
          <div class="user-main-item"><strong>Nome:</strong> {{ user.name }}</div>
          <div class="user-main-item"><strong>Email:</strong> {{ user.email }}</div>
        </div>
        <div class="user-address-row">
          <span class="address-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.1.4-.27.662-.477.352-.275.755-.636 1.198-1.071 1.64-1.64 3.005-3.824 3.005-6.227 0-3.9-3.134-7-7-7s-7 3.1-7 7c0 2.403 1.365 4.587 3.005 6.227.443.435.846.796 1.198 1.071.262.207.476.377.662.477a5.741 5.741 0 00.281.14l.018.008.006.003zM10 11.75a2.75 2.75 0 100-5.5 2.75 2.75 0 000 5.5z" clip-rule="evenodd" />
            </svg>
          </span>
          <span><strong>Endereço:</strong> {{ user.street }}, {{ user.number || 'S/N' }} {{ user.complement ? '- ' + user.complement : '' }} - {{ user.district }}, {{ user.city }} - {{ user.state }}, CEP: {{ user.zipcode }}</span>
        </div>
        <div class="card-footer">
          Cadastrado em: {{ new Date(user.created_at).toLocaleDateString('pt-BR') }}
        </div>
      </div>
    </div>
    
    <div v-if="!loading && !users.length && !error" class="empty-state">
      Nenhum usuário cadastrado ainda.
    </div>

    <div v-if="pagination.last_page > 1" class="pagination-controls">
      <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url">
        Anterior
      </button>
      <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
      <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url">
        Próxima
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const users = ref([]);
const pagination = ref({});
const loading = ref(false);
const error = ref('');

const fetchUsers = async (page = 1) => {
  loading.value = true;
  error.value = '';
  try {
    const response = await axios.get(`/api/v1/users?page=${page}`);
    users.value = response.data.data;
    pagination.value = response.data;
  } catch (err) {
    error.value = 'Failed to load users.';
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  if (page > 0 && page <= pagination.value.last_page) {
    fetchUsers(page);
  }
};

const formatAddress = (user) => {
  return `${user.street}, ${user.number || 'N/A'} - ${user.district}, ${user.city} - ${user.state}, ${user.zipcode}`;
};

onMounted(() => fetchUsers());

defineExpose({ fetchUsers });
</script>

<style scoped>
.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.list-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #4f46e5;
}

.api-badge {
  background-color: #e0e7ff;
  color: #4338ca;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.8rem;
  font-weight: 500;
}

.loading-message, .error-message, .empty-state {
  text-align: center;
  padding: 2rem;
  color: #64748b;
  font-size: 1rem;
}

.error-message {
  color: #ef4444;
}

.user-cards-container {
  display: grid;
  gap: 1.5rem;
}

.user-card {
  background-color: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 2.5rem;
  transition: box-shadow 0.3s, border-color 0.3s;
}

.user-card:hover {
  border-color: #7c3aed;
  box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.08), 0 2px 4px -1px rgba(124, 58, 237, 0.06);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  border-bottom: 1px solid #f1f5f9;
  padding-bottom: 1rem;
  margin-bottom: 1rem;
}

.user-name {
  font-size: 1.125rem;
  font-weight: 600;
  color: #4f46e5;
}

.user-email {
  font-size: 0.9rem;
  color: #64748b;
}

.id-badge {
  background-color: #e0e7ff;
  color: #4338ca;
  padding: 0.2rem 0.6rem;
  border-radius: 6px;
  font-size: 0.8rem;
}

.user-main-row {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  align-items: center;
  margin-bottom: 0.5rem;
}
.user-main-item {
  color: #334155;
  font-size: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.user-address-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #334155;
  font-size: 1rem;
  margin-bottom: 1rem;
}
.address-icon {
  color: #7c3aed;
  margin-right: 0.3rem;
  flex-shrink: 0;
  display: flex;
  align-items: center;
}
.address-icon svg {
  width: 1.1rem;
  height: 1.1rem;
}
.card-footer {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
  text-align: right;
  font-size: 0.85rem;
  color: #64748b;
}

.pagination-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.pagination-controls button {
  padding: 0.5rem 1.25rem;
  background: linear-gradient(90deg, #4f46e5, #7c3aed);
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.3s;
}

.pagination-controls button:disabled {
  background: #e0e7ff;
  color: #a5b4fc;
  cursor: not-allowed;
}

.pagination-controls button:hover:not(:disabled) {
  background: linear-gradient(90deg, #7c3aed, #4f46e5);
}

.pagination-controls span {
    color: #475569;
    font-weight: 500;
}

@media (max-width: 900px) {
  .user-main-row {
    flex-direction: column;
    gap: 0.5rem;
    align-items: flex-start;
  }
  .user-address-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.2rem;
  }
}
</style> 