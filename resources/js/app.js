/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";
import Index from "./Index";

// Dayjs common use set up
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

// Services
import DataStore from "./services/store";
import Auth from "./services/auth";
import router from "./services/routes";
import i18n from "./services/multilang";


// Toast notifications
import VueMyToast from 'vue-my-toasts';
import 'vue-my-toasts/dist/vue-my-toasts.css'
import BootstrapComponent from "vue-my-toasts/src/components/toasts/BootstrapComponent";
import VTooltip from 'v-tooltip'

//window.Vue = require('vue');
Vue.use(VTooltip);
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VueMyToast, {
    component: BootstrapComponent,
    options: {
        width: '400px',
        position: 'bottom-right',
        padding: '1rem'
    }
});

Vue.filter('fromNow', value => dayjs(value).fromNow());
Vue.filter('humanDate', value => dayjs(value).format('YYYY/MM/DD'));

const store = new Vuex.Store(DataStore);
// GLOBAL COMPONENT REGISTRATION

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// Check everytime axios make a request if you are unauthenticated.
// This is useful if the session is destroyed but the Happy token is still in the localStorage
window.axios.interceptors.response.use(
    response => response,
    async error => {
        // 401 = unauthenticated
        if (error.response.status == 401) {
            await Auth.logout();
            store.dispatch('logout');
            router.push({ name: 'login' });
        }
        // 403 = Forbidden
        if (error.response.status == 403) {
            router.push({ name: '403' });
        }
        return Promise.reject(error);
    }
);
store.dispatch('loadSavedData');
const app = new Vue({
    el: '#app',
    router,
    store,
    i18n,
    components: {
        "index": Index
    }
});
