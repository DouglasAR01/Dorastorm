<template>
  <div class="container rounded bg-white p-2">
    <h3>{{ $t("modules.posts.create") }}</h3>
    <form>
      <div class="row">
        <div class="col-8">
          <div class="form-group">
            <label for="title">{{ $t("modules.posts.title") }}</label>
            <input
              type="text"
              name="title"
              class="form-control"
              v-model="new_post.title"
              :class="[{ 'is-invalid': errorFor('title') }]"
            />
            <validation-error :errors="errorFor('title')"></validation-error>
          </div>
        </div>
        <div class="col-4">
          <label>{{ $t("modules.posts.options") }}</label>
          <div class="custom-control custom-checkbox">
            <input
              type="checkbox"
              class="custom-control-input"
              id="visible"
              v-model="new_post.visible"
            />
            <label class="custom-control-label" for="visible">{{
              $t("modules.posts.visible")
            }}</label>
          </div>
          <div class="custom-control custom-checkbox">
            <input
              type="checkbox"
              class="custom-control-input"
              id="private"
              v-model="new_post.private"
            />
            <label class="custom-control-label" for="private">{{
              $t("modules.posts.private")
            }}</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="description">{{ $t("modules.posts.description") }}</label>
        <textarea
          name="description"
          class="form-control"
          v-model="new_post.description"
          :class="[{ 'is-invalid': errorFor('description') }]"
        ></textarea>
        <validation-error :errors="errorFor('description')"></validation-error>
      </div>
    </form>
    <div class="form-group">
      <label for="content">{{ $t("modules.posts.content") }}</label>
      <text-editor
        v-model="new_post.content"
        :class="[{ 'is-invalid': errorFor('content') }]"
      ></text-editor>
      <validation-error :errors="errorFor('content')"></validation-error>
    </div>
    <button
      class="btn btn-primary btn-block"
      :disabled="submitting"
      @click.prevent="submit"
    >
      {{ $t("message.submit") }}
    </button>
  </div>
</template>
<script>
import TextEditor from "../../shared/components/RichTextEditor";
import ErrorTraits from "../../shared/mixins/error-traits";
import ValidationError from "../../shared/components/ValidationError";
import { is422 } from "../../shared/utils/responses";
export default {
  components: {
    TextEditor,
    ValidationError,
  },
  mixins: [ErrorTraits],
  data() {
    return {
      submitting: false,
      new_post: {
        title: null,
        description: null,
        content: null,
        visible: false,
        private: false,
      },
    };
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        const post = (await axios.post("/api/posts", this.new_post)).data.data;
        this.$toasts.success(this.$t("modules.posts.created"));
        this.$router.push({
          name: "posts-read",
          params: {
            slug: post.slug,
          },
        });
      } catch (error) {
        if (is422(error)) {
          this.errors = error.response.data.errors;
        } else {
          this.$toasts.error(this.$t("error.fatal"));
        }
      }
      this.submitting = false;
    },
  },
};
</script>