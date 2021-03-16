<template>
  <div class="container rounded bg-white p-2">
    <h2>{{ $t("message.reset_password") }}</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="email">{{ $t("message.email") }}</label>
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
        <label for="password">{{ $t("message.new_password") }}</label>
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
        <label for="cpassword">{{ $t("message.confirm_password") }}</label>
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
        :value="$t('message.submit')"
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
        this.$toasts.error($t("error.fatal"));
      }
      try {
        Auth.resetPassword(this.payload);
        this.$toasts.success($t("modules.users.password_changed"));
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