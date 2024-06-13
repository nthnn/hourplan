import { createApp, type App } from "vue";

import Application from "./App.vue";
import router from "./router";
import VCalendar from 'v-calendar';

import 'v-calendar/style.css';
import "../node_modules/bootswatch/dist/lumen/bootstrap.min.css";
import "../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js";

import "vuetify/styles";
import { createVuetify, type VuetifyOptions } from 'vuetify';
import * as VuetifyComponents from "vuetify/components";
import * as VuetifyDirectives from "vuetify/directives";

interface VuetifyComponentsType extends VuetifyOptions {
    VuetifyComponents?: any;
    VuetifyDirectives?: any;
}

const app: App<Element> = createApp(Application);
const vuetify = createVuetify({
    VuetifyComponents,
    VuetifyDirectives,
} as VuetifyComponentsType);

app.use(router);
app.use(vuetify);
app.use(VCalendar, {});
app.mount('#app');
