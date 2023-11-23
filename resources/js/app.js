import { createApp } from 'vue';
import './bootstrap.js'; // Ensure that 'bootstrap.js' is in the same directory or provide the correct path
import CookieComponent from './components/CookieComponent.vue';

const app = createApp({});
app.component('cookie-component', CookieComponent);
app.mount('#app');