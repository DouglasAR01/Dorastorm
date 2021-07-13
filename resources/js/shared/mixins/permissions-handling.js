import Permissions from "../../services/role-permissions";
import { mapState } from "vuex";
export default {
    methods: {
        checkUserPermission(user, permission) {
            return Permissions.checkUserPermission(user, permission);
        },
        checkUserAnyPermission(user, permissionsArray) {
            return Permissions.checkUserAnyPermission(user, permissionsArray);
        }
    },
    computed: {
        ...mapState({
            isLoggedIn: (state) => state.isLoggedIn,
            loggedUser: (state) => state.user,
        }),
        corePms() {
            return Permissions.core;
        },
        extPms() {
            return Permissions.extended;
        }
    }
}