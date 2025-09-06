import "./bootstrap";
import Alpine from "alpinejs";
import { createApp } from "vue";

// Vue components
import SendMessage from "./components/SendMessage.vue";
import ChatMessage from "./components/ChatMessage.vue";

window.Alpine = Alpine;
Alpine.start();

// Mount Vue app only if #app exists
const el = document.getElementById("app");

if (el) {
    const app = createApp({});

    // Register Vue components globally
    app.component("send-message", SendMessage);
    app.component("chat-message", ChatMessage);

    // Mount the Vue app
    app.mount("#app");
}
