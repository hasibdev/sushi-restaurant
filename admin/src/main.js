import Vue from "vue";
import App from "./App.vue";
import BootstrapVue from "bootstrap-vue";
import VueApexCharts from "vue-apexcharts";
import Vuelidate from "vuelidate";
import VueSweetalert2 from "vue-sweetalert2";
import VueMask from "v-mask";
import * as VueGoogleMaps from "vue2-google-maps";
import VueYoutube from "vue-youtube";

import initPlugin from "@/plugins/init.js";
import auth from "@/plugins/auth.js";
import axios from "@/plugins/axios.js";
import VueAxios from "vue-axios";

import "./assets/js/essential.js";

import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";

import vco from "v-click-outside";

import router from "./router";
import store from "@/state/store";
import i18n from "./i18n";

import "@/assets/scss/app.scss";

import firebase from "./helpers/push-notification.js";

Vue.prototype.$firebase = firebase;

Vue.config.productionTip = false;
Vue.use(BootstrapVue);
Vue.use(vco);
Vue.use(VueAxios, axios);
Vue.use(initPlugin);
Vue.use(auth);
Vue.use(VueYoutube);
Vue.use(Vuelidate);
Vue.use(VueSweetalert2);
Vue.use(VueMask);
Vue.use(require("vue-chartist"));
Vue.use(Toast, { position: "bottom-left" });
Vue.use(VueGoogleMaps, {
  load: {
    key: "AIzaSyAbvyBxmMbFhrzP9Z8moyYr6dCr-pzjhBE",
    libraries: "places",
  },
  installComponents: true,
});
Vue.component("apexchart", VueApexCharts);

new Vue({
  router,
  store,
  i18n,
  render: (h) => h(App),
}).$mount("#app");
