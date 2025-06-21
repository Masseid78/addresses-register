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
          <input type="text" id="zipcode" v-model="form.zipcode" @input="onCepInput" maxlength="9" placeholder="00000-000" required>
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

      <!-- Quadro de endereço preenchido automaticamente -->
      <div v-if="showAddressBox" class="address-box">
        <div v-if="addressLoading" class="address-loading">
          <svg class="spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"/></svg>
          Buscando endereço...
        </div>
        <div v-else-if="addressError" class="address-error">{{ addressError }}</div>
        <div v-else>
          <div><strong>Cidade:</strong> {{ address.city }}</div>
          <div><strong>Estado:</strong> {{ address.state }}</div>
        </div>
      </div>

      <button type="submit" :disabled="loading || !address.city || !address.state">
        <span v-if="loading">
          <svg class="spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"/></svg>
          Cadastrando...
        </span>
        <span v-else>Cadastrar Usuário</span>
      </button>
      <div v-if="toastError" class="toast-error">{{ toastError }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
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
const toastError = ref('');

const emit = defineEmits(['user-created']);

// Endereço preenchido automaticamente
const address = ref({
  street: '',
  district: '',
  city: '',
  state: '',
});
const addressLoading = ref(false);
const addressError = ref('');
const showAddressBox = ref(false);

const formatZipcode = (value) => {
  let v = value.replace(/\D/g, '');
  if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
  return v;
};

const onCepInput = async (event) => {
  form.value.zipcode = formatZipcode(event.target.value);
  addressError.value = '';
  showAddressBox.value = false;
  address.value = { street: '', district: '', city: '', state: '' };
  if (form.value.zipcode.length === 9) {
    addressLoading.value = true;
    showAddressBox.value = true;
    try {
      const { data } = await axios.get(`https://viacep.com.br/ws/${form.value.zipcode.replace('-', '')}/json/`);
      if (data.erro) {
        addressError.value = 'CEP não encontrado.';
        address.value = { street: '', district: '', city: '', state: '' };
      } else {
        address.value = {
          street: data.logradouro,
          district: data.bairro,
          city: data.localidade,
          state: data.uf,
        };
        // Preencher no form para enviar ao backend
        form.value.street = data.logradouro;
        form.value.district = data.bairro;
        form.value.city = data.localidade;
        form.value.state = data.uf;
      }
    } catch (e) {
      addressError.value = 'Erro ao buscar endereço.';
      address.value = { street: '', district: '', city: '', state: '' };
    } finally {
      addressLoading.value = false;
    }
  }
};

const submitForm = async () => {
  loading.value = true;
  error.value = '';
  toastError.value = '';
  try {
    await axios.post('/api/v1/users', form.value);
    emit('user-created');
    form.value = { name: '', email: '', zipcode: '', number: '', complement: '' };
    address.value = { street: '', district: '', city: '', state: '' };
    showAddressBox.value = false;
  } catch (err) {
    toastError.value = err.response?.data?.message || 'Ocorreu um erro ao cadastrar.';
    setTimeout(() => { toastError.value = ''; }, 4000);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.form-container {
  max-width: 700px;
  margin: 0 auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.06);
  border: 1px solid #e2e8f0;
  padding: 2rem 2.5rem;
}

.form-header {
  text-align: center;
  margin-bottom: 2rem;
}

.form-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #4f46e5;
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
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  background-color: #f8fafc;
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
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
}

button {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(90deg, #4f46e5, #7c3aed);
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  transition: background 0.3s;
}

button:disabled {
  background: #e0e7ff;
  color: #a5b4fc;
  cursor: not-allowed;
}

button:hover:not(:disabled) {
  background: linear-gradient(90deg, #7c3aed, #4f46e5);
}

.error-message {
  color: #ef4444;
  margin-top: 1rem;
  text-align: center;
  font-weight: 500;
}

.address-box {
  background: #f8fafc;
  border: 1.5px solid #7c3aed;
  border-radius: 8px;
  padding: 1rem 1.5rem;
  margin-bottom: 1.5rem;
  margin-top: 0.5rem;
  color: #334155;
  font-size: 1rem;
}
.address-loading {
  color: #7c3aed;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.address-error {
  color: #ef4444;
  font-weight: 500;
}
.spinner {
  width: 1.2em;
  height: 1.2em;
  margin-right: 0.5em;
  animation: spin 1s linear infinite;
}
.spinner .path {
  stroke: #7c3aed;
  stroke-linecap: round;
}
@keyframes spin {
  100% { transform: rotate(360deg); }
}
.toast-error {
  background: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fca5a5;
  border-radius: 6px;
  padding: 0.75rem 1rem;
  margin-top: 1rem;
  text-align: center;
  font-weight: 600;
  font-size: 1rem;
  box-shadow: 0 2px 8px rgba(185,28,28,0.08);
}
@media (max-width: 640px) {
    .form-container {
        padding: 1rem 0.5rem;
    }
    .form-grid {
        grid-template-columns: 1fr;
    }
    .form-group.span-2 {
        grid-column: span 1 / span 1;
    }
}
</style> 