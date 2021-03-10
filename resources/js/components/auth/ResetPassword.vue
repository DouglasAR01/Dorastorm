<template>
  <div class="container rounded bg-white p-2">
    <h2>Reset password</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input
          type="email"
          name="email"
          class="form-control"
          v-model="payload.email"
          :class="[{ 'is-invalid': errorFor('email') }]"
          required
        />
        <validation-error :errors="errorFor('email')"></validation-error>
      </div>
      <div class="form-group">
        <label for="password">New password:</label>
        <input
          type="password"
          name="password"
          class="form-control"
          v-model="payload.password"
          :class="[{ 'is-invalid': errorFor('password') }]"
          required
        />
        <validation-error :errors="errorFor('password')"></validation-error>
      </div>
      <div class="form-group">
        <label for="cpassword">Confirm password:</label>
        <input
          type="password"
          name="cpassword"
          class="form-control"
          v-model="payload.password_confirmation"
          :class="[{ 'is-invalid': errorFor('password_confirmation') }]"
          required
        />
        <validation-error
          :errors="errorFor('password_confirmation')"
        ></validation-error>
      </div>
      <input
        type="submit"
        class="btn btn-primary btn-block"
        :disabled="submitting"
      />
    </form>
  </div>
</template>
<script>
import ValidationError from "../../shared/components/ValidationError";
import ErrorTraits from "../../shared/mixins/error-traits";
import Auth from "../../services/auth";
import { is422 } from "../../shared/utils/responses";
export default {
  mixins: [ErrorTraits],
  components: {
    ValidationError,
  },
  data() {
    return {
      submitting: false,
      payload: {
        email: null,
        password: null,
        password_confirmation: null,
        token: this.$route.query.token,
      },
    };
  },

  methods: {
    async submit() {
      this.submitting = true;
      try {
        Auth.getCsrfCookie();
      } catch (error) {
        this.$toasts.error("Something very wrong happened. Try again later.");
      }
      try {
        Auth.resetPassword(this.payload);
        this.$toasts.success("Your password was successfully changed.");
        this.$router.push({
          name: "login",
        });
      } catch (error) {
        if (is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
    },
  },
};
</script>