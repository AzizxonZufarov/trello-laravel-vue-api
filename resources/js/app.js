import { createApp } from 'vue';
import App from './components/App.vue';
import Home from './components/Home.vue';
import About from './components/About.vue';
import Desks from './components/desks/Desks.vue';
import ShowDesk from './components/desks/ShowDesk.vue';
import { createRouter, createWebHistory } from 'vue-router';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/about',
            name: 'about',
            component: About
        },
        {
            path: '/desks',
            name: 'desks',
            component: Desks
        },
        {
            path: '/desks/:deskId',
            name: 'showDesk',
            component: ShowDesk,
            props: true
        }
    ]
});

const app = createApp(App);
app.use(router);
app.mount('#app');