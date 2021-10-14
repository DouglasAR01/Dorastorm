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