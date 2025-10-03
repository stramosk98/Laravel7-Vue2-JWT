<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">{{ isEdit ? 'Editar Pedido' : 'Novo Pedido' }}</h4>
            </div>
            <div class="card-body">
                <div v-if="loadingScreen" class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <form v-else @submit.prevent="handleSubmit">
                    <div class="form-group">
                        <label for="client_id">Cliente <span class="text-danger">*</span></label>
                        <select 
                            id="client_id" 
                            v-model="form.client_id" 
                            class="form-control"
                            :class="{ 'is-invalid': errors.client_id }"
                            required
                            :disabled="loading"
                        >
                            <option value="">Selecione um cliente</option>
                            <option v-for="client in clients" :key="client.id" :value="client.id">
                                {{ client.name }} - {{ client.city }}/{{ client.state }}
                            </option>
                        </select>
                        <div v-if="errors.client_id" class="invalid-feedback">
                            {{ errors.client_id[0] }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Endereço</label>
                        <input 
                            type="text" 
                            id="address" 
                            v-model="form.address" 
                            class="form-control"
                            :class="{ 'is-invalid': errors.address }"
                            placeholder="Digite o endereço de entrega"
                            :disabled="loading"
                        >
                    </div>
                    <div class="mb-3">
                        <small class="form-text text-muted">
                            Obrigatório apenas para pedidos em trânsito ou entregues (por motivos de teste)
                        </small>
                        <div v-if="errors.address" class="invalid-feedback">
                            {{ errors.address[0] }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select 
                            id="status" 
                            v-model="form.status" 
                            class="form-control"
                            :class="{ 'is-invalid': errors.status }"
                            required
                            :disabled="loading"
                        >
                            <option value="">Selecione o status</option>
                            <option :value="1">Pendente</option>
                            <option :value="2">Em Trânsito</option>
                            <option :value="3">Entregue</option>
                        </select>
                        <div v-if="errors.status" class="invalid-feedback">
                            {{ errors.status[0] }}
                        </div>
                    </div>

                    <div v-if="error" class="alert alert-danger">
                        {{ error }}
                    </div>

                    <div v-if="message" class="alert alert-success">
                        {{ message }}
                    </div>

                    <div class="d-flex justify-content-end">
                        <button 
                            type="button" 
                            class="btn btn-secondary mr-2"
                            @click="goBack"
                            :disabled="loading"
                        >
                            Voltar
                        </button>
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            :disabled="loading"
                        >
                            <span v-if="loading" class="spinner-border spinner-border-sm mr-2"></span>
                            {{ loading ? 'Salvando...' : 'Salvar' }}
                        </button>
                    </div>
                    <div v-if="globalErrors.length" class="alert alert-danger mt-3">
                        <ul><li v-for="(e,i) in globalErrors" :key="i">{{ e }}</li></ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AddOrUpdateOrder',
  data() {
    return {
      form: {
        client_id: '',
        address: '',
        status: ''
      },
      clients: [],
      loading: false,
      loadingScreen: false,
      error: '',
      message: '',
      errors: {},
      isEdit: false,
      orderId: null,
      globalErrors: []
    }
  },
  async created() {
    this.loadingScreen = true;
    await this.fetchClients();
    
    if (this.$route.params.id) {
      this.isEdit = true;
      this.orderId = this.$route.params.id;
      await this.fetchOrder();
    }
    this.loadingScreen = false;
  },
  methods: {
    async fetchClients() {
      try {
        const response = await axios.get('/clients');
        this.clients = response.data;
      } catch (error) {
        this.error = 'Erro ao carregar clientes';
        console.error(error);
      }
    },
    async fetchOrder() {
      this.loading = true;
      try {
        const response = await axios.get(`/orders/${this.orderId}`);
        this.form = {
          client_id: response.data.client_id,
          address: response.data.address || '',
          status: response.data.status
        };
      } catch (error) {
        this.error = 'Erro ao carregar pedido';
        console.error(error);
      }
      this.loading = false;
    },
    async handleSubmit() {
      this.loading = true;
      this.error = '';
      this.message = '';
      this.errors = {};
      this.globalErrors = [];

      try {
        let response;
        if (this.isEdit) {
          response = await axios.put(`/orders/${this.orderId}`, this.form);
        } else {
          response = await axios.post('/orders', this.form);
        }
        this.message = 'Pedido ' + (this.isEdit ? 'atualizado' : 'criado') + ' com sucesso!';

        this.$router.push('/orders');
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.errors = err.response.data.errors || {};
          this.globalErrors = Object.values(this.errors).flat();
        }
      }

      this.loading = false;
    },
    goBack() {
      this.$router.push('/orders');
    }
  }
}
</script>

