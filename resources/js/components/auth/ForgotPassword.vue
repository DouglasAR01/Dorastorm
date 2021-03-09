<template>
  <div class="container rounded bg-white p-2">
    <h2>Forgot Password</h2>
    <form>
      <div class="form-group">
        <label for="email">User E-mail:</label>
        <input
          type="email"
          name="email"
          class="form-control"
          v-model="email"
          :class="[{ 'is-invalid': errorFor('email') }]"
        />
        <validation-error :errors="errorFor('email')"></validation-error>
      </div>
      <button
        class="btn btn-primary btn-block"
        @click.prevent="submit"
        :disabled="loading"
      >
        Submit
      </button>
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
        this.$toasts.error("Something very wrong happened. Try again later.");
        return;
      }
      try {
        await Auth.forgotPassword({ email: this.email });
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