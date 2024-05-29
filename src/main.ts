import { createApp, type App } from "vue";

import Application from "./App.vue";
import router from "./router";
import VCalendar from 'v-calendar';

import 'v-calendar/style.css';
import "../node_modules/bootswatch/dist/lumen/bootstrap.min.css";
import "../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js";

const app: App<Element> = createApp(Application);
app.use(router);
app.use(VCalendar, {});
app.mount('#app');
