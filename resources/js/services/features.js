import CONFIG from "../app.config";
import Permissions from "./role-permissions";
import Login from "../pages/auth/Login";
import ForgotPassword from "../pages/auth/ForgotPassword";
import PostRead from "../pages/posts/PostRead";
import PostIndex from "../pages/posts/PostIndex";

var featureArray = [];

if (CONFIG.features.AUTH) {
    featureArray.push(
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
            component: () => import(/* webpackChunkName: "auth" */"../pages/auth/ResetPassword"),
            name: 'reset-password',
            meta: {
                guest: true
            }
        },
        {
            path: 'login',
            component: Login,
            name: 'login',
            meta: {
                guest: true
            }
        },
        {
            path: 'me',
            component: () => import(/* webpackChunkName: "users" */"../pages/users/UserHome"),
            name: 'me',
            meta: {
                auth: true
            }
        }
    );
}

if (CONFIG.features.AUTH && CONFIG.features.USERS_MANAGEMENT) {
    featureArray.push(
        {
            path: 'users',
            component: () => import(/* webpackChunkName: "users" */"../pages/users/UserIndex"),
            name: 'users-index',
            meta: {
                auth: true,
                permission: Permissions.core.READ_USERS
            }
        },
        {
            path: 'users/create',
            component: () => import(/* webpackChunkName: "users" */"../pages/users/UserCreate"),
            name: 'users-create',
            meta: {
                auth: true,
                permission: Permissions.core.CREATE_USERS
            }
        },
        {
            path: 'users/update/:userId',
            component: () => import(/* webpackChunkName: "users" */"../pages/users/UserUpdate"),
            name: 'users-update',
            props: true,
            meta: {
                auth: true
            }
        },
        {
            path: 'roles',
            component: () => import(/* webpackChunkName: "roles" */"../pages/roles/RoleIndex"),
            name: 'roles-index',
            meta: {
                auth: true,
                permission: Permissions.core.READ_ROLES
            }
        },
        {
            path: 'roles/create',
            component: () => import(/* webpackChunkName: "roles" */"../pages/roles/RoleCreate"),
            name: 'roles-create',
            meta: {
                auth: true,
                permission: Permissions.core.CREATE_ROLES
            }
        },
        {
            path: 'roles/update/:roleId',
            component: () => import(/* webpackChunkName: "roles" */"../pages/roles/RoleUpdate"),
            name: 'roles-update',
            meta: {
                auth: true,
                permission: Permissions.core.UPDATE_ROLES
            }
        }
    );
}

if (CONFIG.features.AUTH && CONFIG.features.POSTS) {
    featureArray.push(
        {
            path: 'posts/create',
            component: () => import(/* webpackChunkName: "posts" */"../pages/posts/PostCreate"),
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
            name: 'posts-read',
            props: true
        },
        {
            path: 'posts/update/:postId',
            component: () => import(/* webpackChunkName: "posts" */"../pages/posts/PostUpdate"),
            name: 'posts-update',
            meta: {
                auth: true
                // User ownership is checked at PostUpdate component
                // permission: Permissions.core.UPDATE_ELSES_POSTS
            }
        }
    );
}

if (CONFIG.features.AUTH && CONFIG.features.QUOTES) {
    featureArray.push(
        {
            path: 'quotes',
            component: () => import(/* webpackChunkName: "quotes" */"../pages/contact/QuoteIndex"),
            name: 'quotes-index',
            meta: {
                auth: true
            }
        }
    );
}

const makeChildRoutes = function (pages, errors) {
    var gen = pages.concat(featureArray);
    return gen.concat(errors);
}

export default makeChildRoutes;