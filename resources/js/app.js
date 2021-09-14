/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import Vuetify from "vuetify";
import axios from 'axios';
import VueAxios from 'vue-axios';
// import VueRouter from "vue-router";
import 'vuetify/dist/vuetify.min.css';
import router from "./router";
Vue.use(Vuetify);
Vue.use(VueAxios, axios);


window.axios = require('axios');
const token  = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent').default);
Vue.component('calendar-component', require('./components/CalendarComponent').default);
Vue.component('add-task', require('./components/templates/add-task').default);
Vue.component('add-user', require('./components/templates/add-user').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    vuetify: new Vuetify(),
    el: '#app',
    router
});

//
// document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// console.log(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }
