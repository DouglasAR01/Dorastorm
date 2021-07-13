<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <validation-error :errors="errors" name="current_password" #default="{ e }">
        <label for="cpassword">{{ $t("message.current_password") }}</label>
        <input
          type="password"
          name="cpassword"
          class="form-control"
          v-model="payload.current_password"
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
        <label for="confirm_password">{{
          $t("message.confirm_password")
        }}</label>
        <input
          type="password"
          name="confirm_password"
          class="form-control"
          v-model="payload.password_confirmation"
          :class="[{ 'is-invalid': e }]"
          required
        />
      </validation-error>
      <small
        v-if="!fieldConfirmed(payload.password, payload.password_confirmation)"
        class="text-danger"
        >{{ $t("error.validation.confirm_password") }}</small
      >
    </div>
    <input
      type="submit"
      :value="$t('modules.users.change_password')"
      class="btn btn-primary mr-1"
      :disabled="
        submitting ||
        !fieldConfirmed(payload.password, payload.password_confirmation)
      "
    />
    <button class="btn btn-danger" @click.prevent="$emit('cancel')">
      {{ $t("message.cancel") }}
    </button>
  </form>
</template>
<script>
import * as Responses from "../../shared/utils/responses";
import FormTraits from "../../shared/mixins/form-traits";
import ValidationError from "../../shared/components/ValidationError";
export default {
  mixins: [FormTraits],
  components: {
    ValidationError,
  },
  props: {
    userId: null,
  },
  data() {
    return {
      submitting: false,
      errors: null,
      payload: {
        password: null,
        password_confirmation: null,
        current_password: null,
      },
    };
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        await axios.patch(
          "/api/users/" + this.userId + "/password",
          this.payload
        );
        this.$toasts.success(this.$t("modules.users.password_changed"));
        this.$emit("cancel");
      } catch (error) {
        if (Responses.is404(error)) {
          this.$toasts.error(this.$t("error.404.specific.user"));
        }
        if (Responses.is422(error)) {
          this.errors = error.response.data.errors;
        }
      }
      this.submitting = false;
    },
  },
};
</script>