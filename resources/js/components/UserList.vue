<template>
  <div>
    <div class="list-header">
      <h3>Usuários Cadastrados ({{ pagination.total || 0 }})</h3>
      <span class="api-badge">API v1</span>
    </div>

    <div v-if="loading" class="loading-message">Carregando...</div>
    <div v-if="error"   class="error-message">{{ error }}</div>

    <div v-if="users.length" class="user-cards-container">
      <div
        v-for="user in users"
        :key="user.id"
        class="user-card"
        @click="selectUser(user.id)"
      >
        <div class="card-header">
          <div class="user-info">
            <div class="user-name">{{ user.name }}</div>
            <div class="user-email">{{ user.email }}</div>
          </div>
          <span class="id-badge">ID: {{ user.id }}</span>
        </div>

        <div class="address-section">
          <div class="address-icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M5.05 3.05a7 7 0 119.9 9.9l-4.95 4.95a.7.7 0 01-.99 0L5.05 12.95a7 7 0 010-9.9zm1.414 1.414a5.5 5.5 0 107.778 7.778 5.5 5.5 0 00-7.778-7.778zM10 8a2 2 0 110 4 2 2 0 010-4z" />
            </svg>
          </div>
          <div class="address-details">
            <p>
              {{ user.street }}, {{ user.number || 'S/N' }}
              <span v-if="user.complement"> – {{ user.complement }}</span>
            </p>
            <p>{{ user.district }}, {{ user.city }} – {{ user.state }}</p>
            <p>CEP: {{ user.zipcode }}</p>
          </div>
        </div>

        <div class="card-footer">
          Cadastrado em: {{ formatDateTime(user.created_at) }}
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

const emit = defineEmits(['view-user']);

const users      = ref([]);
const pagination = ref({});
const loading    = ref(false);
const error      = ref('');

const selectUser = (id) => {
  emit('view-user', id);
};

const fetchUsers = async (page = 1) => {
  loading.value = true;
  error.value   = '';
  try {
    const { data } = await axios.get(`/api/v1/users?page=${page}`);
    users.value      = data.data.sort((a, b) => a.id - b.id);
    pagination.value = data;
  } catch {
    error.value = 'Falha ao carregar usuários.';
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  if (page > 0 && page <= pagination.value.last_page) {
    fetchUsers(page);
  }
};

const formatDateTime = (dt) =>
  new Date(dt).toLocaleString('pt-BR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });

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
  color: #334155;
}
.api-badge {
  background: #eef2ff;
  color: #246587;
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
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.user-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 1.5rem;
  transition: box-shadow .3s, border-color .3s;
  cursor: pointer;
}
.user-card:hover {
  border-color: #246587;
  box-shadow:
    0 4px 6px -1px rgba(79,70,229,.08),
    0 2px 4px -1px rgba(124,58,237,.06);
}

.card-header {
  display: flex;
  justify-content: space-between;
  padding-bottom: 1rem;
  margin-bottom: 1rem;
}
.user-info {
  display: flex;
  flex-direction: column;
}
.user-name {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
}
.user-email {
  font-size: .9rem;
  color: #64748b;
  margin-top: .25rem;
}
.id-badge {
  background: #f1f5f9;
  color: #64748b;
  padding: .25rem .5rem;
  border-radius: 6px;
  font-size: .75rem;
  font-weight: 500;
  border: 1px solid #e2e8f0;
  height: 20px;
}

.address-section {
  display: flex;
  gap: .75rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
}
.address-icon-container {
  color: #4f46e5;
  margin-top: .125rem;
}
.address-details {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
}
.address-details p {
  margin: 0;
  color: #334155;
  line-height: 1.5;
}
.address-details p:first-child {
  font-weight: 500;
}

.card-footer {
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
  text-align: right;
  font-size: .85rem;
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
  padding: .5rem 1.25rem;
  background: linear-gradient(90deg,#246587,#7c3aed);
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background .3s;
}
.pagination-controls button:disabled {
  background: #e0e7ff;
  color: #a5b4fc;
  cursor: not-allowed;
}
.pagination-controls button:hover:not(:disabled) {
  background: linear-gradient(90deg,#7c3aed,#246587);
}
.pagination-controls span {
  color: #475569;
  font-weight: 500;
}
</style>
