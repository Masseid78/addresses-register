<template>
  <div class="main-container">
    <!-- cabeçalho geral -->
    <header class="app-header">
      <h1>Sistema de Endereços</h1>
      <p>Gerencie usuários e seus endereços com integração automática do ViaCEP</p>
    </header>

    <div class="content-wrapper">
      <div class="users-management-panel">

        <!-- panel header com degradê e ícone -->
        <div class="panel-header">
          <div class="panel-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372
                       9.337 9.337 0 004.121-2.305c.394-.442.723-1.047.973-1.748
                       l-.082.04c-.09.042-.18.083-.27.124a18.3 18.3 0 01-6.142 2.924
                       .5.5 0 01-.564-.328V5.084a.5.5 0 01.564-.473
                       c2.25.52 4.256 1.63 5.863 3.064.522.478.96 1.012 1.31 1.587
                       l-.082-.04c-.09-.043-.18-.084-.27-.125a18.3 18.3 0 01-6.142-2.924
                       .5.5 0 01-.564.328v13.078a.5.5 0 01.564.473z" />
            </svg>
          </div>
          <div>
            <h2>Gerenciamento de Usuários</h2>
            <p>API Laravel versionada com integração ViaCEP</p>
          </div>
        </div>

        <!-- abas pill-shape -->
        <div class="tabs">
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

        <!-- conteúdo das abas -->
        <div class="tab-content">
          <UserList v-if="activeTab === 'list'" ref="userList" />
          <UserForm  v-else               @user-created="handleUserCreated" />
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import UserForm from '../components/UserForm.vue';
import UserList from '../components/UserList.vue';

const activeTab = ref('list');
const userList  = ref(null);

const handleUserCreated = () => {
  activeTab.value = 'list';
  setTimeout(() => userList.value?.fetchUsers(), 100);
};
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

/* cabeçalho do painel com degradê */
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

/* abas pill-shape */
.tabs {
  display: flex;
  background-color: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
  margin: 1rem 0;
}
.tabs button {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: none;
  color: #475569;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s, color 0.2s;
}
.tabs button:hover:not(.active) {
  background-color: #eef2ff;
}
.tabs button.active {
  background-color: #ffffff;
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
</style>
