<template>
  <div class="main-container">
    <header class="app-header">
      <h1>Sistema de Endereços</h1>
      <p>Gerencie usuários e seus endereços com integração automática do ViaCEP</p>
    </header>

    <div class="content-wrapper">
      <div class="users-management-panel">
        <div class="panel-header">
          <div class="panel-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M10 2a5 5 0 100 10A5 5 0 0010 2zM2 18a8 8 0 1116 0H2z"
                    clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h2>Gerenciamento de Usuários</h2>
            <p>API Laravel versionada com integração ViaCEP</p>
          </div>
        </div>

        <div class="tabs" v-if="!selectedUserId">
          <button
            :class="{ active: activeTab === 'list' }"
            @click="activeTab = 'list'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="tab-icon" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M10 4a3 3 0 100 6 3 3 0 000-6zm1 
                       8h-2a4 4 0 00-4 4v1h10v-1a4 4 0 00-4-4z" />
            </svg>
            Lista de Usuários
          </button>
          <button
            :class="{ active: activeTab === 'form' }"
            @click="activeTab = 'form'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="tab-icon" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 
                       2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 
                       110-2h3V6a1 1 0 011-1z" />
            </svg>
            Cadastrar Usuário
          </button>
        </div>

        <!-- conteúdo das abas ou detalhe -->
        <div class="tab-content">
          <!-- 1) Tela de detalhe -->
          <UserDetail
            v-if="selectedUserId"
            :user-id="selectedUserId"
            @close="selectedUserId = null"
          />

          <!-- 2) Lista ou Form -->
          <template v-else>
            <UserList
              v-if="activeTab === 'list'"
              ref="userList"
              @view-user="showUserDetail"
            />
            <UserForm
              v-else
              @user-created="onUserCreated"
            />
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import UserList   from '../components/UserList.vue';
import UserForm   from '../components/UserForm.vue';
import UserDetail from '../components/UserDetail.vue';

const activeTab      = ref('list');
const selectedUserId = ref(null);
const userList       = ref(null);

// Abre a tela de detalhes com o ID clicado
function showUserDetail(id) {
  selectedUserId.value = id;
}

// Depois de cadastrar, volta pra lista e recarrega
function onUserCreated() {
  activeTab.value = 'list';
  setTimeout(() => userList.value?.fetchUsers(), 100);
}
</script>

<style scoped>
.main-container {
  font-family: 'Inter', sans-serif;
  background-color: #f0f2f5;
  min-height: 100vh;
  padding: 2rem;
}

.app-header {
  text-align: center;
  margin-bottom: 2rem;
}
.app-header h1 {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1e293b;
}
.app-header p {
  font-size: 1.1rem;
  color: #475569;
}

.content-wrapper {
  max-width: 900px;
  margin: 0 auto;
}

.users-management-panel {
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow:
    0 10px 15px -3px rgba(0,0,0,0.1),
    0 4px 6px -2px rgba(0,0,0,0.05);
  overflow: hidden;
}

.panel-header {
  display: flex;
  align-items: center;
  padding: 1.5rem;
  background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
  color: white;
}
.panel-icon {
  margin-right: 1rem;
  background-color: rgba(255,255,255,0.1);
  border-radius: 50%;
  padding: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.panel-icon svg {
  width: 2rem;
  height: 2rem;
}
.panel-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
}
.panel-header p {
  font-size: 0.9rem;
  opacity: 0.9;
}
.tabs {
  display: flex;
  background-color: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
  margin: 1rem 0;
}
.tabs button {
  border: 1px solid #e2e8f0;
  background-color: #f1f5f9;
  border-radius: 0.5rem;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  color: #475569;
  font-weight: 500;
  cursor: pointer;
  transition: background-color .2s, color .2s;
  margin-right: 0.5rem;
}
.tabs button:last-child {
  margin-right: 0;
}
.tabs button.active {
  border: 1px solid #246587;
  background-color: #ffffff;
  border-radius: 0.5rem;
  color: #4f46e5;
  font-weight: 600;
}
.tab-icon {
  width: 1.25rem;
  height: 1.25rem;
  color: inherit;
}

.tab-content {
  padding: 1.5rem;
}
.main-container {
  background: linear-gradient(-45deg,
    #4f46e5, 
    #7c3aed, 
    #4f46e5,
    #7c3aed
  );
  background-size: 400% 400%;    
  animation: gradientBG 15s ease infinite; 
}

@keyframes gradientBG {
  0%   { background-position: 0%   50%; }
  50%  { background-position: 100% 50%; }
  100% { background-position: 0%   50%; }
}

</style>
