import VueRouter from "vue-router";
import Auth, * as AuthExtra from "./auth";
import Store from "./store";
import Permissions from "./role-permissions";
// Components
import TheHome from "../components/TheHome";
import TheLogin from "../components/auth/TheLogin";
// Static (not splitted) components
import ForgotPassword from "../components/auth/ForgotPassword";
import PostRead from "../components/posts/PostRead";
import PostIndex from "../components/posts/PostIndex";
import Error404 from "../components/errors/Error404";
import Error403 from "../components/errors/Error403";
const routes = [
    {
        path: "/",
        component: TheHome,
        name: "home",
    },
    {
        path: '/forgot-password',
        component: ForgotPassword,
        name: 'forgot-password',
        meta: {
            guest: true
        }
    },
    {
        path: '/reset-password',
        component: () => import (/* webpackChunkName: "auth" */"../components/auth/ResetPassword"),
        name: 'reset-password',
        meta: {
            guest: true
        }
    },
    {
        path: '/login',
        component: TheLogin,
        name: 'login',
        meta: {
            guest: true
        }
    },
    {
        path: '/me',
        component: () => import (/* webpackChunkName: "users" */"../components/users/UserHome"),
        name: 'me',
        meta: {
            auth: true
        }
    },
    {
        path: '/me2',
        component: () => import (/* webpackChunkName: "users" */"../components/users/UserHome"),
        name: 'me2',
        meta: {
            auth: true,
            permission: Permissions.core.READ_USERS
        }
    },
    {
        path: '/users',
        component: () => import (/* webpackChunkName: "users" */"../components/users/UserIndex"),
        name: 'users-index',
        meta: {
            auth: true,
            permission: Permissions.core.READ_USERS
        }
    },
    {
        path: '/users/view',
        component: () => import (/* webpackChunkName: "users" */"../components/users/UserRead"),
        name: 'users-read',
        meta: {
            auth: true,
            permission: Permissions.core.READ_USERS
        }
    },
    {
        path: '/users/create',
        component: () => import (/* webpackChunkName: "users" */"../components/users/UserCreate"),
        name: 'users-create',
        meta: {
            auth: true,
            permission: Permissions.core.CREATE_USERS
        }
    },
    {
        path: '/users/update/:userId',
        component: () => import (/* webpackChunkName: "users" */"../components/users/UserUpdate"),
        name: 'users-update',
        meta: {
            auth: true
        }
    },
    {
        path: '/roles',
        component: () => import (/* webpackChunkName: "roles" */"../components/roles/RoleIndex"),
        name: 'roles-index',
        meta: {
            auth: true,
            permission: Permissions.core.READ_ROLES
        }
    },
    {
        path: '/roles/create',
        component: () => import (/* webpackChunkName: "roles" */"../components/roles/RoleCreate"),
        name: 'roles-create',
        meta: {
            auth: true,
            permission: Permissions.core.CREATE_ROLES
        }
    },
    {
        path: '/roles/update/:roleId',
        component: () => import (/* webpackChunkName: "roles" */"../components/roles/RoleUpdate"),
        name: 'roles-update',
        meta: {
            auth: true,
            permission: Permissions.core.UPDATE_ROLES
        }
    },
    ,
    {
        path: '/posts/create',
        component: () => import (/* webpackChunkName: "posts" */"../components/posts/PostCreate"),
        name: 'posts-create',
        meta: {
            auth: true,
            permission: Permissions.core.CREATE_POSTS
        }
    },
    {
        path: '/posts',
        component: PostIndex,
        name: 'posts-index'
    },
    {
        path: '/private/posts',
        component: PostIndex,
        name: 'private-posts-index',
        props: {
            aditionalParams: {
                p: 1
            }
        },
        meta: {
            auth: true
        }
    },
    {
        path: '/user/posts',
        component: PostIndex,
        name: 'user-posts',
        props: {
            aditionalParams: {
                mine: 1
            }
        },
        meta: {
            auth: true
        }
    },
    {
        path: '/posts/:slug',
        component: PostRead,
        name: 'posts-read'
    },
    {
        path: '/posts/update/:postId',
        component: () => import (/* webpackChunkName: "posts" */"../components/posts/PostUpdate"),
        name: 'posts-update',
        meta: {
            auth: true
            // User ownership is checked at PostUpdate component
            // permission: Permissions.core.UPDATE_ELSES_POSTS
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
        if (AuthExtra.isLoggedIn() && AuthExtra.isUserHere()) {
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
            if (Auth.logout()) {
                // Dirty dispatch('logout'). This should be changed 
                // if the context object is known.
                // Anyway, this could only happen if the user is hacking...
                Store.state.isLoggedIn = false;
                Store.state.user = null;
            }
            next({
                name: 'home',
                //query: { redirect: to.fullPath }
            });
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        // this route requires to be a guest, check if user is logged in
        // if not, continue.
        if (AuthExtra.isLoggedIn()) {
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