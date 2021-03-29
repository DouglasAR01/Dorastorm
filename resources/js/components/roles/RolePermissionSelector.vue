<template>
  <div>
    <h3>{{ $t("modules.roles.select_permissions") }}</h3>
    <div
      class="border-bottom pb-2 mt-2"
      v-for="(content, categorie) in permissionsCategories"
      :key="categorie"
    >
      <h6>{{ categorie }}</h6>
      <div class="custom-control custom-checkbox" v-for="permission in content" :key="permission" @change="selected">
        <input type="checkbox" :id="permission" :value="permission" class="custom-control-input" v-model="selected_permissions">
        <label :for="permission" class="custom-control-label">{{permission}}</label>
      </div>
    </div>
  </div>
</template>
<script>
import Permissions from "../../services/role-permissions";
export default {
  props: {
    preselectedPermissions: {
      type: Array,
      default: function () {
        return [];
      }
    }
  },
  data() {
    return {
      loading: false,
      selected_permissions: this.preselectedPermissions.concat([]),
    };
  },
  computed: {
    permissionsCategories() {
      return Permissions.categorizePermissions(
        this.$store.getters.getUserPermissions
      );
    },
  },
  methods: {
    selected() {
      this.$emit("selected", this.selected_permissions);
    },
  },
};
</script>