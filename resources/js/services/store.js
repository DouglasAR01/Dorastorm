import * as auth from "./auth";
export default {
    state: {
        isLoggedIn: false,
        user: null
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
            let isLoggedIn = auth.isLoggedIn();
            if (isLoggedIn) {
                if (auth.isUserHere()) {
                    context.commit('setLoggedIn', isLoggedIn);
                    context.commit('setUser', auth.loadSavedUser());
                } else {
                    context.dispatch('logout');
                }
            }
        },
    },
    getters: {
        getUserID: state => {
            return (state.user)? state.user.id : null;
        },
        getUserHierarchy: state => {
            return (state.user)? state.user.role.hierarchy : null;
        }
    }
}