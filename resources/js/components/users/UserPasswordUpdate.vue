<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label for="cpassword">Current password</label>
      <input
        type="password"
        name="cpassword"
        class="form-control"
        v-model="payload.current_password"
        :class="[{ 'is-invalid': errorFor('current_password') }]"
      />
      <validation-error
        :errors="errorFor('current_password')"
      ></validation-error>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input
        type="password"
        name="password"
        class="form-control"
        v-model="payload.password"
        :class="[{ 'is-invalid': errorFor('password') }]"
      />
      <validation-error :errors="errorFor('password')"></validation-error>
    </div>
    <div class="form-group">
      <label for="confirm_password">Confirm password</label>
      <input
        type="password"
        name="confirm_password"
        class="form-control"
        v-model="payload.password_confirmation"
        :class="[{ 'is-invalid': errorFor('password_confirmation') }]"
      />
      <validation-error
        :errors="errorFor('password_confirmation')"
      ></validation-error>
      <small
        v-if="!fieldConfirmed(payload.password, payload.password_confirmation)"
        class="text-danger"
        >The passwords doesn't match</small
      >
    </div>
    <input
      type="submit"
      value="Update password"
      class="btn btn-primary mr-1"
      :disabled="
        submitting ||
        !fieldConfirmed(payload.password, payload.password_confirmation)
      "
    />
    <button class="btn btn-danger" @click.prevent="$emit('cancel')">
      Cancel
    </button>
  </form>
</template>
<script>
import * as Responses from "../../shared/utils/responses";
import FormTraits from "../../shared/mixins/form-traits";
import ErrorTraits from "../../shared/mixins/error-traits";
import ValidationError from "../../shared/components/ValidationError";
export default {
  mixins: [FormTraits, ErrorTraits],
  components: {
    ValidationError,
  },
  props: {
    user_id: null,
  },
  data() {
    return {
      submitting: false,
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
          "/api/users/" + this.user_id + "/password",
          this.payload
        );
        this.$toasts.success("The changes were made successfully.");
        this.$emit("cancel");
      } catch (error) {
        if (Responses.is404(error)) {
          this.$toasts.error("We couldn't find the specified user.");
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