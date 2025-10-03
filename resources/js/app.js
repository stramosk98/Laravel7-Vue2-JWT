import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';

// Import components
import LoginComponent from './components/LoginComponent.vue';
import DashboardComponent from './components/DashboardComponent.vue';
import NoPermission from './components/NoPermission.vue';
import OrdersIndex from './components/orders/index.vue';
import OrdersAddOrUpdate from './components/orders/addOrUpdate.vue';

Vue.use(VueRouter);

// Configure axios
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Add token to requests if it exists
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  } else {
    delete config.headers.Authorization;
  }
  return config;
}, error => Promise.reject(error));

axios.interceptors.response.use(response => response, error => {
  if (error.response && error.response.status === 401) {
    localStorage.removeItem('auth_token');
    delete axios.defaults.headers.common['Authorization'];
    window.location.href = '/login';
  }
  if (error.response && error.response.status === 403) {
    window.location.href = '/no-permission';
  }
  return Promise.reject(error);
});

Vue.prototype.$http = axios;

// Router configuration
const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'login', component: LoginComponent, meta: { requiresGuest: true } },
  { path: '/dashboard', name: 'dashboard', component: DashboardComponent, meta: { requiresAuth: true } },
  { path: '/no-permission', name: 'no-permission', component: NoPermission, meta: { requiresAuth: true } },
  { path: '/orders', name: 'orders.index', component: OrdersIndex, meta: { requiresAuth: true } },
  { path: '/orders/create', name: 'orders.create', component: OrdersAddOrUpdate, meta: { requiresAuth: true } },
  { path: '/orders/:id/edit', name: 'orders.edit', component: OrdersAddOrUpdate, meta: { requiresAuth: true } },
];

const router = new VueRouter({
  mode: 'history',
  routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('auth_token');

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) return next('/login');
    return next();
  }

  if (to.matched.some(record => record.meta.requiresGuest)) {
    if (isAuthenticated) return next('/dashboard');
    return next();
  }

  next();
});

// Vue instance
const app = new Vue({
  el: '#app',
  router,
  data: {
    user: null
  },
  async created() {
    const token = localStorage.getItem('auth_token');
    if (token) {
      try {
        const response = await axios.get('/user');
        this.user = response.data;
      } catch (error) {
        localStorage.removeItem('auth_token');
        delete axios.defaults.headers.common['Authorization'];
        if (this.$route.path !== '/login') {
          this.$router.push('/login');
        }
      }
    }
  }
});
