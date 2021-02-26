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
            auth.logOut();
            context.commit('setLoggedIn', false);
            context.commit('setUser', null);
        },
        async login(context) {
            await context.dispatch('loadUser');
            auth.logIn();
            context.commit('setLoggedIn', true);
        },
        loadSavedData(context) {
            // Here comes all the commits that have to be made everytime the app initialize

            // LoggedIn logic should be the last in the execution list
            let isLoggedIn = auth.isLoggedIn();
            if (isLoggedIn) {
                if (auth.isUserHere()){
                    context.commit('setLoggedIn', isLoggedIn);
                    context.commit('setUser', auth.loadUser());
                } else {
                    context.dispatch('logout');
                }
            }
        },
        async loadUser(context) {
            try {
                const user = (await axios.get("/api/user")).data.data;
                context.commit('setUser', user);
                auth.saveUser(user);
            } catch (error) {
                context.dispatch('logout');
            }
        }
    },
    getters: {

    }
}