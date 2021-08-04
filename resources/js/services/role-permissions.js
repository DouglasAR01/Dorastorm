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

        //Quotes related
        READ_QUOTES: 'read_quotes',
        DELETE_QUOTES: 'delete_quotes'
    },
    extended: {

    },

    checkUserPermission: function (user, permission) {
        if (user && user.role && user.role.permissions) {
            return user.role.permissions.includes(permission);
        }
        return false;
    },
    checkUserAnyPermission: function (user, permissions_array) {
        return permissions_array.some(permission =>
            this.checkUserPermission(user, permission));
    },
    categorizePermissions: function (permissions_array) {
        const regex = /(?<=([a-z]+_)+)([a-z]+)$/g;
        var result = {
            OTHERS: []
        };
        permissions_array.forEach(element => {
            if (regex.test(element)){
                var match = element.match(regex)[0].toUpperCase();
                if (!(match in result)){
                    result[match] = [];
                }
                result[match].push(element);
            } else {
                result.OTHERS.push(element);
            }
        });
        if (result.OTHERS.length<1){
            delete result.OTHERS;
        }
        return result;
    }
}
export default permissions;