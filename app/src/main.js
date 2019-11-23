import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import App from './App.vue'

// Import bootstrap and bootstrap-vue CSS
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false
Vue.use(BootstrapVue)

new Vue({
  render: h => h(App),
}).$mount('#app')
