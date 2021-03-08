export const logIn = async function (userPayload) {
    try {
        await axios.post("/api/login", userPayload);
        try {
            // User successfully logged in
            return (await loadUser());
        } catch (error) {
            // For some reason, the user could log in but his data cannot be
            // retreived.
            await logOut();
            throw error;
        }
    } catch (error) {
        // Couldn't log in for some reason
        throw error;
    }
}
export const logOut = async function () {
    try {
        await axios.post("/api/logout");
        localStorage.removeItem('happy');
        localStorage.removeItem('user');
    } catch (error) {
        // No internet connection
    }    
}
export const isLoggedIn = function () {
    return !!localStorage.getItem('happy');
}
export const isUserHere = function () {
    return !!localStorage.getItem('user');
}
export const loadUser = async function() {
    const user = (await axios.get("/api/user")).data.data;
    saveUser(user);
    localStorage.setItem('happy', 'true');
    return user;
}
export const saveUser = function (user) {
    localStorage.setItem('user', JSON.stringify(user));
}
export const loadSavedUser = function() {
    return JSON.parse(localStorage.getItem('user'));
}