<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Pedidos</h4>
        <div class="d-flex justify-content-end">
            <button class="btn btn-outline-primary btn-sm mr-2" v-if="hasPermission(user, 'create_orders')" @click="createOrder">Novo Pedido</button>
            <button class="btn btn-outline-secondary btn-sm" @click="showFilterModal = true">Filtrar</button>
        </div>
      </div>
      <div class="card-body">
        <div v-if="message" :class="`alert alert-${message.type}`">
          {{ message.text }}
        </div>
        <div v-if="loading" class="text-center">
          <div class="spinner-border" role="status">
            <span class="sr-only">Carregando...</span>
          </div>
        </div>
        <div v-else-if="error" class="alert alert-danger">
          {{ error }}
        </div>
        <div v-else-if="orders.length > 0">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Endereço</th>
                <th>Status</th>
                <th>Data</th>
                <th class="align-middle text-center">Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(order, index) in orders" :key="order.id">
                <td>{{ index + 1 }}</td>
                <td>{{ order.client ? order.client.name : 'N/A' }}</td>
                <td>{{ order.address }}</td>
                <td>
                  <select 
                    v-model="order.status" 
                    :class="getStatusClass(order.status)" 
                    :disabled="!hasPermission(user, 'update_orders')" 
                    @change="updateStatus(order)"
                  >
                    <option :class="getStatusClass(1)" value="1">Pendente</option>
                    <option :class="getStatusClass(2)" value="2">Em trânsito</option>
                    <option :class="getStatusClass(3)" value="3">Entregue</option>
                  </select>
                </td>
                <td>{{ formatDate(order.created_at) }}</td>
                <td class="align-middle text-center">
                  <div class="d-flex justify-content-center align-items-center">
                      <button 
                          v-if="hasPermission(user, 'update_orders')"
                          class="btn btn-sm btn-outline-primary mr-1" 
                          @click="editOrder(order.id)"
                          title="Editar"
                      >
                          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pen-icon lucide-pen"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                      </button>
                      <button 
                          v-if="hasPermission(user, 'delete_orders')"
                          class="btn btn-sm btn-outline-danger" 
                          @click="deleteOrder(order.id)"
                          title="Excluir"
                      >
                          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                      </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <span>Mostrando {{ orders.length }} de {{ total }} pedidos</span>
        </div>
        <div v-else class="alert alert-info">
          Nenhum pedido encontrado.
        </div>
      </div>
    </div>

    <FilterComponent 
      :show="showFilterModal" 
      @close="showFilterModal = false"
      @apply="handleFilterApply"
    />

    <div id="pagination">
      <Pagination
        v-if="orders.length"
        :offset="offset"
        :total="total"
        :limit="limit"
        @change-page="changePage"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import FilterComponent from './FilterComponent.vue';
import Pagination from '../Pagination.vue';
import { hasPermission } from '../../helpers/permission';

export default {
  name: 'OrdersIndex',
  components: {
    FilterComponent,
    Pagination
  },
  data() {
    return {
      orders: [],
      loading: false,
      error: '',
      showFilterModal: false,
      appliedFilters: {},
      offset: 0,
      limit: 15,
      total: 0,
      message: ''
    }
  },
  async created() {
    await this.fetchOrders();
  },
  computed: {
    user() {
      return this.$parent.user;
    },
  },
  methods: {
    hasPermission,
    async fetchOrders() {
      this.loading = true;
      this.error = '';
      
      try {
        const { client, status } = this.appliedFilters;
        const params = { 
          client, 
          status,
          page: this.offset + 1
        };
        
        const response = await axios.get('/orders', { params });
        this.orders = response.data.data;
        this.total = response.data.total;
        this.limit = response.data.per_page;
      } catch (error) {
        this.error = 'Erro ao carregar pedidos';
        console.error(error);
      }
      
      this.loading = false;
    },
    async handleFilterApply(filters) {
      this.appliedFilters = { ...filters };
      this.offset = 0;
      await this.fetchOrders();
    },
    getStatusText(status) {
      const statuses = {
        1: 'Pendente',
        2: 'Em Transito',
        3: 'Entregue',
      };
      return statuses[status] || 'Desconhecido';
    },
    getStatusClass(status) {
      const classes = {
        1: 'badge badge-warning',
        2: 'badge badge-info',
        3: 'badge badge-success',
      };
      return classes[status] || 'badge badge-secondary';
    },
    formatDate(date) {
      if (!date) return 'N/A';
      const d = new Date(date);
      return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    },
    changePage(value) {
      this.offset = value;
      this.fetchOrders();
    },
    createOrder() {
      this.$router.push('/orders/create');
    },
    editOrder(id) {
      this.$router.push(`/orders/${id}/edit`);
    },
    async updateStatus(order) {
      try {
        await axios.put(`/orders/${order.id}`, order);
        await this.fetchOrders();
        this.message = { text: 'Status do pedido atualizado com sucesso', type: 'success' };
      } catch (err) {
        if (err.response && err.response.status === 422) {
          const errors = err.response.data.errors || {};
          this.message = { text: Object.values(errors).flat().join(', '), type: 'danger' };
        }
      }
    },
    async deleteOrder(id) {
      if (!confirm('Tem certeza que deseja excluir este pedido?')) return;
      
      try {
        await axios.delete(`/orders/${id}`);
        await this.fetchOrders();
        this.message = { text: 'Pedido excluído com sucesso', type: 'success' };
      } catch (error) {
        if (err.response && err.response.status === 422) {
          const errors = err.response.data.errors || {};
          this.message = { text: Object.values(errors).flat().join(', '), type: 'danger' };
        }
      }
    }
  }
}
</script> 