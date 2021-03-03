import Permissions from "../../services/role-permissions";
export default {
    methods: {
        checkUserPermission(user, permission) {
            return Permissions.checkUserPermission(user, permission);
        },
        checkUserAnyPermission(user, permissions_array) {
            return permissions_array.some(permission => 
                Permissions.checkUserPermission(user, permission))
        }
    },
    computed: {
        corePms() {
            return Permissions.core;
        },
        extPms() {
            return Permissions.extended;
        }
    }
}