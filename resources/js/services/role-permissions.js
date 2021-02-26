const permissions = {
    core: {
        CREATE_USERS: 'create_users',
        READ_USERS: 'read_users',
        UPDATE_USERS: 'update_users',
        DELETE_USERS: 'delete_users',

        // Post related
        CREATE_POSTS: 'create_posts',
        UPDATE_ELSES_POSTS: 'update_elses_posts',
        DELETE_ELSES_POSTS: 'delete_elses_posts',

        // Comments related
        UPDATE_ELSES_COMMENTS: 'update_elses_comments',
        DELETE_ELSES_COMMENTS: 'delete_elses_comments',

        // Roles related
        CREATE_ROLES: 'create_roles',
        READ_ROLES: 'read_roles',
        UPDATE_ROLES: 'update_roles',
        DELETE_ROLES: 'delete_roles',
    },
    extended: {

    },

    checkUserPermission: function (user, permission) {
        if (user && user.role && user.role.permissions) {
            return user.role.permissions.includes(permission);
        }
        return false;
    }
}
export default permissions;