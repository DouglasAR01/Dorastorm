<template>
  <div class="container wp bg-light">
    <div class="row justify-content-center">
      <h3>{{ $t("modules.users.list") }}</h3>
    </div>
    <div v-if="loading">{{ $t("message.loading") }}</div>
    <div v-else>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">{{ $t("message.id") }}</th>
              <th scope="col">{{ $t("message.name") }}</th>
              <th scope="col">{{ $t("message.email") }}</th>
              <th scope="col">{{ $t("modules.users.role") }}</th>
              <th scope="col">{{ $t("message.action") }}</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="user in data">
              <tr :key="user.id">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.role.name }}</td>
                <td>
                  <button
                    class="btn btn-link"
                    type="button"
                    data-toggle="collapse"
                    :data-target="'#co' + user.id"
                    role="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    :aria-controls="'co' + user.id"
                    v-tooltip="$t('modules.users.read')"
                  >
                    <i class="far fa-eye"></i>
                  </button>
                  <router-link
                    :to="{
                      name: 'users-update',
                      params: { userId: user.id },
                    }"
                    v-tooltip="$t('modules.users.update')"
                    class="btn btn-link btn-sm"
                    v-if="userCanUpdate && userCanTouchThis(user)"
                  >
                    <i class="fas fa-pen"></i>
                  </router-link>
                  <button
                    class="btn btn-link btn-sm"
                    @click.prevent="deleteUser(user.id)"
                    :disabled="deleting"
                    v-tooltip="$t('modules.users.delete')"
                    v-if="userCanDelete && userCanTouchThis(user)"
                  >
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
              <tr :key="'co' + user.id">
                <td colspan="5" class="hidden-column">
                  <div class="collapse" :id="'co' + user.id">
                    <user-data-card
                      :user="user"
                      :allow-edit="userCanDelete && userCanTouchThis(user)"
                    ></user-data-card>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      <simple-pagination
        :meta="meta"
        :links="links"
        @navigating="navigate"
      ></simple-pagination>
      <confirm-dialogue-modal ref="confirmDialogue"></confirm-dialogue-modal>
    </div>
  </div>
</template>
<script>
import ConfirmDialogueModal from "../../shared/components/modals/ConfirmDialogueModal";
import { is404, is409 } from "../../shared/utils/responses";
import Permissions from "../../services/role-permissions";
import UserDataCard from "./UserDataCard";
import SimplePagination from "../../shared/components/SimplePagination";
import IndexPaginationTraits from "../../shared/mixins/index-pagination-traits";
export default {
  components: {
    ConfirmDialogueModal,
    UserDataCard,
    SimplePagination,
  },
  mixins: [IndexPaginationTraits],
  data() {
    return {
      deleting: false,
    };
  },
  created() {
    this.ep = "/api/users";
    this.params = {
      q: null,
    };
  },
  methods: {
    async deleteUser(user_id) {
      const ok = await this.$refs.confirmDialogue.show({
        title: this.$t("modules.users.delete"),
        message: this.$t("modules.users.delete_warning"),
        okButton: this.$t("message.delete"),
        okButtonColor: "btn-danger",
      });
      // If you throw an error, the method will terminate here unless you surround it wil try/catch
      if (ok) {
        this.deleting = true;
        try {
          await axios.delete("/api/users/" + user_id);
          this.data = this.data.filter((user) => user.id != user_id);
          this.$toasts.success(this.$t("modules.users.deleted"));
        } catch (error) {
          if (is404(error)) {
            this.$toasts.error(this.$t("error.404.specific.user"));
          }
          if (is409(error)) {
            this.$toasts.error(this.$("error.409.specific.last_admin"));
          }
        }
        this.deleting = false;
      }
    },
    userCanTouchThis(another_user) {
      return (
        this.$store.getters.getUserHierarchy < another_user.role.hierarchy ||
        this.$store.getters.getUserHierarchy === 0
      );
    },
  },
  computed: {
    userCanUpdate() {
      return Permissions.checkUserPermission(
        this.$store.state.user,
        Permissions.core.UPDATE_USERS
      );
    },
    userCanDelete() {
      return Permissions.checkUserPermission(
        this.$store.state.user,
        Permissions.core.DELETE_USERS
      );
    },
  },
};
</script>
<style scoped>
.hidden-column {
  border-top: 0px;
  padding: 0 !important;
  margin-top: 0 !important;
}
</style>