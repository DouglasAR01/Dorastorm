<template>
  <div class="container rounded bg-white p-3">
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="name">User name:</label>
        <input
          type="text"
          name="name"
          class="form-control"
          v-model="new_user.name"
          :class="[{ 'is-invalid': errorFor('name') }]"
          required
        />
        <validation-error :errors="errorFor('name')"></validation-error>
      </div>
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input
          type="email"
          name="email"
          class="form-control"
          v-model="new_user.email"
          :class="[{ 'is-invalid': errorFor('email') }]"
          required
        />
        <validation-error :errors="errorFor('email')"></validation-error>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input
          type="password"
          name="password"
          class="form-control"
          v-model="new_user.password"
          :class="[{ 'is-invalid': errorFor('password') }]"
          required
        />
        <validation-error :errors="errorFor('password')"></validation-error>
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm password:</label>
        <input
          type="password"
          name="confirm_password"
          class="form-control"
          v-model="new_user.password_confirmation"
          :class="[{ 'is-invalid': errorFor('password_confirmation') }]"
          required
        />
        <validation-error :errors="errorFor('password_confirmation')"></validation-error>
        <small v-if="!fieldConfirmed(new_user.password, new_user.password_confirmation)" class="text-danger">The passwords doesn't match</small>
        
      </div>
      <div class="form-group">
        <div v-if="!loading">
          <label for="role_id">Select the user role</label>
          <select
            name="role_id"
            class="custom-select form-control"
            v-model="new_user.role_id"
            :class="[{ 'is-invalid': errorFor('role_id') }]"
            required
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
          <validation-error :errors="errorFor('role_id')"></validation-error>
        </div>
      </div>
      <input type="submit" value="Create user" class="btn btn-primary btn-block" :disabled="
          loading ||
          submitting ||
          !fieldConfirmed(new_user.password, new_user.password_confirmation)
        ">
    </form>
  </div>
</template>
<script>
import { is422 } from "../../shared/utils/responses";
import ErrorTraits from "../../shared/mixins/error-traits";
import FormTraits from "../../shared/mixins/form-traits";
import ValidationError from "../../shared/components/ValidationError";
import Obj from "../../shared/utils/object-utils";
export default {
  mixins: [ErrorTraits, FormTraits],
  components: {
    ValidationError,
  },
  data() {
    return {
      loading: false,
      submitting: false,
      available_roles: null,
      new_user: {
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
        role_id: "",
      },
    };
  },
  async created() {
    this.form_initial_state = Obj.clone(this.new_user);
    try {
      this.loading = true;
      this.available_roles = (await axios.get("/api/roles/below")).data.data;
      this.loading = false;
    } catch (error) {
      this.$toasts.error("Something went wrong. Try again later.");
    }
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        let user = (await axios.post("/api/users", this.new_user)).data;
        this.submitting = false;
        this.$toasts.success("User created!");
        this.new_user = Obj.clone(this.form_initial_state);
        // Send to UserView
      } catch (error) {
        if (is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
      this.submitting = false;
    },
  },
};
</script>