import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

Vue.config.productionTip = false



window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.baseURL = process.env.VUE_APP_BASE_URL_API;



new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

