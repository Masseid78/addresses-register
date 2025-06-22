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
  <svg xmlns="http://www.w3.org/2000/svg"
       fill="currentColor"
       viewBox="0 0 20 20">
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

        <!-- Abas customizadas -->
        <div class="tab-group">
          <button
            :class="['tab-btn', { active: activeTab === 'list' }]"
            @click="activeTab = 'list'"
          >
            <span class="tab-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25v-2.25A2.25 2.25 0 016.75 15.75h10.5a2.25 2.25 0 012.25 2.25v2.25" />
              </svg>
            </span>
            Lista de Usuários
          </button>
          <button
            :class="['tab-btn', { active: activeTab === 'form' }]"
            @click="activeTab = 'form'"
          >
            <span class="tab-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </span>
            Cadastrar Usuário
          </button>
        </div>

        <div class="tab-content">
          <UserList v-if="activeTab === 'list'" ref="userList" />
          <UserForm  v-if="activeTab === 'form'" @user-created="handleUserCreated" />
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
const userList  = ref(null);

const handleUserCreated = () => {
  activeTab.value = 'list';
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
  color: #38a9af;
}

.app-header p {
  font-size: 1.1rem;
  color: #64748b;
}

.content-wrapper {
  max-width: 900px;
  margin: 0 auto;
}

.users-management-panel {
  background-color: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08),
              0 4px 6px  -2px rgba(0,0,0,0.03);
  overflow: hidden;
}

.panel-header {
  display: flex;
  align-items: center;
  padding: 1.5rem;
  background: linear-gradient(90deg, #46e5dd 0%, #246587 100%);
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

/* Abas customizadas */
.tab-group {
  display: flex;
  border-bottom: 2px solid #e2e8f0;
  margin: 0 1rem 0 1rem;
}

.tab-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: none;
  color: #64748b;
  border: none;
  padding: 1rem 2rem 0.75rem;
  font-size: 1.1rem;
  font-weight: 500;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  transition: color 0.2s, border-bottom 0.2s;
  margin-bottom: -2px;
}

.tab-btn .tab-icon svg {
  width: 1.25rem;
  height: 1.25rem;
  color: #246587;
}

.tab-btn.active {
  color: #246587;
  font-weight: 700;
  border-bottom: 2.5px solid #246587;
}

.tab-btn.active .tab-icon svg {
  color: #246587;
}

.tab-btn:not(.active):hover {
  color: #246587;
}

.tab-content {
  padding: 1.5rem;
}
</style>
