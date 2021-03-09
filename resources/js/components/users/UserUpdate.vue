<template>
  <div class="container rounded bg-white p-2">
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div v-if="!success">Fatal error</div>
      <div v-else>
        <h3>Personal information</h3>
        <form>
          <div class="form-group">
            <label for="name">User name:</label>
            <input
              type="text"
              name="name"
              class="form-control"
              :placeholder="updated_user.name"
              v-model="updated_user.name"
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
            />
          </div>
          <div class="form-group" v-if="checkUserPermission(updated_user, corePms.UPDATE_USERS) && available_roles.length > 0">
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
          <button
            class="btn btn-primary btn-block"
            @click.prevent="submit"
            :disabled="submiting"
          >
            Update
          </button>
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
          <form>
            <div class="form-group">
              <label for="cpassword">Current password</label>
              <input
                type="password"
                name="cpassword"
                class="form-control"
                v-model="upd_user_password.current_password"
              />
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input
                type="password"
                name="password"
                class="form-control"
                v-model="upd_user_password.password"
              />
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm password</label>
              <input
                type="password"
                name="confirm_password"
                class="form-control"
                v-model="confirmation_field"
              />
              <small
                v-if="
                  !fieldConfirmed(
                    upd_user_password.password,
                    confirmation_field
                  )
                "
                class="text-danger"
                >The passwords doesn't match</small
              >
            </div>
          </form>
          <button
            class="btn btn-primary mr-1"
            @click.prevent="submitPassword"
            :disabled="
              loading ||
              submiting ||
              !fieldConfirmed(upd_user_password.password, confirmation_field)
            "
          >
            Update password
          </button>
          <button class="btn btn-danger" @click.prevent="changePassword">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as Responses from "../../shared/utils/responses";
import clone from "../../shared/utils/object-clone";
import FormTraits from "../../shared/mixins/form-traits";
import ErrorTraits from "../../shared/mixins/error-traits";
import PermissionsHandling from "../../shared/mixins/permissions-handling";
import {loadUser} from "../../services/auth";
export default {
  mixins: [FormTraits, ErrorTraits, PermissionsHandling],
  data() {
    return {
      loading: false,
      success: true,
      submiting: false,
      changing_password: false,
      available_roles: null,
      updated_user: null,
      user_id: null,
      upd_user_password: {
        id: null,
        password: null,
        current_password: null,
      },
    };
  },
  async created() {
    this.loading = true;
    this.user_id = this.$store.getters.getUserID;
    this.updated_user = clone(this.$store.state.user);

    if (this.$route.query && this.$route.query.user_id) {
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
    this.available_roles = (await axios.get("/api/roles/below")).data.data;
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
          let user = await loadUser();
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
    async submitPassword() {
      this.submiting = true;
      this.upd_user_password.id = this.updated_user.id;
      try {
        await axios.patch("/api/users/" + this.user_id, this.upd_user_password);
        this.$toasts.success("The changes were made successfully.");
      } catch (error) {
        if (Responses.is404(error)) {
          this.$toasts.error("We couldn't find the specified user.");
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