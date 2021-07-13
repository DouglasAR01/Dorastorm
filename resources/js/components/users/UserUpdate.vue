<template>
  <div class="wp-light">
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
                v-model="updatedUser.name"
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
                v-model="updatedUser.email"
                :class="[{ 'is-invalid': e }]"
                required
              />
            </validation-error>
          </div>
          <div
            class="form-group"
            v-if="
              checkUserPermission(loggedUser, corePms.UPDATE_USERS) &&
              availableRoles.length > 0
            "
          >
            <validation-error :errors="errors" name="role_id" v-slot="{ e }">
              <label for="role_id">{{ $t("modules.users.role_select") }}</label>
              <select
                name="role_id"
                class="custom-select form-control"
                v-model="updatedUser.role.id"
                :class="[{ 'is-invalid': e }]"
                required
              >
                <option disabled value="">
                  {{ $t("modules.users.role_default") }}
                </option>
                <option
                  :value="role.id"
                  v-for="role in availableRoles"
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
          :class="[{ 'd-none': changingPassword }]"
        >
          {{ $t("modules.users.change_password") }}
        </button>
        <div v-if="changingPassword">
          <user-password-update
            :user-id="userId"
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
      changingPassword: false,
      availableRoles: null,
      updatedUser: null,
      userId: null,
    };
  },
  async created() {
    this.loading = true;
    this.userId = this.$store.getters.getUserID;
    this.updatedUser = Obj.clone(this.$store.state.user);
    if ("userId" in this.$route.params) {
      this.userId = this.$route.params.userId;
    }
    if (this.userId != this.updatedUser.id) {
      try {
        this.updatedUser = (
          await axios.get("/api/users/" + this.userId)
        ).data.data;
      } catch (error) {
        this.success = false;
        if (Responses.is404(error)) {
          this.$toasts.error($t("error.404.specific.user"));
        }
      }
    }
    // Improve
    this.availableRoles = (await Auth.userRolesBelow()).data.data;
    this.loading = false;
  },
  methods: {
    changePassword() {
      this.changingPassword = !this.changingPassword;
    },
    async submit() {
      this.submiting = true;
      this.updatedUser.role_id = this.updatedUser.role.id;
      try {
        await axios.patch("/api/users/" + this.userId, this.updatedUser);
        this.$toasts.success(this.$t("message.data_changed"));
        if (this.userId === this.$store.getters.getUserID) {
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