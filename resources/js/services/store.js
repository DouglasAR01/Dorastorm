import * as auth from "./auth";
export default {
    state: {
        isLoggedIn: false,
        user: null,
        locale: null,
    },
    mutations: {
        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
        },
        setUser(state, payload) {
            state.user = payload;
        },
        setLocale(state, payload) {
            state.locale = payload;
        }
    },
    actions: {
        logout(context) {
            context.commit('setLoggedIn', false);
            context.commit('setUser', null);
            auth.removeSavedUser();
        },
        login(context, payload) {
            context.commit('setLoggedIn', true);
            context.commit('setUser', payload);
        },
        setLocale(context, payload) {
            context.commit('setLocale', payload);
        },
        loadSavedData(context) {
            // Here comes all the commits that have to be made everytime the app initialize

            // LoggedIn logic should be the last in the execution list
            // let isApparentlyLoggedIn = auth.isApparentlyLoggedIn();
            // // Check if the logged in token exists
            // console.log("0");
            // if (!isApparentlyLoggedIn) {
            //     console.log("No está APARENTEMENTE logeado");
            //     context.dispatch('logout');
            //     return;
            // }
            // // Check if the users is truly logged in making a call to the API
            // if (!(auth.isLoggedIn())) {
            //     console.log("limpa");
            //     context.dispatch('logout');
            //     return;
            // }
            // console.log("2");
            // // Check if the user info is loaded
            // if (!auth.isUserHere()) {
            //     context.dispatch('logout');
            //     return;
            // }
            context.commit('setLoggedIn', auth.isApparentlyLoggedIn());
            context.commit('setUser', auth.loadSavedUser());
        },
    },
    getters: {
        getUserID: state => {
            return (state.user) ? state.user.id : null;
        },
        getUserHierarchy: state => {
            return (state.user) ? state.user.role.hierarchy : null;
        },
        getUserPermissions: state => {
            return (state.user) ? state.user.role.permissions : null;
        },
        getLocaleCode: state => {
            return (state.locale) ? state.locale.code : null;
        }
    }
}