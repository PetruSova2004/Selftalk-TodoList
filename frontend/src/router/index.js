import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/Home.vue';
import TaskDetail from '@/views/TaskDetail.vue';
import TaskCreate from '@/views/TaskCreate.vue';



// Используем функцию createRouter для создания экземпляра маршрутизатора
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home
        },
        {
            path: '/tasks/:taskId',
            name: 'TaskDetail',
            component: TaskDetail,
            props: true
        },
        {
            path: '/tasks/create',
            name: 'TaskCreate',
            component: TaskCreate,
            props: true
        },
    ]
});

export default router;
