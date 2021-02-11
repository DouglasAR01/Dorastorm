/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import router from "./routes";
import VueRouter from "vue-router";
import Vuex from "vuex";
import Index from "./Index";

// Dayjs common use set up
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.filter('fromNow', value => dayjs(value).fromNow());
Vue.filter('humanDate', value => dayjs(value).format('YYYY/MM/DD'));

// Datastore creation
import DataStore from "./store";
const store = new Vuex.Store(DataStore);


// GLOBAL COMPONENT REGISTRATION

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// Check everytime axios make a request if you are unauthenticated.
// This is useful if the session is destroyed but the Happy token is still in the localStorage
window.axios.interceptors.response.use(
    response => response,
    error => {
        // 401 = unauthenticated
        if (error.response.status == 401) {
            store.dispatch('logout');
            router.push({name:'login'});
        }
        return Promise.reject(error);
    }
);

const app = new Vue({
    el: '#app',
    router: router,
    store: store,
    components: {
        "index": Index
    },
    beforeCreate() {
        this.$store.dispatch('loadSavedData');
    }
});
