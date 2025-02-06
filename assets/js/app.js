import { createApp } from 'vue';
//import VueRouter from 'vue-router';


import App from './pages/app';

const app = createApp(App)

/*app.use(VueRouter);


const routes = [
    { path: '/', component: App },
]

const router = new VueRouter({
    mode: 'history',
    routes
})*/

app.mount('#app');