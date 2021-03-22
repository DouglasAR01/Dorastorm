<template>
  <div class="container rounded bg-white p-2">
    <div class="row justify-content-center">
      <h3>{{ $t("modules.users.list") }}</h3>
    </div>
    <div v-if="loading">{{ $t("message.loading") }}</div>
    <div class="table-responsive" v-else>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">{{ $t("message.id") }}</th>
            <th scope="col">{{ $t("modules.users.name2") }}</th>
            <th scope="col">{{ $t("message.email") }}</th>
            <th scope="col">{{ $t("modules.users.role") }}</th>
            <th scope="col">{{ $t("message.action") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role.name }}</td>
            <td>
              <router-link
                :to="{
                  name: 'users-read',
                  query: { user_id: user.id },
                }"
                data-toggle="tooltip"
                data-placement="top"
                :title="$t('modules.users.read')"
                class="btn btn-link btn-sm"
              >
                <i class="far fa-eye"></i>
              </router-link>
              <router-link
                :to="{
                  name: 'users-update',
                  query: { user_id: user.id },
                }"
                data-toggle="tooltip"
                data-placement="top"
                :title="$t('modules.users.update')"
                class="btn btn-link btn-sm"
                v-if="userCanUpdate && userCanTouchThis(user)"
              >
                <i class="fas fa-pen"></i>
              </router-link>
              <button
                class="btn btn-link btn-sm"
                @click.prevent="deleteUser(user.id)"
                :disabled="deleting"
                data-toggle="tooltip"
                data-placement="top"
                :title="$t('modules.users.delete')"
                v-if="userCanDelete && userCanTouchThis(user)"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <confirm-dialogue ref="confirmDialogue"></confirm-dialogue>
    </div>
  </div>
</template>
<script>
import ConfirmDialogue from "../../shared/components/ConfirmDialogue";
import { is404, is409 } from "../../shared/utils/responses";
import Permissions from "../../services/role-permissions";
export default {
  components: {
    ConfirmDialogue,
  },
  data() {
    return {
      loading: false,
      deleting: false,
      users: null,
    };
  },
  async created() {
    try {
      this.loading = true;
      this.users = (await axios.get("/api/users")).data.data;
      this.loading = false;
    } catch (error) {
      this.$toast.error($t("error.fatal"));
    }
  },
  methods: {
    async deleteUser(user_id) {
      const ok = await this.$refs.confirmDialogue.show({
        title: "Delete User",
        message:
          this.$t("modules.users.delete_warning"),
        okButton: this.$t("message.delete"),
      });
      // If you throw an error, the method will terminate here unless you surround it wil try/catch
      if (ok) {
        this.deleting = true;
        try {
          await axios.delete("/api/users/" + user_id);
          this.loading = false;
          this.users = this.users.filter((user) => user.id != user_id);
          this.$toasts.success($t("modules.users.deleted"));
        } catch (error) {
          if (is404(error)) {
            this.$toasts.error($t("error.404.specific.user"));
          }
          if (is409(error)) {
            this.$toasts.error($("error.409.specific.last_admin"));
          }
        }
        this.deleting = false;
      }
    },
    userCanTouchThis(another_user){
      return this.$store.getters.getUserHierarchy < another_user.role.hierarchy || this.$store.getters.getUserHierarchy===0;
    }
  },
  computed: {
    userCanUpdate() {
      return Permissions.checkUserPermission(this.$store.state.user, Permissions.core.UPDATE_USERS);
    },
    userCanDelete() {
      return Permissions.checkUserPermission(this.$store.state.user, Permissions.core.DELETE_USERS);
    }
  }
};
</script>