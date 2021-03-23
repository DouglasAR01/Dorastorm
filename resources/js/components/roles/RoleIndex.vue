<template>
  <div class="container bg-white rounded p-2">
    <div class="row justify-content-center">
      <h3>{{ $t("modules.roles.list") }}</h3>
    </div>
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div class="table-responsive" v-else>
      <table class="table table-striped table-hover">
        <thead>
          <th scope="col">{{ $t("message.id") }}</th>
          <th scope="col">{{ $t("message.name") }}</th>
          <th scope="col">{{ $t("message.action") }}</th>
        </thead>
        <tbody>
          <tr v-for="role in roles" :key="role.id">
            <td>{{ role.id }}</td>
            <td>{{ role.name }}</td>
            <td>
              <!-- <router-link
                :to="{
                  name: 'roles-read',
                  query: { role_id: role.id },
                }"
                data-toggle="tooltip"
                data-placement="top"
                :title="$t('modules.roles.read')"
                class="btn btn-link btn-sm"
              >
                <i class="far fa-eye"></i>
              </router-link> -->
              <!-- <router-link
                :to="{
                  name: 'roles-update',
                  query: { role_id: role.id },
                }"
                data-toggle="tooltip"
                data-placement="top"
                :title="$t('modules.roles.update')"
                class="btn btn-link btn-sm"
                v-if="userCanUpdate && userCanTouchThis(role)"
              >
                <i class="fas fa-pen"></i>
              </router-link> -->
              <button
                class="btn btn-link btn-sm"
                @click.prevent="deleteRole(role.id)"
                :disabled="deleting"
                data-toggle="tooltip"
                data-placement="top"
                :title="$t('modules.roles.delete')"
                v-if="userCanDelete && userCanTouchThis(role)"
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
import { is404 } from "../../shared/utils/responses";
import Permissions from "../../services/role-permissions";
export default {
  components: {
    ConfirmDialogue
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