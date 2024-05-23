import { createRouter, createWebHistory } from 'vue-router'

import HomePage from '@/views/HomePage.vue';
import Login from '@/views/Login.vue';
import Signup from '@/views/Signup.vue';
import SplashScreen from '@/views/SplashScreen.vue';

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
            component: Login
        },
        {
            path: '/signup',
            name: 'signup',
            component: Signup
        },
        {
            path: '/home',
            name: 'home',
            component: HomePage
        }
    ]
});

export default router;
