import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import App from './App.vue'
import VueRouter from 'vue-router'

// Import bootstrap and bootstrap-vue CSS
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Import components
import MealList from './components/MealList.vue'

Vue.config.productionTip = false

Vue.use(VueRouter)
Vue.use(BootstrapVue)

// Create a new router
const router = new VueRouter({
  // Define mode
  mode: 'history',
  // Define routes in an array of objects
  routes: [
    // Root Route
    { path: '/', component: MealList },
  ]
})

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
