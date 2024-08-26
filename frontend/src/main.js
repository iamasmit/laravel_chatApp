import "./assets/main.css";

import Aura from "@/presets/aura";

import { createApp } from "vue";
import { createPinia } from "pinia";
import PrimeVue from "primevue/config";
import App from "./App.vue";
import router from "./router";

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(PrimeVue, {
    unstyled: true,
    pt: Aura,
});

app.mount("#app");
