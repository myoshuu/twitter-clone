import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import Axios from "axios";
import EventEmitter from "eventemitter3";

import "./assets/main.css";

const app = createApp(App);

app.use(router);

// Global properties
app.config.globalProperties.url = "http://localhost:8000";
app.config.globalProperties.apiUrl = "http://localhost:8000/api";
app.config.globalProperties.axios = Axios;
app.config.globalProperties.$emitter = new EventEmitter();

app.mount("#app");
