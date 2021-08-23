import * as auth from "./auth";
export default {
    state: {
        isLoggedIn: false,
        user: null,
        locale: null
    },
    mutations: {
        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
        },
        setUser(state, payload) {
            state.user = payload;
        }
    },
    actions: {
        logout(context) {
            context.commit('setLoggedIn', false);
            context.commit('setUser', null);
        },
        login(context, payload) {
            context.commit('setLoggedIn', true);
            context.commit('setUser', payload);
        },
        loadSavedData(context) {
            // Here comes all the commits that have to be made everytime the app initialize

            // LoggedIn logic should be the last in the execution list
            let isApparentlyLoggedIn = auth.isApparentlyLoggedIn();
            if (!isApparentlyLoggedIn) {
                context.dispatch('logout');
                return;
            }
            if (!auth.isLoggedIn()) {
                context.dispatch('logout');
                return;
            }
            if (!auth.isUserHere()) {
                context.dispatch('logout');
                return;
            }
            context.commit('setLoggedIn', isApparentlyLoggedIn);
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
        }
    }
}