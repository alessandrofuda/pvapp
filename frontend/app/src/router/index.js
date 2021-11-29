import Vue from 'vue'
import VueRouter from 'vue-router'
import Store from '@/store'
import AuthLayout from '../views/layouts/authLayout.vue'
import BasicLayout from '../views/layouts/basicLayout.vue'
import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'
// import ForgotPassword from '../views/auth/ForgotPassword.vue'
// import ResetPassword from '../views/auth/ResetPassword.vue'
import Home from '../views/Home.vue'
import About from '../views/About.vue'
import UserQuoteForm from '../views/UserQuoteForm.vue'
import PageNotFound from '../views/errors/PageNotFound.vue'
import Dashboard from '../views/Dashboard.vue'


Vue.use(VueRouter)

// available "meta" (string):
// - middleware: 'guest' || 'auth'
// - role: 'is_admin' || 'is_operator'
// - title: 'example'  --> to set: document.title = `${to.meta.title} - ${process.env.VUE_APP_NAME}` in router.beforeEach(..)
//
// Meta Handling
// router.beforeEach((to, from, next) => { ... });

const routes = [
  {
    path: '',
    name: 'guestsGroup',
    component: BasicLayout,
    meta: { middleware: "guest" },
    children: [
      {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
          title: 'Home',
          fffff:'ggggg'
        }
      },
      {
        path: '/about',
        name: 'about',
        component: About,
        meta: {
          title: 'About'
        }
      },
      {
        path: '/user-quote-form',
        name: 'user-quote-form',
        component: UserQuoteForm,
        meta: {
          title: 'Quotation Form'
        }
      }
    ]
  },
  {
    path: '',
    name: 'privatesGroup',
    component: BasicLayout,
    meta: { middleware: 'auth' },
    children: [
      {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
          role: 'is_operator',
          title: 'Dashboard'
        }
      }
    ]
  },
  {
    path: '',
    name: 'loginRegisterGroup',
    component: AuthLayout,
    meta: { middleware: "guest" },
    children: [
      {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
          title: 'Login'
        }
      },
      {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
          title: 'Register'
        }
      },
      { // TODO
        path: '/forgot-password',
        name: 'forgot-password',
        // component: ForgotPassword,
        meta: {
          title: 'Forgot password'
        }
      },
      { // TODO
        path: '/reset-password',
        name: 'reset-password',
        // component: ResetPassword,
        meta: {
          title: 'Reset password'
        }
      }
    ]
  },
  {  // all others 404 urls (always last position!)
    path: '*',
    name: 'page-not-found',
    component: PageNotFound,
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  linkActiveClass: 'active',
  routes
})

router.beforeEach((to, from, next) => {


  console.log()


  document.title = `${to.meta.title} - ${process.env.VUE_APP_NAME}`
  if(to.matched.some(r => r.meta.middleware === 'guest')){
    if(Store.state.auth.authenticated && to.matched.some(r => r.name === 'loginRegisterGroup')){
      next({name:"dashboard"})
    }
    next()
  } else if(to.matched.some(r => r.meta.middleware === 'auth')) {
    if(Store.state.auth.authenticated){
      next()
    }else{
      next({name:"login"})
    }
  } else {
      console.error('Route meta middleware not defined')
  }
})

export default router
