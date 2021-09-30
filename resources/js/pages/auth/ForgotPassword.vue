<template>
  <div class="container wp bg-light">
    <h2>{{ $t("message.forgot_password") }}</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <validation-error :errors="errors" name="email" #default="{ e }">
          <label for="email">{{ $t("message.email") }}</label>
          <input
            type="email"
            name="email"
            class="form-control"
            v-model="email"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <input
        type="submit"
        :value="$t('message.submit')"
        class="btn btn-primary btn-block"
        :disabled="loading"
      />
    </form>
  </div>
</template>
<script>
import ValidationError from "../../components/ValidationError";
import Auth from "../../services/auth";
import { is422 } from "../../shared/utils/responses";
export default {
  components: {
    ValidationError,
  },

  data() {
    return {
      loading: false,
      email: null,
      errors: null,
    };
  },

  methods: {
    async submit() {
      this.loading = true;
      try {
        await Auth.getCsrfCookie();
        try {
          let msg = (await Auth.forgotPassword({ email: this.email })).data
            .message;
          this.$toasts.success(msg);
          this.$router.push({
            name: "home",
            params: {locale: this.$route.params.locale}
          });
        } catch (error) {
          if (is422(error)) {
            this.errors = error.response.data.errors;
          }
        }
      } catch (error) {
        this.$toasts.error(this.$t("error.fatal"));
        return;
      }
      this.loading = false;
    },
  },
};
</script>