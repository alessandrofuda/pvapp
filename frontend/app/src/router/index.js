import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import About from '../views/About.vue'
import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'
import ApplicationForm from '../views/ApplicationForm.vue'
import PageNotFound from '../views/errors/PageNotFound.vue'


Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/about',
    name: 'about',
    component: About
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    // component: ForgotPassword
  },
  {
    path: '/application-form',
    name: 'application-form',
    component: ApplicationForm
  },
  {
    path: '*',
    name: 'page-not-found',
    component: PageNotFound,
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  // linkActiveClass: 'active',
  routes
})

export default router
