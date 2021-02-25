export const logIn = function () {
    localStorage.setItem('happy', 'true');
}
export const logOut = function () {
    localStorage.removeItem('happy');
    localStorage.removeItem('user');
}
export const isLoggedIn = function () {
    return !!localStorage.getItem('happy');
}
export const isUserHere = function () {
    return !!localStorage.getItem('user');
}
export const saveUser = function (user) {
    localStorage.setItem('user', JSON.stringify(user));
}
export const loadUser = function() {
    return JSON.parse(localStorage.getItem('user'));
}
export const checkUserPermission = function (permission) {
    return !!loadUser().role.permissions.find(p => p === permission);
}