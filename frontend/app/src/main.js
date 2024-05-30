import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

// Import Bootstrap and BootstrapVue CSS files (order is important)
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import './assets/app.scss'

/* css for vue-multiselect, imported globally. Js is imported locally, in page */
import 'vue-multiselect/dist/vue-multiselect.min.css'

// Make BootstrapVue available throughout project
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

Vue.config.productionTip = false

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.baseURL = process.env.VUE_APP_BASE_URL_API;
window.axios.defaults.withCredentials = true; // says to axios send all requests with credentials (all headers and COOKIES!)
window.axios.defaults.withXSRFToken = true;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

