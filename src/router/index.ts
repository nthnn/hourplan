import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';

import Calendar from '@/views/Calendar.vue';
import HomePage from '@/views/HomePage.vue';
import Login from '@/views/Login.vue';
import ProfilePage from '@/views/ProfilePage.vue';
import Signup from '@/views/Signup.vue';
import SplashScreen from '@/views/SplashScreen.vue';
import TodoList from '@/views/TodoList.vue';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'splash_screen',
            component: SplashScreen,
            meta: { overflowHidden: true }
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
        },
        {
            path: '/profile',
            name: 'profile',
            component: ProfilePage
        }
    ] as Array<RouteRecordRaw>,
    scrollBehavior(_to, _from, savedPosition) {
        if(savedPosition)
            return savedPosition;
        return { top: 0 };
    }
});

export default router;
