import Permissions from "../../services/role-permissions";
import { mapState } from "vuex";
export default {
    methods: {
        checkUserPermission(user, permission) {
            return Permissions.checkUserPermission(user, permission);
        },
        checkUserAnyPermission(user, permissions_array) {
            return Permissions.checkUserAnyPermission(user, permissions_array);
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