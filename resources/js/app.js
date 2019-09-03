import Vue from 'vue'
import VueRouter from 'vue-router'

import App from './components/App'
import Index from './components/Index'
import Login from './components/Login'
import NotFound from './components/NotFound'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'index',
      component: Index
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '*',
      name: 'not-found',
      component: NotFound
    }
  ]
})

const app = new Vue({
  el: '#app',
  components: { App },
  render: h => h(App),
  router
})
