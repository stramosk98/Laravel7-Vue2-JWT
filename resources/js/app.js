import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';

// Import components
import LoginComponent from './components/LoginComponent.vue';
import DashboardComponent from './components/DashboardComponent.vue';

Vue.use(VueRouter);

// Configure axios
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add token to requests if it exists
const token = localStorage.getItem('auth_token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

Vue.prototype.$http = axios;

// Router configuration
const routes = [
    { 
        path: '/', 
        redirect: '/login' 
    },
    { 
        path: '/login', 
        name: 'login', 
        component: LoginComponent,
        meta: { requiresGuest: true }
    },
    { 
        path: '/dashboard', 
        name: 'dashboard', 
        component: DashboardComponent,
        meta: { requiresAuth: true }
    }
];

const router = new VueRouter({
    mode: 'history',
    routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('auth_token');
    
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            next('/login');
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.requiresGuest)) {
        if (isAuthenticated) {
            next('/dashboard');
        } else {
            next();
        }
    } else {
        next();
    }
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
                const response = await this.$http.get('/user');
                this.user = response.data;
            } catch (error) {
                localStorage.removeItem('auth_token');
                delete axios.defaults.headers.common['Authorization'];
            }
        }
    }
});
