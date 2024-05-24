import { createApp } from "vue";

import App from "./App.vue";
import router from "./router";
import VCalendar from 'v-calendar';

import 'v-calendar/style.css';
import "../node_modules/bootswatch/dist/lumen/bootstrap.min.css";
import "../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js";

const app = createApp(App);
app.use(router);
app.use(VCalendar, {});
app.mount('#app');
