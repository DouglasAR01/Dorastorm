export const isLoggedIn = function () {
    return !!localStorage.getItem('happy');
}
export const isUserHere = function () {
    return !!localStorage.getItem('user');
}
export const saveUser = function (user) {
    localStorage.setItem('user', JSON.stringify(user));
}
export const loadSavedUser = function () {
    return JSON.parse(localStorage.getItem('user'));
}

export default {
    async getCsrfCookie() {
        return axios.get("/sanctum/csrf-cookie");
    },

    async login(userPayload) {
        try {
            await axios.post("/api/login", userPayload);
            try {
                // User successfully logged in
                return (await this.loadUser());
            } catch (error) {
                // The system will try to log out and will throw an error 
                // even if the logout was successful
                await this.logout();
                throw error;
            }
        } catch (error) {
            // Couldn't log in for some reason
            throw error;
        }
    },

    async logout() {
        try {
            await axios.post("/api/logout");
            localStorage.removeItem('happy');
            localStorage.removeItem('user');
            return true;
        } catch (error) {
            // No internet connection
        }
        return false;
    },

    async loadUser() {
        try {
            // Everything went fine and the user is logged in.
            const user = (await axios.get("/api/user")).data.data;
            saveUser(user);
            localStorage.setItem('happy', 'true');
            return user;
        } catch (error) {
            // The user could log in, however, his data couldn't be retrieved
            throw error;
        }
    },

    async forgotPassword(payload) {
        return axios.post('/api/forgot-password', payload);
    },

    async resetPassword(payload) {
        return axios.post('/api/reset-password', payload);
    },

    async userRolesBelow() {
        return axios.get('/api/user/roles-below');
    }
}