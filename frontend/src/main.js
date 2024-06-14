import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

import '@/assets/styles/main.css';
import 'bootstrap/dist/css/bootstrap.css';


const app = createApp(App);

app.use(router);

app.mount('#app');