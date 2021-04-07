<template>
  <div class="container rounded bg-white p-3">
    <form @submit.prevent="submit">
      <div class="form-group">
        <validation-error :errors="errors" name="name" v-slot="{ e }">
          <label for="name">{{ $t("modules.users.name") }}</label>
          <input
            type="text"
            name="name"
            class="form-control"
            v-model="new_user.name"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error :errors="errors" name="email" v-slot="{ e }">
          <label for="email">{{ $t("message.email") }}</label>
          <input
            type="email"
            name="email"
            class="form-control"
            v-model="new_user.email"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error :errors="errors" name="password" v-slot="{ e }">
          <label for="password">{{ $t("message.password") }}</label>
          <input
            type="password"
            name="password"
            class="form-control"
            v-model="new_user.password"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error
          :errors="errors"
          name="password_confirmation"
          v-slot="{ e }"
        >
          <label for="confirm_password">{{
            $t("message.confirm_password")
          }}</label>
          <input
            type="password"
            name="confirm_password"
            class="form-control"
            v-model="new_user.password_confirmation"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
        <small
          v-if="
            !fieldConfirmed(new_user.password, new_user.password_confirmation)
          "
          class="text-danger"
          >The passwords doesn't match</small
        >
      </div>
      <div class="form-group">
        <div v-if="!loading">
          <validation-error :errors="errors" name="role_id" v-slot="{ e }">
            <label for="role_id">{{ $t("modules.users.role_select") }}</label>
            <select
              name="role_id"
              class="custom-select form-control"
              v-model="new_user.role_id"
              :class="[{ 'is-invalid': e }]"
              required
            >
              <option disabled value="">
                {{ $t("modules.users.role_default") }}
              </option>
              <option
                :value="role.id"
                v-for="role in available_roles"
                :key="role.id"
              >
                {{ role.name }}
              </option>
            </select>
          </validation-error>
        </div>
      </div>
      <input
        type="submit"
        :value="$t('message.submit')"
        class="btn btn-primary btn-block"
        :disabled="
          loading ||
          submitting ||
          !fieldConfirmed(new_user.password, new_user.password_confirmation)
        "
      />
    </form>
  </div>
</template>
<script>
import { is422 } from "../../shared/utils/responses";
import FormTraits from "../../shared/mixins/form-traits";
import ValidationError from "../../shared/components/ValidationError";
import Obj from "../../shared/utils/object-utils";
import Auth from "../../services/auth";
export default {
  mixins: [FormTraits],
  components: {
    ValidationError,
  },
  data() {
    return {
      loading: false,
      submitting: false,
      errors: null,
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
      this.available_roles = (await Auth.userRolesBelow()).data.data;
      this.loading = false;
    } catch (error) {
      this.$toasts.error(this.$t("error.fatal"));
    }
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        let user = (await axios.post("/api/users", this.new_user)).data;
        this.submitting = false;
        this.$toasts.success(this.$t("modules.users.created"));
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