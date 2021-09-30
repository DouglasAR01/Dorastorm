<template>
  <div class="container wp bg-light">
    <form @submit.prevent="submit">
      <div class="form-group">
        <validation-error :errors="errors" name="name" #default="{ e }">
          <label for="name">{{ $t("modules.users.name") }}</label>
          <input
            type="text"
            name="name"
            class="form-control"
            v-model="newUser.name"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
      </div>
      <div class="form-group">
        <validation-error :errors="errors" name="email" #default="{ e }">
          <label for="email">{{ $t("message.email") }}</label>
          <input
            type="email"
            name="email"
            class="form-control"
            v-model="newUser.email"
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
            name="password"
            class="form-control"
            v-model="newUser.password"
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
          <label for="confirm_password">{{
            $t("message.confirm_password")
          }}</label>
          <input
            type="password"
            name="confirm_password"
            class="form-control"
            v-model="newUser.password_confirmation"
            :class="[{ 'is-invalid': e }]"
            required
          />
        </validation-error>
        <small
          v-if="
            !fieldConfirmed(newUser.password, newUser.password_confirmation)
          "
          class="text-danger"
          >The passwords doesn't match</small
        >
      </div>
      <div class="form-group">
        <div v-if="!loading">
          <validation-error :errors="errors" name="role_id" #default="{ e }">
            <label for="role_id">{{ $t("modules.users.role_select") }}</label>
            <select
              name="role_id"
              class="custom-select form-control"
              v-model="newUser.role_id"
              :class="[{ 'is-invalid': e }]"
              required
            >
              <option disabled value="">
                {{ $t("modules.users.role_default") }}
              </option>
              <option
                :value="role.id"
                v-for="role in availableRoles"
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
          !fieldConfirmed(newUser.password, newUser.password_confirmation)
        "
      />
    </form>
  </div>
</template>
<script>
import { is422 } from "../../shared/utils/responses";
import FormTraits from "../../shared/mixins/form-traits";
import ValidationError from "../../components/ValidationError";
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
      availableRoles: null,
      newUser: {
        name: null,
        email: null,
        password: null,
        password_confirmation: null, // DO NOT change this variable name
        role_id: "", // DO NOT change this variable name
      },
    };
  },
  async created() {
    this.formInitialState = Obj.clone(this.newUser);
    try {
      this.loading = true;
      this.availableRoles = (await Auth.userRolesBelow()).data.data;
      this.loading = false;
    } catch (error) {
      this.$toasts.error(this.$t("error.fatal"));
    }
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        let user = (await axios.post("/api/users", this.newUser)).data;
        this.submitting = false;
        this.$toasts.success(this.$t("modules.users.created"));
        this.newUser = Obj.clone(this.formInitialState);
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