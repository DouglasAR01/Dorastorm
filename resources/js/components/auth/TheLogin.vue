<template>
<div class="container wp">
   <div class="row">
    <div class="col-md-3 col-lg-4"></div>
    <div class="col-md-6 col-lg-4">
      <div class="card card-body">
        <form @submit.prevent="login">
          <div class="form-group">
            <validation-error :errors="errors" name="email" #default="{ e }">
              <label for="email">{{ $t("message.email") }}</label>
              <input
                type="email"
                class="form-control"
                name="email"
                v-model="user.email"
                :class="[{ 'is-invalid': e }]"
                required
              />
            </validation-error>
          </div>
          <div class="form-group">
            <validation-error :errors="errors" name="password" #default="{ e }">
              <label for="password">{{ $t("message.password") }}</label>
              <input
                type="password"
                class="form-control"
                name="password"
                v-model="user.password"
                :class="[{ 'is-invalid': e }]"
                required
              />
            </validation-error>
          </div>
          <div class="form-check pb-2">
            <input
              type="checkbox"
              class="form-check-input"
              v-model="user.remember"
              name="remember"
            />
            <label for="remember" class="form-check-label">{{
              $t("message.remember_me")
            }}</label>
          </div>
          <input
            type="submit"
            :value="$t('message.submit')"
            class="btn btn-primary btn-block"
            :disabled="loading"
          />
        </form>
        <hr />
        <router-link :to="{ name: 'forgot-password', params: {locale: $route.params.locale} }">{{
          $t("message.forgot_password")
        }}</router-link>
      </div>
    </div>
    <div class="col-md-3 col-lg-4"></div>
  </div>
</div> 
</template>
<script>
import { is422 } from "../../shared/utils/responses";
import ValidationError from "../../shared/components/ValidationError";
import Auth from "../../services/auth";
export default {
  components: {
    ValidationError,
  },
  data() {
    return {
      user: {
        email: null,
        password: null,
        remember: true,
      },
      loading: false,
      errors: null,
    };
  },
  methods: {
    async login() {
      this.loading = true;
      try {
        await Auth.getCsrfCookie();
        try {
          // Laravel uses $request->filled('remember'), thats why if it is false you must set it to null
          this.user.remember = !!this.user.remember == false ? null : true;
          const user = await Auth.login(this.user);
          this.$store.dispatch("login", user);
          this.$router.push({ name: "me", params: {locale: this.$route.params.locale} });
        } catch (error) {
          if (is422(error)) {
            this.errors = error.response.data.errors;
          }
        }
      } catch (error) {
        this.$toasts.error(this.$t("error.fatal"));
      }
      this.loading = false;
    },
  },
};
</script>