export const logIn = function () {
    localStorage.setItem('happy', 'true');
}
export const logOut = function () {
    localStorage.removeItem('happy');
}
export const isLoggedIn = function () {
    return !!localStorage.getItem('happy');
}
