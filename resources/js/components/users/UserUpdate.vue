<template>
  <div class="container rounded bg-white p-2">
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div v-if="!success">Fatal error</div>
      <div v-else>
        <h3>Personal information</h3>
        <form @submit.prevent="submit">
          <div class="form-group">
            <label for="name">User name:</label>
            <input
              type="text"
              name="name"
              class="form-control"
              :placeholder="updated_user.name"
              v-model="updated_user.name"
              required
            />
          </div>
          <div class="form-group">
            <label for="email">User E-mail:</label>
            <input
              type="email"
              name="email"
              class="form-control"
              :placeholder="updated_user.email"
              v-model="updated_user.email"
              required
            />
          </div>
          <div
            class="form-group"
            v-if="
              checkUserPermission(loggedUser, corePms.UPDATE_USERS) &&
              available_roles.length > 0
            "
          >
            <label for="role_id">Select the user role</label>
            <select
              name="role_id"
              class="custom-select form-control"
              v-model="updated_user.role.id"
            >
              <option disabled value="">Select a role</option>
              <option
                :value="role.id"
                v-for="role in available_roles"
                :key="role.id"
              >
                {{ role.name }}
              </option>
            </select>
          </div>
          <input
            type="submit"
            value="Update"
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
          Change password
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
import ErrorTraits from "../../shared/mixins/error-traits";
import ValidationError from "../../shared/components/ValidationError";
import PermissionsHandling from "../../shared/mixins/permissions-handling";
import Auth from "../../services/auth";
import UserPasswordUpdate from "./UserPasswordUpdate";
export default {
  mixins: [FormTraits, ErrorTraits, PermissionsHandling],
  components: {
    ValidationError,
    UserPasswordUpdate,
  },
  data() {
    return {
      loading: false,
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
    if (!Obj.isEmpty(this.$route.query) && this.$route.query.user_id) {
      this.user_id = this.$route.query.user_id;
    }
    if (this.user_id != this.updated_user.id) {
      try {
        this.updated_user = (
          await axios.get("/api/users/" + this.user_id)
        ).data.data;
      } catch (error) {
        this.success = false;
        if (Responses.is404(error)) {
          this.$toasts.error("We couldn't find the specified user.");
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
        this.$toasts.success("The changes were made successfully.");
        if (this.user_id === this.$store.getters.getUserID) {
          let user = await Auth.loadUser();
          this.$store.commit("setUser", user);
        }
      } catch (error) {
        if (Responses.is404(error)) {
          this.$toasts.error("We couldn't find the specified user.");
        }
        if (Responses.is406(error)) {
          this.$toasts.error(
            "You are the last admin left. You can not change your role."
          );
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