import { createApp } from "vue";

import App from "./App.vue";
import router from "./router";

import "../node_modules/bootswatch/dist/lumen/bootstrap.min.css";
import "../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js";

const app = createApp(App)
app.use(router)
app.mount('#app')
