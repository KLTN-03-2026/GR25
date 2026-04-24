// main.js
import { createApp } from "vue";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import App from "../App.vue";
import router from "./router";
import VueToaster from "@meforma/vue-toaster";
import "./style.css";
import Swal from "sweetalert2";
import 'leaflet/dist/leaflet.css'

// IMPORT axios instance ĐÃ CẤU HÌNH từ config.js
import api from "./axios/config";

// IMPORT LAYOUT
import AdminLayout from "./layout/wrapper/Admin/index.vue";
import BlankLayout from "./layout/wrapper/Blank/index.vue";
import KhachHangLayout from "./layout/wrapper/KhachHang/index.vue";
import VueApexCharts from 'vue3-apexcharts';
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import 'boxicons'
window.Pusher = Pusher

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: 'localhost',
  wsPort: 8080,
  forceTLS: false,
  disableStats: true,
});
const app = createApp(App);
// Register components
app.component("admin-layout", AdminLayout);
app.component("blank-layout", BlankLayout);
app.component("khach-hang-layout", KhachHangLayout);

// Plugins
app.use(VueApexCharts);
app.use(VueToaster, {
  position: "top-right",
  duration: 3500,
});
app.use(router);

// ✅ Export api để dùng trong component
app.config.globalProperties.$axios = api;
export { api };

app.mount("#app");


