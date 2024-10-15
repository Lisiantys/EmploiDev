import './bootstrap';
import { createApp } from "vue";
import App from "./components/App.vue";
import router from './routeur'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import Notifications from '@kyvg/vue3-notification';

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

createApp(App).use(router).use(pinia).use(Notifications).mount("#app")