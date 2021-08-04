<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <validation-error :errors="errors" name="subject" #default="{ e }">
        <label for="subject">{{$t("modules.quotes.subject")}}:</label>
        <input
          type="text"
          name="subject"
          class="form-control"
          :class="[{ 'is-invalid': e }]"
          v-model="quote.subject"
          required
        />
      </validation-error>
    </div>
    <div class="form-row">
      <div class="col-md-8">
        <div class="form-group">
          <validation-error :errors="errors" name="name" #default="{ e }">
            <label for="name">{{$t("modules.quotes.form_name")}}:</label>
            <input
              type="text"
              name="name"
              class="form-control"
              :class="[{ 'is-invalid': e }]"
              v-model="quote.name"
              required
            />
          </validation-error>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <validation-error :errors="errors" name="phone" #default="{ e }">
            <label for="phone">{{$t("modules.quotes.phone")}}:</label>
            <input
              type="text"
              name="phone"
              class="form-control"
              :class="[{ 'is-invalid': e }]"
              v-model="quote.phone"
            />
          </validation-error>
        </div>
      </div>
    </div>
    <div class="form-group">
      <validation-error :errors="errors" name="email" #default="{ e }">
        <label for="email">{{$t("message.email")}}</label>
        <input
          type="email"
          name="email"
          class="form-control"
          :class="[{ 'is-invalid': e }]"
          v-model="quote.email"
          required
        />
      </validation-error>
    </div>
    <div class="form-group">
      <validation-error :errors="errors" name="content" #default="{ e }">
        <label for="content">{{$t("modules.quotes.content")}}:</label>
        <textarea
          name="content"
          rows="5"
          class="form-control"
          :class="[{ 'is-invalid': e }]"
          v-model="quote.content"
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
</template>
<script>
import ValidationError from "../../shared/components/ValidationError.vue";
import { is422 } from "../../shared/utils/responses";
export default {
  components: {
    ValidationError,
  },
  data() {
    return {
      loading: false,
      errors: null,
      quote: {
        subject: null,
        email: null,
        phone: null,
        name: null,
        content: null,
      },
    };
  },
  methods: {
    async submit() {
      this.loading = true;
      try {
        await axios.post("/api/quotes", this.quote);
        this.$toasts.success("Quote sended");
      } catch (error) {
        if (is422(error)) {
          this.errors = error.response.data.errors;
        } else {
          this.$toasts.error("Something happened");
        }
      }
      this.loading = false;
    },
  },
};
</script>