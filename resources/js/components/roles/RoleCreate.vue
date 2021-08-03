<template>
  <div class="container wp bg-light">
    <form @submit.prevent="submit">
      <div class="form-group">
        <validation-error :errors="errors" name="name" #default="{ e }">
          <label for="name">{{ $t("modules.roles.name") }}</label>
          <input
            type="text"
            name="name"
            class="form-control"
            v-model="newRole.name"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error :errors="errors" name="description" #default="{ e }">
          <label for="description">{{ $t("modules.roles.description") }}</label>
          <textarea
            name="description"
            class="form-control"
            v-model="newRole.description"
            :class="[{ 'is-invalid': e }]"
            required
          ></textarea>
        </validation-error>
      </div>
      <div class="form-row">
        <div class="col-md-8">
          <role-permission-selector
            @selected="updateSelected"
          ></role-permission-selector>
        </div>
        <div class="col-md-4">
          <role-hierarchy-selector
            v-model="newRole.hierarchy"
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
import RolePermissionSelector from "./RolePermissionSelector";
import RoleHierarchySelector from "./RoleHierarchySelector";
import ValidationError from "../../shared/components/ValidationError";
import { is422 } from "../../shared/utils/responses";
export default {
  components: {
    RolePermissionSelector,
    RoleHierarchySelector,
    ValidationError,
  },
  data() {
    return {
      submitting: false,
      errors: null,
      newRole: {
        name: null,
        description: null,
        hierarchy: null,
        permissions: null,
      },
    };
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        await axios.post("/api/roles", this.newRole);
        this.$toasts.success(this.$t("modules.roles.created"));
        this.$router.push({
          name: "roles-index",
        });
      } catch (error) {
        if (is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
      this.submitting = false;
    },
    updateSelected(payload) {
      var permissions = {};
      payload.forEach((element) => {
        permissions[element] = 1;
      });
      this.newRole.permissions = permissions;
    },
  },
};
</script>