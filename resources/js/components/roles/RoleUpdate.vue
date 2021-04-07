<template>
  <div v-if="loading">
    {{ $t("message.loading") }}
  </div>
  <div class="container bg-white rounded p-2" v-else>
    <form @submit.prevent="submit">
      <div class="form-group">
        <validation-error :errors="errors" name="name" v-slot="{ e }">
          <label for="name">{{ $t("modules.roles.name") }}</label>
          <input
            type="text"
            name="name"
            class="form-control"
            v-model="role.name"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error :errors="errors" name="description" v-slot="{ e }">
          <label for="description">{{ $t("modules.roles.description") }}</label>
          <textarea
            name="description"
            class="form-control"
            v-model="role.description"
            :class="[{ 'is-invalid': e }]"
            required
          ></textarea>
        </validation-error>
      </div>
      <div class="row">
        <div class="col-md-8">
          <role-permission-selector
            :preselected-permissions="initial_permissions"
            @selected="updateSelected"
          ></role-permission-selector>
        </div>
        <div class="col-md-4">
          <role-hierarchy-selector
            v-model="role.hierarchy"
            :rid="role.id"
          ></role-hierarchy-selector>
        </div>
      </div>
      <input
        type="submit"
        :value="$t('message.submit')"
        class="btn btn-primary btn-block mt-2"
        :disabled="submitting"
      />
    </form>
  </div>
</template>
<script>
import { is404, is422 } from "../../shared/utils/responses";
import Obj from "../../shared/utils/object-utils";
import RolePermissionSelector from "./RolePermissionSelector";
import RoleHierarchySelector from "./RoleHierarchySelector";
import ValidationError from "../../shared/components/ValidationError";
export default {
  components: {
    RolePermissionSelector,
    RoleHierarchySelector,
    ValidationError,
  },

  data() {
    return {
      errors: null,
      loading: false,
      submitting: false,
      role: null,
      initial_permissions: null,
    };
  },

  async created() {
    this.loading = true;
    const role_id = this.$route.query.role_id;
    if (Obj.isEmpty(this.$route.query) || role_id == null) {
      this.$toasts.error(this.$t("error.fatal"));
      return;
    }
    try {
      this.role = (await axios.get("/api/roles/" + role_id)).data.data;
      this.initial_permissions = this.role.permissions;
    } catch (error) {
      if (is404(error)) {
        this.$toasts.error(this.$t("error.404.specific.role"));
      }
    }
    this.loading = false;
  },

  methods: {
    updateSelected(payload) {
      this.role.permissions = payload;
    },
    preparePermissionsForSubmit() {
      var permissions = {};
      this.role.permissions.forEach((element) => {
        permissions[element] = 1;
      });
      this.initial_permissions.forEach((element) => {
        if (!(element in permissions)) {
          permissions[element] = 0;
        }
      });
      return permissions;
    },
    async submit() {
      this.submitting = true;
      try {
        const modified_role = {
          name: this.role.name,
          hierarchy: this.role.hierarchy,
          description: this.role.description,
          permissions: this.preparePermissionsForSubmit(),
        };
        await axios.patch("/api/roles/" + this.role.id, modified_role);
        this.$toasts.success(this.$t("message.data_changed"));
        this.$router.push({
          name: "roles-index",
        });
      } catch (error) {
        if (is404(error)) {
          this.$toasts.error(this.$t("error.404.specific.role"));
        }
        if (is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
      this.submitting = false;
    },
  },
};
</script>