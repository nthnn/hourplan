import { createRouter, createWebHistory } from 'vue-router'

import Calendar from '@/views/Calendar.vue';
import HomePage from '@/views/HomePage.vue';
import Login from '@/views/Login.vue';
import Signup from '@/views/Signup.vue';
import SplashScreen from '@/views/SplashScreen.vue';
import TodoList from '@/views/TodoList.vue';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'splash_screen',
            component: SplashScreen
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: { overflowHidden: true }
        },
        {
            path: '/signup',
            name: 'signup',
            component: Signup,
            meta: { overflowHidden: true }
        },
        {
            path: '/home',
            name: 'home',
            component: HomePage
        },
        {
            path: '/todo-list',
            name: 'todo-list',
            component: TodoList
        },
        {
            path: '/calendar',
            name: 'calendar',
            component: Calendar
        }
    ]
});

export default router;
