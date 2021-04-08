<template>
  <div class="container rounded bg-white p-2">
    <div v-if="loading">{{ $t("message.loading") }}</div>
    <div v-else>
      <div v-if="!success">{{ $t("error.fatal") }}</div>
      <div v-else>
        <h3>{{ $t("modules.users.user_info") }}</h3>
        <form @submit.prevent="submit">
          <div class="form-group">
            <validation-error :errors="errors" name="name" v-slot="{ e }">
              <label for="name">{{ $t("modules.users.name") }}</label>
              <input
                type="text"
                name="name"
                class="form-control"
                v-model="updated_user.name"
                :class="[{ 'is-invalid': e }]"
                required
              />
            </validation-error>
          </div>
          <div class="form-group">
            <validation-error :errors="errors" name="email" v-slot="{ e }">
              <label for="email">{{ $t("message.email") }}</label>
              <input
                type="email"
                name="email"
                class="form-control"
                v-model="updated_user.email"
                :class="[{ 'is-invalid': e }]"
                required
              />
            </validation-error>
          </div>
          <div
            class="form-group"
            v-if="
              checkUserPermission(loggedUser, corePms.UPDATE_USERS) &&
              available_roles.length > 0
            "
          >
            <validation-error :errors="errors" name="role_id" v-slot="{ e }">
              <label for="role_id">{{ $t("modules.users.role_select") }}</label>
              <select
                name="role_id"
                class="custom-select form-control"
                v-model="updated_user.role_id"
                :class="[{ 'is-invalid': e }]"
                required
              >
                <option disabled value="">
                  {{ $t("modules.users.role_default") }}
                </option>
                <option
                  :value="role.id"
                  v-for="role in available_roles"
                  :key="role.id"
                >
                  {{ role.name }}
                </option>
              </select>
            </validation-error>
          </div>
          <input
            type="submit"
            :value="$t('modules.users.update')"
            class="btn btn-primary btn-block"
            :disabled="submiting"
          />
        </form>
        <hr />
        <button
          class="btn btn-warning"
          @click.prevent="changePassword"
          :class="[{ 'd-none': changing_password }]"
        >
          {{ $t("modules.users.change_password") }}
        </button>
        <div v-if="changing_password">
          <user-password-update
            :user_id="user_id"
            @cancel="changePassword"
          ></user-password-update>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as Responses from "../../shared/utils/responses";
import Obj from "../../shared/utils/object-utils";
import FormTraits from "../../shared/mixins/form-traits";
import ValidationError from "../../shared/components/ValidationError";
import PermissionsHandling from "../../shared/mixins/permissions-handling";
import Auth from "../../services/auth";
import UserPasswordUpdate from "./UserPasswordUpdate";
export default {
  mixins: [FormTraits, PermissionsHandling],
  components: {
    ValidationError,
    UserPasswordUpdate,
  },
  data() {
    return {
      loading: false,
      errors: null,
      success: true,
      submiting: false,
      changing_password: false,
      available_roles: null,
      updated_user: null,
      user_id: null,
    };
  },
  async created() {
    this.loading = true;
    this.user_id = this.$store.getters.getUserID;
    this.updated_user = Obj.clone(this.$store.state.user);
    if ("userId" in this.$route.params) {
      this.user_id = this.$route.params.userId;
    }
    if (this.user_id != this.updated_user.id) {
      try {
        this.updated_user = (
          await axios.get("/api/users/" + this.user_id)
        ).data.data;
      } catch (error) {
        this.success = false;
        if (Responses.is404(error)) {
          this.$toasts.error($t("error.404.specific.user"));
        }
      }
    }
    // Improve
    this.available_roles = (await Auth.userRolesBelow()).data.data;
    this.loading = false;
  },
  methods: {
    changePassword() {
      this.changing_password = !this.changing_password;
    },
    async submit() {
      this.submiting = true;
      this.updated_user.role_id = this.updated_user.role.id;
      try {
        await axios.patch("/api/users/" + this.user_id, this.updated_user);
        this.$toasts.success(this.$t("message.data_changed"));
        if (this.user_id === this.$store.getters.getUserID) {
          let user = await Auth.loadUser();
          this.$store.commit("setUser", user);
        }
      } catch (error) {
        if (Responses.is404(error)) {
          this.$toasts.error(this.$t("error.404.specific.user"));
        }
        if (Responses.is409(error)) {
          this.$toasts.error(this.$t("error.409.specific.last_admin"));
        }
        if (Responses.is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
      this.submiting = false;
    },
  },
};
</script>