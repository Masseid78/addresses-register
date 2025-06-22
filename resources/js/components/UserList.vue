<template>
  <div>
    <div class="list-header">
      <h3 class="flex items-center">
       
        Usuários Cadastrados ({{ pagination.total || 0 }})
      </h3>
      <span class="api-badge">API v1</span>
    </div>

    <div v-if="loading" class="loading-message">Carregando...</div>
    <div v-if="error" class="error-message">{{ error }}</div>

    <div v-if="users.length" class="user-cards-container">
      <div v-for="user in users" :key="user.id" class="user-card">
        <div class="card-header">
          <div class="user-info">
            <div class="user-name">{{ user.name }}</div>
            <div class="user-email">
              <span>{{ user.email }}</span>
            </div>
          </div>
          <span class="id-badge">ID: {{ user.id }}</span>
        </div>

        <div class="address-section">
          <div class="address-icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                    d="M9.69 18.933l.003.001C9.89 19.02 10 19
                       10 19s.11.02.308-.066l.002-.001.006-.003.018-.008
                       a5.741 5.741 0 00.281-.14c.186-.1.4-.27.662-.477
                       .352-.275.755-.636 1.198-1.071 1.64-1.64 3.005-3.824
                       3.005-6.227 0-3.9-3.134-7-7-7s-7 3.1-7
                       7c0 2.403 1.365 4.587 3.005 6.227.443.435.846.796
                       1.198 1.071.262.207.476.377.662.477a5.741 5.741 0
                       00.281.14l.018.008.006.003zM10 11.75a2.75 2.75 0
                       100-5.5 2.75 2.75 0 000 5.5z"
                    clip-rule="evenodd" />
            </svg>
          </div>
          <div class="address-details">
            <p>{{ user.street }}, {{ user.number || 'S/N' }}<span v-if="user.complement"> – {{ user.complement }}</span></p>
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
      <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url">Anterior</button>
      <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
      <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url">Próxima</button>
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
    // 1) ordena por ID ascendente
    users.value = response.data.data.sort((a, b) => a.id - b.id);
    pagination.value = response.data;
  } catch (err) {
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

const formatDateTime = (dt) => {
  return new Date(dt).toLocaleString('pt-BR', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
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
.list-header h3 { font-size:1.25rem; font-weight:600; color:#334155; }
.api-badge {
  background:#eef2ff; color:#246587;
  padding:0.25rem .75rem; border-radius:9999px;
  font-size:0.8rem; font-weight:500;
}
.loading-message, .error-message, .empty-state {
  text-align:center; padding:2rem; color:#64748b; font-size:1rem;
}
.error-message { color:#ef4444; }

.user-cards-container {
  display:flex; flex-direction:column; gap:1.5rem;
}

.user-card {
  background:#fff; border:1px solid #e2e8f0;
  border-radius:12px; padding:1.5rem;
  transition:box-shadow .3s, border-color .3s;
}
.user-card:hover {
  border-color:#246587;
  box-shadow:0 4px 6px -1px rgba(79,70,229,.08),
             0 2px 4px -1px rgba(124,58,237,.06);
}

.card-header {
  display:flex; justify-content:space-between;
  padding-bottom:1rem; margin-bottom:1rem;
}
.user-info { display:flex; flex-direction:column; }
.user-name { font-size:1.25rem; font-weight:600; color:#1e293b; }
.user-email {
  display:flex; align-items:center; gap:.3rem;
  font-size:.9rem; color:#64748b; margin-top:.25rem;
}
.id-badge {
  background:#f1f5f9; color:#64748b;
  padding:.25rem .5rem; border-radius:6px;
  font-size:.75rem; font-weight:500;
  border:1px solid #e2e8f0;
  height: 20px;
}

/* 2) faz o endereço em colunas lado-a-lado */
.address-section {
  display:flex; gap:.75rem;
  background:#f8fafc; border:1px solid #e2e8f0;
  border-radius:8px; padding:1rem; margin-bottom:1rem;
}
.address-icon-container {
  color:#4f46e5; margin-top:.125rem;
}
.address-details {
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
}
.address-details p {
  margin:0; color:#334155; line-height:1.5;
}
.address-details p:first-child {
  font-weight:500;
}

.card-footer {
  padding-top:1rem; border-top:1px solid #f1f5f9;
  text-align:right; font-size:.85rem; color:#64748b;
}

.pagination-controls {
  display:flex; justify-content:space-between;
  align-items:center; margin-top:2rem; padding-top:1rem;
  border-top:1px solid #e2e8f0;
}
.pagination-controls button {
  padding:.5rem 1.25rem;
  background:linear-gradient(90deg,#246587,#7c3aed);
  color:#fff; border:none; border-radius:6px;
  cursor:pointer; font-weight:500; transition:background .3s;
}
.pagination-controls button:disabled {
  background:#e0e7ff; color:#a5b4fc; cursor:not-allowed;
}
.pagination-controls button:hover:not(:disabled) {
  background:linear-gradient(90deg,#7c3aed,#246587);
}
.pagination-controls span {
  color:#475569; font-weight:500;
}
</style>
