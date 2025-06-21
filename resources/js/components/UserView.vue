<template>
  <div class="main-container">
    <header class="app-header">
      <h1>Sistema de Endereços</h1>
      <p>Gerencie seus usuários e seus endereços com integração automática do ViaCEP</p>
    </header>

    <div class="content-wrapper">
      <div class="users-management-panel">
        <div class="panel-header">
          <div class="panel-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-2.305c.394-.442.723-1.047.973-1.748l-.082.04c-.09.042-.18.083-.27.124a18.3 18.3 0 01-6.142 2.924.5.5 0 01-.564-.328V5.084a.5.5 0 01.564-.473c2.25.52 4.256 1.63 5.863 3.064.522.478.96 1.012 1.31 1.587l-.082-.04c-.09-.043-.18-.084-.27-.125a18.3 18.3 0 01-6.142-2.924.5.5 0 01-.564.328v13.078a.5.5 0 01.564.473z" />
            </svg>
          </div>
          <div>
            <h2>Gerenciamento de Usuários</h2>
            <p>API Laravel versionada com integração ViaCEP</p>
          </div>
        </div>

        <div class="tabs">
          <button :class="{ active: activeTab === 'list' }" @click="activeTab = 'list'">
            Lista de Usuários
          </button>
          <button :class="{ active: activeTab === 'form' }" @click="activeTab = 'form'">
            Cadastrar Usuário
          </button>
        </div>

        <div class="tab-content">
          <UserList v-if="activeTab === 'list'" ref="userList" />
          <UserForm v-if="activeTab === 'form'" @user-created="handleUserCreated" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import UserForm from './UserForm.vue';
import UserList from './UserList.vue';

const activeTab = ref('list');
const userList = ref(null);

const handleUserCreated = () => {
  activeTab.value = 'list';
  // Adiciona um pequeno delay para garantir que a lista seja recarregada
  setTimeout(() => {
    userList.value?.fetchUsers();
  }, 100);
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
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.panel-header {
  display: flex;
  align-items: center;
  padding: 1.5rem;
  background: linear-gradient(90deg, #4f46e5, #7c3aed);
  color: white;
}

.panel-icon {
  margin-right: 1rem;
  background-color: rgba(255, 255, 255, 0.1);
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
  background-color: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.tabs button {
  padding: 1rem 1.5rem;
  font-size: 1rem;
  font-weight: 500;
  color: #475569;
  background-color: transparent;
  border: none;
  cursor: pointer;
  position: relative;
  transition: color 0.3s;
}

.tabs button:hover {
  color: #1e293b;
}

.tabs button.active {
  color: #4f46e5;
  font-weight: 600;
}

.tabs button.active::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 3px;
  background-color: #4f46e5;
}

.tab-content {
  padding: 1.5rem;
}
</style> 