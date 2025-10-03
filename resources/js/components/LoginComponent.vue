<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4 class="mb-0">Entrar no Sistema</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="login">
              <div class="form-group">
                <label for="email">E-mail:</label>
                <input 
                  type="email" 
                  id="email" 
                  v-model="form.email" 
                  class="form-control"
                  :class="{ 'is-invalid': errors.email }"
                  required
                >
                <div v-if="errors.email" class="invalid-feedback">
                  {{ errors.email[0] }}
                </div>
              </div>
              
              <div class="form-group">
                <label for="password">Senha:</label>
                <input 
                  type="password" 
                  id="password" 
                  v-model="form.password" 
                  class="form-control"
                  :class="{ 'is-invalid': errors.password }"
                  required
                >
                <div v-if="errors.password" class="invalid-feedback">
                  {{ errors.password[0] }}
                </div>
              </div>
              
              <div class="form-group">
                <button 
                  type="submit" 
                  class="btn btn-primary btn-block"
                  :disabled="loading"
                >
                  <span v-if="loading" class="spinner-border spinner-border-sm mr-2"></span>
                  {{ loading ? 'Entrando...' : 'Entrar' }}
                </button>
              </div>
              
              <div v-if="message" class="alert alert-info">
                {{ message }}
              </div>
              
              <div v-if="error" class="alert alert-danger">
                {{ error }}
              </div>
            </form>
            
            <hr>
            <div class="text-center">
              <small class="text-muted">
                <strong>Usuários de teste:</strong><br>
                Admin: admin@exemplo.com / 123456<br>
                Gerente: gerente@exemplo.com / 123456<br>
                Usuário: usuario@exemplo.com / 123456
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LoginComponent',
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      loading: false,
      message: '',
      error: '',
      errors: {}
    }
  },
  methods: {
    async login() {
      this.loading = true;
      this.error = '';
      this.message = '';
      this.errors = {};
      
      try {
        const response = await axios.post('/login', this.form);
        
        if (response.data.token) {
          localStorage.setItem('auth_token', response.data.token);
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
          this.$parent.user = response.data.user;
          this.message = response.data.message;
          
          setTimeout(() => {
            this.$router.push('/dashboard');
          }, 1000);
        }
      } catch (error) {
        if (error.response) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          } else if (error.response.status === 401) {
            this.error = error.response.data.message || 'Credenciais inválidas';
          } else {
            this.error = 'Erro interno do servidor';
          }
        } else {
          this.error = 'Erro de conexão com o servidor';
        }
      }
      
      this.loading = false;
    }
  }
}
</script>
