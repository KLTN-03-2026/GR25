import { createApp } from "vue";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import App from "./App.vue";
import router from "./router";
import Toaster from "@meforma/vue-toaster";
import "./style.css";


import WrapperLayout from "./layout/wrapper/Admin/index.vue";

const app = createApp(App);

app.component("wrapper-layout", WrapperLayout);
app.use(Toaster, {
  position: "top-right",
  duration: 3500,
});

app.use(router).mount("#app");
