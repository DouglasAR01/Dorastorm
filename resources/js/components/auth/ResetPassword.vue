<template>
  <div class="wp-light">
    <h2>{{ $t("message.reset_password") }}</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <validation-error :errors="errors" name="email" #default="{ e }">
          <label for="email">{{ $t("message.email") }}</label>
          <input
            type="email"
            name="email"
            class="form-control"
            v-model="payload.email"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error :errors="errors" name="password" #default="{ e }">
          <label for="password">{{ $t("message.new_password") }}</label>
          <input
            type="password"
            name="password"
            class="form-control"
            v-model="payload.password"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error
          :errors="errors"
          name="password_confirmation"
          #default="{ e }"
        >
          <label for="cpassword">{{ $t("message.confirm_password") }}</label>
          <input
            type="password"
            name="cpassword"
            class="form-control"
            v-model="payload.password_confirmation"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
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
import Auth from "../../services/auth";
import { is422 } from "../../shared/utils/responses";
export default {
  components: {
    ValidationError,
  },
  data() {
    return {
      submitting: false,
      errors: null,
      payload: {
        email: null,
        password: null,
        password_confirmation: null, // DO NOT change this variable name. That's how Laravel looks for it
        token: this.$route.query.token,
      },
    };
  },

  methods: {
    async submit() {
      this.submitting = true;
      try {
        await Auth.getCsrfCookie();
        try {
          await Auth.resetPassword(this.payload);
          this.$toasts.success(this.$t("modules.users.password_changed"));
          this.$router.push({
            name: "login",
          });
        } catch (error) {
          if (is422(error)) {
            this.errors = error.response.data.errors;
          }
        }
      } catch (error) {
        this.$toasts.error(this.$t("error.fatal"));
      }
      this.submitting = false;
    },
  },
};
</script>