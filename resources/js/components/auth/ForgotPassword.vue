<template>
  <div class="container rounded bg-white p-2">
    <h2>{{ $t("message.forgot_password") }}</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="email">{{ $t("message.email") }}</label>
        <input
          type="email"
          name="email"
          class="form-control"
          v-model="email"
          :class="[{ 'is-invalid': errorFor('email') }]"
          required
        />
        <validation-error :errors="errorFor('email')"></validation-error>
      </div>
      <input type="submit" :value="$t('message.submit')" class="btn btn-primary btn-block" :disabled="loading">
    </form>
  </div>
</template>
<script>
import ErrorTraits from "../../shared/mixins/error-traits";
import ValidationError from "../../shared/components/ValidationError";
import Auth from "../../services/auth";
import { is422 } from "../../shared/utils/responses";
export default {
  mixins: [ErrorTraits],
  components: {
    ValidationError,
  },

  data() {
    return {
      loading: false,
      email: null,
    };
  },

  methods: {
    async submit() {
      this.loading = true;
      try {
        await Auth.getCsrfCookie();
      } catch (error) {
        this.$toasts.error($t("error.fatal"));
        return;
      }
      try {
        let msg = (await Auth.forgotPassword({ email: this.email })).data.message;
        this.$toasts.success(msg);
        this.$router.push({
          name: 'home'
        });
      } catch (error) {
        if (is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
      this.loading = false;
    },
  },
};
</script>