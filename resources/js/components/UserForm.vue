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
          <input
            type="text"
            id="name"
            v-model="form.name"
            placeholder="Digite seu nome completo"
            required
          />
        </div>
        <div class="form-group span-2">
          <label for="email">E-mail *</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            placeholder="Digite seu e-mail"
            required
          />
        </div>
        <div class="form-group">
          <label for="zipcode">CEP *</label>
          <input
            type="text"
            id="zipcode"
            v-model="form.zipcode"
            @input="onCepInput"
            maxlength="9"
            placeholder="00000-000"
            required
          />
        </div>
        <div class="form-group">
          <label for="number">Número *</label>
          <input
            type="text"
            id="number"
            v-model="form.number"
            placeholder="123"
            required
          />
        </div>
        <div class="form-group span-2">
          <label for="street">Rua *</label>
          <input
            type="text"
            id="street"
            v-model="form.street"
            placeholder="Preenchido pelo CEP"
            required
          />
        </div>
        <div class="form-group">
          <label for="district">Bairro *</label>
          <input
            type="text"
            id="district"
            v-model="form.district"
            placeholder="Preenchido pelo CEP"
            required
          />
        </div>
        <div class="form-group">
          <label for="complement">Complemento</label>
          <input
            type="text"
            id="complement"
            v-model="form.complement"
            placeholder="Apto 101, Bloco A..."
          />
        </div>
      </div>

      <!-- Quadro de endereço preenchido automaticamente -->
      <div v-if="showAddressBox" class="address-box">
        <div v-if="addressLoading" class="address-loading">
          <svg class="spinner" viewBox="0 0 50 50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" />
          </svg>
          Buscando endereço...
        </div>
        <div v-else-if="addressError" class="address-error">{{ addressError }}</div>
        <div v-else class="address-auto">
          <div><strong>Cidade:</strong> {{ address.city }}</div>
          <div><strong>Estado:</strong> {{ address.state }}</div>
        </div>
      </div>

      <button type="submit" :disabled="loading || addressLoading">
        <span v-if="loading">
          <svg class="spinner" viewBox="0 0 50 50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" />
          </svg>
          Cadastrando...
        </span>
        <span v-else>Cadastrar Usuário</span>
      </button>
      <div v-if="toastError" class="toast-error">{{ toastError }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue';
import axios from 'axios';

const form = ref({
  name: '',
  email: '',
  zipcode: '',
  number: '',
  complement: '',
  street: '',
  district: '',
  city: '',
  state: ''
});
const loading        = ref(false);
const toastError     = ref('');
const address        = ref({ street:'', district:'', city:'', state:'' });
const addressLoading = ref(false);
const addressError   = ref('');
const showAddressBox = ref(false);

const emit = defineEmits(['user-created']);

const formatZip = v => {
  let d = v.replace(/\D/g,'');
  if (d.length>5) d = d.slice(0,5)+'-'+d.slice(5,8);
  return d;
};

async function onCepInput(e) {
  form.value.zipcode = formatZip(e.target.value);
  showAddressBox.value = false;
  addressError.value = '';
  address.value = {street:'',district:'',city:'',state:''};

  if (form.value.zipcode.length === 9) {
    addressLoading.value = true;
    showAddressBox.value = true;
    try {
      const { data } = await axios.get(`https://viacep.com.br/ws/${form.value.zipcode.replace('-','')}/json/`);
      if (data.erro) {
        addressError.value = 'CEP não encontrado.';
      } else {
        address.value = {
          street:   data.logradouro,
          district: data.bairro,
          city:     data.localidade,
          state:    data.uf
        };
        form.value.street   = data.logradouro;
        form.value.district = data.bairro;
        form.value.city     = data.localidade;
        form.value.state    = data.uf;
      }
    } catch {
      addressError.value = 'Erro ao buscar endereço.';
    } finally {
      addressLoading.value = false;
    }
  }
}

async function submitForm() {
  loading.value = true;
  toastError.value = '';
  try {
    await axios.post('/api/v1/users', form.value);
    
    // Sucesso, agora vamos parar o loading e avisar o componente pai
    loading.value = false;
    await nextTick(); // Garante que a UI (botão) atualize ANTES de trocar de aba
    emit('user-created');

  } catch (err) {
    loading.value = false; // Para o loading em caso de erro também
    toastError.value = err.response?.data?.message || 'Erro ao cadastrar.';
    setTimeout(() => { toastError.value = ''; }, 4000);
  }
}
</script>

<style scoped>
.form-container {
  max-width: 700px;
  margin: 2rem auto;
  padding: 2rem;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.06);
}
.form-header {
  text-align: center;
  margin-bottom: 2rem;
}
.form-header h3 {
  color: #246587;
  font-size: 1.25rem;
  font-weight: 600;
}
.form-header p {
  color: #64748b;
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
  grid-column: span 2;
}
.form-group label {
  margin-bottom: .5rem;
  color: #334155;
  font-weight: 500;
}
.form-group input {
  padding: .75rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  background: #f8fafc;
  transition: border-color .3s, box-shadow .3s;
}
.form-group input:focus {
  border-color: #246587;
  box-shadow: 0 0 0 3px rgba(36, 101, 135, 0.1);
  outline: none;
}
.address-box {
  background: #f8fafc;
  border: 1.5px solid #246587;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1.5rem;
}
.address-loading {
  display: flex;
  align-items: center;
  gap: .5rem;
  color: #246587;
  font-weight: 500;
}
.address-error {
  color: #ef4444;
  font-weight: 500;
}
.address-auto > div {
  margin-bottom: .5rem;
}
.spinner {
  width: 1.2em;
  height: 1.2em;
  margin-right: 0.5em;
  animation: spin 1s linear infinite;
}
.spinner .path {
  stroke: #246587; /* Usando a cor do tema para consistência */
  stroke-linecap: round;
}
@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}
button {
  width: 100%;
  padding: 1rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: auto;
  flex: 0 0 auto;
  background: linear-gradient(90deg, #246587, #6eb8e7);
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.3s;
}
button:disabled {
  background: #e0e7ff;
  color: #a5b4fc;
  cursor: not-allowed;
}
button:hover:not(:disabled) {
  background: linear-gradient(90deg, #6eb8e7, #246587);
}
.toast-error {
  margin-top: 1rem;
  padding: .75rem 1rem;
  background: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fca5a5;
  border-radius: 6px;
  text-align: center;
  font-weight: 600;
}
@media(max-width:640px) {
  .form-container {
    padding: 1rem;
  }
  .form-grid {
    grid-template-columns: 1fr;
  }
  .form-group.span-2 {
    grid-column: span 1;
  }
}
</style>
