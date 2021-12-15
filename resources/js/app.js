/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import Vuetify from "vuetify";
import axios from 'axios';
import VueAxios from 'vue-axios';
// import VueRouter from "vue-router";
import 'vuetify/dist/vuetify.min.css';
import ru from 'vuetify/lib/locale/ru';
import router from "./router";
import moment from 'moment';
import store from './store/index';
import { mapGetters, mapActions } from "vuex";
import CKEditor from '@ckeditor/ckeditor5-vue2';


Vue.use( CKEditor );
Vue.use(moment);
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

Vue.component('index', require('./views/index').default);

Vue.component('example-component', require('./components/ExampleComponent').default);
Vue.component('calendar-component', require('./components/CalendarComponent').default);
Vue.component('add-event', require('./views/calendar/add-event').default);
Vue.component('add-meeting', require('./views/calendar/add-meeting').default);
Vue.component('add-task', require('./components/templates/add-task').default);
Vue.component('add-user', require('./components/templates/add-user').default);
Vue.component('update-task', require('./components/templates/update-task').default);

Vue.component('department-users', require('./views/team/department-users').default);
Vue.component('team-user-tasks', require('./views/team/team-user-tasks').default);

Vue.component('user-tasks', require('./views/tasks/user-tasks').default);
Vue.component('department-tasks', require('./views/tasks/department-tasks').default);
Vue.component('task', require('./views/tasks/task').default);

Vue.component('structure-tasks', require('./views/structure/structure-tasks').default);

Vue.component('add-notification', require('./views/notifications/add-notification').default);

Vue.component('ck-editor', require('./components/templates/ckeditor').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.qapp = new Vue({
    vuetify: new Vuetify({
        lang: {
            locales: { ru },
            current: 'ru',
        },
    }),
    el: '#app',
    router,
    store,
    methods: {
        ...mapActions(['user/getAppParameters']),
    },
    created () {
        this.$store.dispatch('user/getAppParameters');
    }
});

// $("#d1").on("shown.bs.modal", function() {
//     tinyMCE.get("editor").focus();
// });
