import VueRouter from "vue-router";
import * as auth from "./auth";
import Home from "../components/Home";
import Login from "../components/auth/Login";
import UserHome from "../components/users/UserHome";
import Error404 from "../components/errors/Error404";
import Error403 from "../components/errors/Error403";
import Permissions from "./role-permissions";
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
    },
    {
        path: '/me2',
        component: UserHome,
        name: 'me2',
        meta: {
            auth: true,
            permission: Permissions.core.CREATE_USERS
        }
    },
    {
        path: '/403',
        component: Error403,
        name: '403'
    },
    {
        path: '*',
        component: Error404,
        name: '404'
    }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes,
});
// The logic in the method below is stupidly complex because the next() method
// must be called EXACTLY ONE (1) time and if the method is called it DOESN'T 
// stop the function. DO NOT try to change the logic if you are not sure of 
// what you are doing.
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.auth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (auth.isLoggedIn() && auth.isUserHere()) {
            // Check if the route have any permission tag, if not, continue.
            if (to.meta.permission) {
                if (auth.checkUserPermission(to.meta.permission)) {
                    next();
                } else {
                    next({
                        name: "403"
                    });
                }
            } else {
                next();
            }
        } else {
            // Ensure the user is going to be unauthenticated in order to prevent 
            // constant redirections in worst case scenario.
            console.log('aquí');
            auth.logOut();
            next({
                name: 'login',
                query: { redirect: to.fullPath }
            });
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        // this route requires to be a guest, check if user is logged in
        // if not, continue.
        if (auth.isLoggedIn()) {
            next({
                name: 'me',
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;