import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import HomeComponent from '../components/HomeComponent.vue'
import DashboardComponent from '../components/DashboardComponent.vue'
import HowToComponent from '../components/HowToComponent.vue'
// import LoginComponent from '../components/LoginComponent.vue'
// import RegisterComponent from '../components/RegisterComponent.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeComponent
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: DashboardComponent
  },
  {
    path: '/how-to',
    name: 'howTo',
    component: HowToComponent
  },
  // {
  //   path: '/login',
  //   name: 'login',
  //   component: LoginComponent
  // },
  // {
  //   path: '/register',
  //   name: 'register',
  //   component: RegisterComponent
  // }
]

const router = new VueRouter({
  routes,
  mode: 'history',
  linkActiveClass: 'active',
})

export default router