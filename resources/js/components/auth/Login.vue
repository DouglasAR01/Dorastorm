<template>
  <div class="d-flex w-50 m-auto align-items-center">
    <div class="card card-body">
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="email">Email</label>
          <input
            type="email"
            class="form-control"
            name="email"
            v-model="user.email"
            :class="[{ 'is-invalid': errorFor('email') }]"
            required
          />
          <validation-error :errors="errorFor('email')"></validation-error>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            class="form-control"
            name="password"
            v-model="user.password"
            :class="[{ 'is-invalid': errorFor('password') }]"
            required
          />
          <validation-error :errors="errorFor('password')"></validation-error>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-check pb-2">
              <input
                type="checkbox"
                class="form-check-input"
                v-model="user.remember"
                name="remember"
              />
              <label for="remember" class="form-check-label"
                >Remember me?</label
              >
            </div>
          </div>
          <div class="col-6">
            <router-link :to="{ name: 'forgot-password' }"
              >Forgot password</router-link
            >
          </div>
        </div>
        <input type="submit" value="Submit" class="btn btn-primary btn-block" :disabled="loading">
      </form>
    </div>
  </div>
</template>
<script>
import { is422 } from "../../shared/utils/responses";
import ErrorTraits from "../../shared/mixins/error-traits";
import ValidationError from "../../shared/components/ValidationError";
import Auth from "../../services/auth";
export default {
  mixins: [ErrorTraits],
  components: {
    ValidationError,
  },
  data() {
    return {
      user: {
        email: null,
        password: null,
        remember: false,
      },
      loading: false,
    };
  },
  methods: {
    async login() {
      this.loading = true;
      try {
        await Auth.getCsrfCookie();
      } catch (error) {
        this.$toasts.error("Something very wrong happened. Try again later.");
      }
      try {
        // Laravel uses $request->filled('remember'), thats why if it is false you must set it to null
        this.user.remember = !!this.user.remember == false ? null : true;
        const user = await Auth.login(this.user);
        this.$store.dispatch("login", user);
        this.$router.push({ name: "me" });
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