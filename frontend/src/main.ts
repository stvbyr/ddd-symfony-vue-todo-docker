import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';
import App from './App.vue';
import Home from './components/Home.vue';
import Login from './components/Login.vue';
import Todos from './components/Todos.vue';
import TodoCreate from './components/TodoCreate.vue';
import Habits from './components/Habits.vue';

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        { path: '/', component: Home, name: 'home' },
        { path: '/login', component: Login, name: 'login' },
        { path: '/todos', component: Todos, name: 'todos' },
        { path: '/todo/create', component: TodoCreate, name: 'todo.create' },
        { path: '/habits', component: Habits, name: 'habits' },
    ],
});

const app = createApp(App);

app.use(router);

app.mount('#app');
