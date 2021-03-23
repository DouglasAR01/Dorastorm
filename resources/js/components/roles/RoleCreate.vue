<template>
  <div class="container bg-white rounded p-2">
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="name">{{ $t("modules.roles.name") }}</label>
        <input
          type="text"
          name="name"
          class="form-control"
          v-model="new_role.name"
          :class="[{ 'is-invalid': errorFor('name') }]"
          required
        />
        <validation-error :errors="errorFor('name')"></validation-error>
      </div>
      <div class="form-group">
        <label for="description">{{ $t("modules.roles.description") }}</label>
        <textarea
          name="description"
          class="form-control"
          v-model="new_role.description"
          :class="[{ 'is-invalid': errorFor('description') }]"
          required
        ></textarea>
        <validation-error :errors="errorFor('description')"></validation-error>
      </div>
      <div class="row">
        <div class="col-8">
          <role-permission-selector
            @selected="updateSelected"
          ></role-permission-selector>
        </div>
        <div class="col-4">
          <role-hierarchy-selector
            v-model="new_role.hierarchy"
          ></role-hierarchy-selector>
        </div>
      </div>
      <input type="submit" :value="$t('message.submit')" class="btn btn-primary btn-block mt-2" :disabled="submitting">
    </form>
  </div>
</template>
<script>
import RolePermissionSelector from "./RolePermissionSelector";
import RoleHierarchySelector from "./RoleHierarchySelector";
import ValidationError from "../../shared/components/ValidationError";
import ErrorTraits from "../../shared/mixins/error-traits";
import { is422 } from "../../shared/utils/responses";
export default {
  components: {
    RolePermissionSelector,
    RoleHierarchySelector,
    ValidationError,
  },
  mixins: [ErrorTraits],
  data() {
    return {
      submitting: false,
      new_role: {
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
        await axios.post("/api/roles", this.new_role);
        this.$toasts.success(this.$t("modules.roles.created"));
      } catch (error) {
        if (is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
      this.submitting = false;
    },
    updateSelected(payload) {
      var permissions = {};
      payload.forEach(element => {
        permissions[element] = 1
      });
      this.new_role.permissions = permissions;
    },
  },
};
</script>