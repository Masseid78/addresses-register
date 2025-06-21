import './bootstrap';
import { createApp } from 'vue';
import UserView from './components/UserView.vue';
import '../css/app.css';

const app = createApp({});

app.component('user-view', UserView);

app.mount('#app');
