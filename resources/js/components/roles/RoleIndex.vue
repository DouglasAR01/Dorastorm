<template>
  <div class="wp-light">
    <div class="row justify-content-center">
      <h3>{{ $t("modules.roles.list") }}</h3>
    </div>
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div class="table-responsive" v-else>
      <table class="table table-hover">
        <thead>
          <th scope="col">{{ $t("message.id") }}</th>
          <th scope="col">{{ $t("message.name") }}</th>
          <th scope="col">{{ $t("message.action") }}</th>
        </thead>
        <tbody>
          <template v-for="role in roles">
            <tr :key="role.id">
              <td>{{ role.id }}</td>
              <td>{{ role.name }}</td>
              <td>
                <button
                  class="btn btn-link"
                  type="button"
                  data-toggle="collapse"
                  :data-target="'#co' + role.id"
                  role="button"
                  aria-haspopup="true"
                  aria-expanded="false"
                  :aria-controls="'co' + role.id"
                  v-tooltip="$t('modules.roles.read')"
                >
                  <i class="far fa-eye"></i>
                </button>
                <router-link
                :to="{
                  name: 'roles-update',
                  params: { roleId: role.id },
                }"
                v-tooltip="$t('modules.roles.update')"
                class="btn btn-link btn-sm"
                v-if="userCanUpdate && userCanTouchThis(role)"
              >
                <i class="fas fa-pen"></i>
              </router-link>
                <button
                  class="btn btn-link btn-sm"
                  @click.prevent="deleteRole(role.id)"
                  :disabled="deleting"
                  v-tooltip="$t('modules.roles.delete')"
                  v-if="userCanDelete && userCanTouchThis(role)"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <tr :key="'co' + role.id">
              <td colspan="3" class="hidden-column">
                <div class="collapse" :id="'co' + role.id">
                  <role-data-card :payload="role"></role-data-card>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
      <confirm-dialogue ref="confirmDialogue"></confirm-dialogue>
    </div>
  </div>
</template>
<script>
import ConfirmDialogue from "../../shared/components/ConfirmDialogue";
import { is404 } from "../../shared/utils/responses";
import Permissions from "../../services/role-permissions";
import RoleDataCard from "./RoleDataCard";
export default {
  components: {
    ConfirmDialogue,
    RoleDataCard,
  },
  data() {
    return {
      loading: false,
      deleting: false,
      roles: null,
    };
  },
  async created() {
    this.loading = true;
    try {
      this.roles = (await axios.get("/api/roles")).data.data;
    } catch (error) {
      this.$toast.error(this.$t("error.fatal"));
    }
    this.loading = false;
  },
  computed: {
    userCanUpdate() {
      return Permissions.checkUserPermission(
        this.$store.state.user,
        Permissions.core.UPDATE_ROLES
      );
    },
    userCanDelete() {
      return Permissions.checkUserPermission(
        this.$store.state.user,
        Permissions.core.DELETE_ROLES
      );
    },
  },
  methods: {
    userCanTouchThis(role) {
      return this.$store.getters.getUserHierarchy < role.hierarchy;
    },
    async deleteRole(role_id) {
      const ok = await this.$refs.confirmDialogue.show({
        title: this.$t("modules.roles.delete"),
        message: this.$t("modules.roles.delete_warning"),
        okButton: this.$t("message.delete"),
        okButtonColor: "btn-danger"
      });
      if (ok) {
        this.deleting = true;
        try {
          await axios.delete("/api/roles/" + role_id);
          this.roles = this.roles.filter((role) => role.id != role_id);
          this.$toasts.success(this.$t("modules.roles.deleted"));
        } catch (error) {
          if (is404(error)) {
            this.$toasts.error(this.$t("error.404.specific.role"));
          }
        }
        this.deleting = false;
      }
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