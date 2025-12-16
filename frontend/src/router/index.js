import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Tasks from '../views/Tasks.vue';

const routes = [
    { path: '/login', component: Login },
    { path: '/tasks', component: Tasks },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});