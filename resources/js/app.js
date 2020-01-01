/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')

import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue)

import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

// Import and configure toaster.
import toastr from 'toastr';
toastr.options.preventDuplicates = true;
toastr.options.progressBar = true;
toastr.options.closeDuration = 5000;

window.toastr = toastr

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app-component', require('./App.vue').default)

import router from './router'

import store from './store'

import mixin from './mixin'
Vue.mixin(mixin)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store
})
