<template>
  <div v-if="loading">
    {{ $t("message.loading") }}
  </div>
  <article class="container wp bg-light" v-else>
    <form @submit.prevent="submit">
      <section class="form-group">
        <validation-error :errors="errors" name="name" #default="{ e }">
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
      </section>
      <section class="form-group">
        <validation-error :errors="errors" name="description" #default="{ e }">
          <label for="description">{{ $t("modules.roles.description") }}</label>
          <textarea
            name="description"
            class="form-control"
            v-model="role.description"
            :class="[{ 'is-invalid': e }]"
            required
          ></textarea>
        </validation-error>
      </section>
      <section class="row">
        <div class="col-md-8">
          <role-permission-selector
            :preselected-permissions="initialPermissions"
            @selected="updateSelected"
          ></role-permission-selector>
        </div>
        <div class="col-md-4">
          <role-hierarchy-selector
            v-model="role.hierarchy"
            :rid="role.id"
          ></role-hierarchy-selector>
        </div>
      </section>
      <input
        type="submit"
        :value="$t('message.submit')"
        class="btn btn-primary btn-block mt-2"
        :disabled="submitting"
      />
    </form>
  </article>
</template>
<script>
import { is404, is422 } from "../../shared/utils/responses";
import RolePermissionSelector from "../../components/roles/RolePermissionSelector";
import RoleHierarchySelector from "../../components/roles/RoleHierarchySelector";
import ValidationError from "../../components/ValidationError";
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
      initialPermissions: null,
    };
  },

  async created() {
    this.loading = true;
    if (!("roleId" in this.$route.params)) {
      this.$toasts.error(this.$t("error.fatal"));
      return;
    }
    const role_id = this.$route.params.roleId;
    try {
      this.role = (await axios.get("/api/roles/" + role_id)).data.data;
      this.initialPermissions = this.role.permissions;
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
      this.initialPermissions.forEach((element) => {
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
          params: { locale: this.$route.params.locale },
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