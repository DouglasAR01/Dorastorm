import VueRouter from "vue-router";
import * as Auth from "./auth";
import Store from "./store";
import Permissions from "./role-permissions";
// Components
import Home from "../components/Home";
import Login from "../components/auth/Login";
import UserHome from "../components/users/UserHome";
import UserCreate from "../components/users/UserCreate";
import UserRead from "../components/users/UserRead";
import UserIndex from "../components/users/UserIndex";
import Error404 from "../components/errors/Error404";
import Error403 from "../components/errors/Error403";
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
            permission: Permissions.core.READ_USERS
        }
    },
    {
        path: '/users',
        component: UserIndex,
        name: 'users-index',
        meta: {
            auth: true,
            permission: Permissions.core.READ_USERS
        }
    },
    {
        path: '/users/view',
        component: UserRead,
        name: 'users-read',
        meta: {
            auth: true,
            permission: Permissions.core.READ_USERS
        }
    },
    {
        path: '/users/create',
        component: UserCreate,
        name: 'users-create',
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
        if (Auth.isLoggedIn() && Auth.isUserHere()) {
            // Check if the route have any permission tag, if not, continue.
            if (to.meta.permission) {
                if (Permissions.checkUserPermission(Store.state.user, to.meta.permission)) {
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
            Auth.logOut();
            // Dirty dispatch('logout'). This should be changed 
            // if the context object is known.
            // Anyway, this could only happen if the user is hacking...
            Store.state.isLoggedIn = false;
            Store.state.user = null;
            next({
                name: 'home',
                //query: { redirect: to.fullPath }
            });
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        // this route requires to be a guest, check if user is logged in
        // if not, continue.
        if (Auth.isLoggedIn()) {
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