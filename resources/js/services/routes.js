import VueRouter from "vue-router";
import Auth, * as AuthExtra from "./auth";
import Store from "./store";
import Permissions from "./role-permissions";
import i18n, { getLocale, getLocalesCodes, loadLocale } from "./multilang";
import Config from "../app.config";
// Components
import TheHome from "../components/TheHome";
import TheLogin from "../components/auth/TheLogin";
// Static (not splitted) components
import ForgotPassword from "../components/auth/ForgotPassword";
import PostRead from "../components/posts/PostRead";
import PostIndex from "../components/posts/PostIndex";
import Error404 from "../components/errors/Error404";
import Error403 from "../components/errors/Error403";
// Testing
import TheTester from "../components/TheTester";

const CHILD_ROUTES = [
    {
        path: '',
        component: TheHome,
        name: 'home'
    },
    {
        path: 'forgot-password',
        component: ForgotPassword,
        name: 'forgot-password',
        meta: {
            guest: true
        }
    },
    {
        path: 'reset-password',
        component: () => import(/* webpackChunkName: "auth" */"../components/auth/ResetPassword"),
        name: 'reset-password',
        meta: {
            guest: true
        }
    },
    {
        path: 'login',
        component: TheLogin,
        name: 'login',
        meta: {
            guest: true
        }
    },
    {
        path: 'me',
        component: () => import(/* webpackChunkName: "users" */"../components/users/UserHome"),
        name: 'me',
        meta: {
            auth: true
        }
    },
    {
        path: 'users',
        component: () => import(/* webpackChunkName: "users" */"../components/users/UserIndex"),
        name: 'users-index',
        meta: {
            auth: true,
            permission: Permissions.core.READ_USERS
        }
    },
    {
        path: 'users/create',
        component: () => import(/* webpackChunkName: "users" */"../components/users/UserCreate"),
        name: 'users-create',
        meta: {
            auth: true,
            permission: Permissions.core.CREATE_USERS
        }
    },
    {
        path: 'users/update/:userId',
        component: () => import(/* webpackChunkName: "users" */"../components/users/UserUpdate"),
        name: 'users-update',
        props: true,
        meta: {
            auth: true
        }
    },
    {
        path: 'roles',
        component: () => import(/* webpackChunkName: "roles" */"../components/roles/RoleIndex"),
        name: 'roles-index',
        meta: {
            auth: true,
            permission: Permissions.core.READ_ROLES
        }
    },
    {
        path: 'roles/create',
        component: () => import(/* webpackChunkName: "roles" */"../components/roles/RoleCreate"),
        name: 'roles-create',
        meta: {
            auth: true,
            permission: Permissions.core.CREATE_ROLES
        }
    },
    {
        path: 'roles/update/:roleId',
        component: () => import(/* webpackChunkName: "roles" */"../components/roles/RoleUpdate"),
        name: 'roles-update',
        meta: {
            auth: true,
            permission: Permissions.core.UPDATE_ROLES
        }
    },
    ,
    {
        path: 'posts/create',
        component: () => import(/* webpackChunkName: "posts" */"../components/posts/PostCreate"),
        name: 'posts-create',
        meta: {
            auth: true,
            permission: Permissions.core.CREATE_POSTS
        }
    },
    {
        path: 'posts',
        component: PostIndex,
        name: 'posts-index'
    },
    {
        path: 'private/posts',
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
        path: 'user/posts',
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
        path: 'posts/:slug',
        component: PostRead,
        name: 'posts-read'
    },
    {
        path: 'posts/update/:postId',
        component: () => import(/* webpackChunkName: "posts" */"../components/posts/PostUpdate"),
        name: 'posts-update',
        meta: {
            auth: true
            // User ownership is checked at PostUpdate component
            // permission: Permissions.core.UPDATE_ELSES_POSTS
        }
    },
    {
        path: 'quotes',
        component: () => import(/* webpackChunkName: "quotes" */"../components/quotes/QuoteIndex"),
        name: 'quotes-index',
        meta: {
            auth: true
        }
    },
    {
        path: '403',
        component: Error403,
        name: '403'
    },
    {
        path: '*',
        component: Error404,
        name: '404'
    }
];
var BASE_ROUTES = [
    {
        path: `/:locale${getLocalesCodes()}?`,
        component: {
            template: "<router-view></router-view>"
        },
        children: CHILD_ROUTES,
        beforeEnter(to, from, next) {
            const locale = getLocale(to.params.locale).code;
            if (locale != i18n.locale) {
                loadLocale(locale).then(() => {
                    i18n.locale = locale;
                });
            }
            next();
        }
    }
];

if (Config.DEBUG) {
    BASE_ROUTES.splice(0, 0,
        {
            path: '/component-test',
            component: TheTester,
            name: 'test-path'
        });
}

const router = new VueRouter({
    mode: 'history',
    routes: BASE_ROUTES
});

// The logic in the method below is stupidly complex because the next() method
// must be called EXACTLY ONE (1) time and if the method is called it DOESN'T 
// stop the function. DO NOT try to change the logic if you are not sure of 
// what you are doing.
router.beforeEach((to, from, next) => {
    // let localeFrom = from.params.locale;
    // let localeTo = to.params.locale;
    // if (localeFrom && !localeTo){
    //     console.log("sí");
    //     to.params.locale = localeFrom;
    // }
    // Check if the ROUTE requires to be authenticated
    if (to.matched.some(record => record.meta.auth)) {
        // Check if the USER is authenticated
        if (AuthExtra.isApparentlyLoggedIn() && AuthExtra.isUserHere()) {
            // The USER is authenticated, now check if the route have any permission tag, if not, continue.
            if (to.meta.permission) {
                // Check if the USER have the right permission to enter into this route. If not, 
                // redirects to 403.
                if (Permissions.checkUserPermission(Store.state.user, to.meta.permission)) {
                    next();
                } else {
                    next();
                }
            } else {
                next();
            }
        } else {
            // The USER ISN'T authenticated. He is going to be redirected to the login page.
            // Ensure the user is going to be unauthenticated in order to prevent 
            // constant redirections in worst case scenario.
            if (Auth.logout()) {
                // Dirty dispatch('logout'). This should be changed if the context object is known.
                // Anyway, this could only happen if the user is hacking...
                Store.state.isLoggedIn = false;
                Store.state.user = null;
            }
            next();
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        // This route (↑) requires to be a GUEST.
        // Check if the user is authenticated. Since this route is for guest only, the USER
        // shouldn't be here.
        if (AuthExtra.isApparentlyLoggedIn()) {
            // Redirect to another, non-GUEST route.
            next();
        } else {
            // The user wasn't authenticated. Continue.
            next();
        }
    } else {
        // The route doesn't have any matched meta tag. Continue.
        next();
    }
});
export default router;