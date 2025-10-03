<template>
  <div>
    <div v-if="show" class="modal-backdrop fade show" @click="close"></div>
    <div :class="['filter-modal bg-white shadow-lg d-flex flex-column', { 'show': show }]">
      <div class="modal-header border-bottom">
        <h5 class="modal-title">Filtrar Pedidos</h5>
        <button type="button" class="close" @click="close">
          <span>&times;</span>
        </button>
      </div>
      
      <div class="modal-body flex-fill overflow-auto">
        <div class="form-group">
          <label for="client">Cliente</label>
          <select 
            id="client" 
            v-model="filters.client" 
            class="form-control"
          >
            <option value="">Todos os clientes</option>
            <option v-for="client in clients" :key="client.id" :value="client.id">
              {{ client.name }}
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="status">Status</label>
          <select 
            id="status" 
            v-model="filters.status" 
            class="form-control"
          >
            <option value="">Todos os status</option>
            <option value="1">Pendente</option>
            <option value="2">Em Trânsito</option>
            <option value="3">Entregue</option>
          </select>
        </div>
      </div>

      <div class="modal-footer border-top d-flex justify-content-end">
        <button type="button" class="btn btn-outline-secondary mr-2" @click="clearFilters">
          Limpar
        </button>
        <button type="button" class="btn btn-outline-primary" @click="applyFilters">
          Aplicar Filtros
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FilterComponent',
  props: {
    show: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      clients: [],
      filters: {
        client: '',
        status: ''
      }
    }
  },
  async created() {
    await this.fetchClients();
  },
  methods: {
    async fetchClients() {
      try {
        const response = await axios.get('/clients');
        this.clients = response.data;
      } catch (error) {
        console.error('Erro ao carregar clientes:', error);
      }
    },
    applyFilters() {
      this.$emit('apply', this.filters);
      this.close();
    },
    clearFilters() {
      this.filters = {
        client: '',
        status: ''
      };
      this.$emit('apply', this.filters);
      this.close();
    },
    close() {
      this.$emit('close');
    }
  }
}
</script>

<style scoped>
.filter-modal {
  position: fixed;
  top: 0;
  right: -30%;
  width: 30%;
  height: 100%;
  z-index: 1050;
  transition: right 0.3s ease-in-out;
}

.filter-modal.show {
  right: 0;
}

@media (max-width: 768px) {
  .filter-modal {
    width: 80%;
    right: -80%;
  }
}
</style>

