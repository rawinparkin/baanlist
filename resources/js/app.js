import "./bootstrap";
import Alpine from "alpinejs";
import { createApp } from "vue";

// Vue components
import SendMessage from "./components/SendMessage.vue";
import ChatMessage from "./components/ChatMessage.vue";

import L from "leaflet";
import "./leaflet-custom.css";
// ✅ Make Leaflet globally available (so `L` works in inline scripts, if needed)
window.L = L;
import "leaflet.markercluster";
import "leaflet-gesture-handling";
import "leaflet-control-geocoder";

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
