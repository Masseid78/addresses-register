<template>
  <div class="form-container">
    <div class="form-header">
      <h3>Cadastrar Novo Usuário</h3>
      <p>Preencha os dados abaixo. O endereço será preenchido automaticamente pelo CEP.</p>
    </div>
    <form @submit.prevent="submitForm">
      <div class="form-grid">
        <div class="form-group span-2">
          <label for="name">Nome Completo *</label>
          <input type="text" id="name" v-model="form.name" placeholder="Digite seu nome completo" required>
        </div>
        <div class="form-group span-2">
          <label for="email">E-mail *</label>
          <input type="email" id="email" v-model="form.email" placeholder="Digite seu e-mail" required>
        </div>
        <div class="form-group">
          <label for="zipcode">CEP *</label>
          <input type="text" id="zipcode" v-model="form.zipcode" @input="formatZipcode" maxlength="9" placeholder="00000-000" required>
        </div>
        <div class="form-group">
          <label for="number">Número *</label>
          <input type="text" id="number" v-model="form.number" placeholder="123" required>
        </div>
        <div class="form-group span-2">
          <label for="complement">Complemento</label>
          <input type="text" id="complement" v-model="form.complement" placeholder="Apto 101, Bloco A...">
        </div>
      </div>
      <button type="submit" :disabled="loading">
        <span v-if="loading">Cadastrando...</span>
        <span v-else>Cadastrar Usuário</span>
      </button>
      <p v-if="error" class="error-message">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const form = ref({
  name: '',
  email: '',
  zipcode: '',
  number: '',
  complement: '',
});
const loading = ref(false);
const error = ref('');

const emit = defineEmits(['user-created']);

const formatZipcode = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  if (value.length > 5) {
    value = value.slice(0, 5) + '-' + value.slice(5, 8);
  }
  form.value.zipcode = value;
};

const submitForm = async () => {
  loading.value = true;
  error.value = '';
  try {
    await axios.post('/api/v1/users', form.value);
    emit('user-created');
    form.value = { name: '', email: '', zipcode: '', number: '', complement: '' };
  } catch (err) {
    error.value = err.response?.data?.message || 'An error occurred.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.form-container {
  max-width: 700px;
  margin: 0 auto;
}

.form-header {
  text-align: center;
  margin-bottom: 2rem;
}

.form-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
}

.form-header p {
  color: #64748b;
  font-size: 1rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.span-2 {
  grid-column: span 2 / span 2;
}

.form-group label {
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #334155;
}

.form-group input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  background-color: #ffffff;
  transition: border-color 0.3s, box-shadow 0.3s;
  font-size: 1rem;
  box-sizing: border-box;
}

.form-group input::placeholder {
  color: #94a3b8;
}

.form-group input:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

button {
  width: 100%;
  padding: 1rem;
  background-color: #4f46e5;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  transition: background-color 0.3s;
}

button:disabled {
  background-color: #a5b4fc;
  cursor: not-allowed;
}

button:hover:not(:disabled) {
  background-color: #4338ca;
}

.error-message {
  color: #ef4444;
  margin-top: 1rem;
  text-align: center;
  font-weight: 500;
}

@media (max-width: 640px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .form-group.span-2 {
        grid-column: span 1 / span 1;
    }
}
</style> 