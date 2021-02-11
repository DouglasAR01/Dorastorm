import { logOut, logIn, isLoggedIn } from "./shared/utils/auth";
export default {
    state: {
        isLoggedIn: false,
    },
    mutations: {
        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
        }
    },
    actions: {
        logout(context) {
            logOut();
            context.commit('setLoggedIn', false);
        },
        login(context) {
            logIn();
            context.commit('setLoggedIn', true);
        },
        loadSavedData(context) {
            // Here comes all the commits that have to be made everytime the app initialize
            context.commit('setLoggedIn', isLoggedIn());
        }
    },
    getters: {

    }
}