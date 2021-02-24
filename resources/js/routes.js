import VueRouter from "vue-router";
import { isLoggedIn } from "./shared/utils/auth";
import Home from "./components/Home";
import Login from "./components/auth/Login";
import UserHome from "./components/users/UserHome";
const routes = [
    {
        path: "/",
        component: Home,
        name: "home",
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: {
            guest: true
        }
    },
    {
        path: '/me',
        component: UserHome,
        name: 'me',
        meta: {
            auth: true
        }
    }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.auth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!isLoggedIn()) {
            next({
                name: 'login',
                query: { redirect: to.fullPath }
            })
        }
    }
    if (to.matched.some(record => record.meta.guest)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (isLoggedIn()) {
            next({
                name: 'me',
                query: { redirect: to.fullPath }
            })
        }
    }
    next();
});

export default router;