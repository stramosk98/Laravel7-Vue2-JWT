<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Painel do Usuário</h4>
            <div class="d-flex">
              <button v-if="hasPermission(user, 'view_orders')" @click="viewOrders" class="btn btn-outline-success btn-md mr-2">
                Pedidos
              </button>
              <button @click="logout" class="btn btn-outline-danger btn-md">
                Sair
              </button>
            </div>
          </div>
          <div class="card-body">
            <div v-if="user" class="mb-4">
              <h5>Bem-vindo, {{ user.name }}!</h5>
              <p class="text-muted">E-mail: {{ user.email }}</p>
            </div>
            
            <div v-if="dashboardData" class="alert alert-success">
              <h6>Mensagem do Servidor:</h6>
              <p class="mb-0">{{ dashboardData.message }}</p>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-body">
                    <h6 class="card-title">Sistema de Autenticação</h6>
                    <p class="card-text">
                      Você está logado com sucesso no sistema BoxLog
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card bg-light">
                  <div class="card-body">
                    <h6 class="card-title">Tecnologias</h6>
                    <ul class="list-unstyled">
                      <li>Laravel 7</li>
                      <li>Vue 2</li>
                      <li>MySQL</li>
                      <li>Docker</li>
                      <li>Bootstrap 4</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { hasPermission } from '../helpers/permission';

export default {
  name: 'DashboardComponent',
  data() {
    return {
      dashboardData: null
    }
  },
  computed: {
    user() {
      return this.$parent.user;
    },
  },
  async created() {
    try {
      const response = await axios.get('/dashboard');
      this.dashboardData = response.data;
    } catch (error) {
      console.error('Erro ao carregar dados do dashboard:', error);
    }
  },
  methods: {
    hasPermission,
    viewOrders() {
      this.$router.push('/orders');
    },
    async logout() {
      try {
        await axios.post('/logout');
      } catch (error) {
        console.error('Erro no logout:', error);
      } finally {
        localStorage.removeItem('auth_token');
        delete axios.defaults.headers.common['Authorization'];
        this.$parent.user = null;
        this.$router.push('/login');
      }
    }
  }
}
</script>
