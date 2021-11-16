/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";
import VueMeta from "vue-meta";
import Index from "./pages/Index";
import CONFIG from "./app.config";

// Dayjs common use set up
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

// Services
import DataStore from "./services/store";
import Auth, {isApparentlyLoggedIn, checkLoggedIn, isUserHere} from "./services/auth";
import router from "./services/routes";
import i18n, { getLocale } from "./services/multilang";


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
Vue.use(VueMeta);

Vue.filter('fromNow', value => dayjs(value).fromNow());
Vue.filter('humanDate', value => dayjs(value).format(CONFIG.DATE_FORMAT));

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
            router.push({ name: 'login', params: { locale: store.getters.getLocaleCode } });
        }
        // 403 = Forbidden
        if (error.response.status == 403) {
            router.push({ name: '403', params: { locale: store.getters.getLocaleCode } });
        }
        return Promise.reject(error);
    }
);

function showApp() {
    return new Vue({
        el: '#app',
        router,
        store,
        i18n,
        components: {
            TheIndex: Index
        },
        metaInfo () {
            let info = {
                title: this.$t(CONFIG.meta.title.APP_TITLE)
            };
            if (CONFIG.meta.title.TEMPLATE_ON) {
                info["titleTemplate"] = CONFIG.meta.title.TEMPLATE
            }
            return info;
        }
    });
}
// App booting
//console.log("Iniciando...");
// Check if the URL has a locale
const loc = window.location.pathname.split("/", 2);
const lang = (loc[1] != '') ? getLocale(loc[1]) : getLocale();
const payload = (lang) ? lang : getLocale();
store.dispatch('setLocale', payload);
// Check if the user is apparently logged in
if (isApparentlyLoggedIn()) {
    //console.log("Verificando...");
    checkLoggedIn().then((response) => {
        // If the user isn't signed in server side flush the session data
        if (!response.data.result) {
            //console.log("Borrando...");
            store.dispatch('logout');
        } else {
            // If the users is signed in server sided, load the data
            //console.log("Comprobando usuario...");
            if (isUserHere()){
                //console.log("Cargando...");
                store.dispatch('loadSavedData');
            }
        }
    }).then(() => {
        //console.log("Ejecutando...");
        showApp();
    });

} else {
    //console.log("Ejecutando guest...");
    showApp();
}
