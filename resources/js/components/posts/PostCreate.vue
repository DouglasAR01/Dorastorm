<template>
  <div class="wp-light">
    <h3>{{ $t("modules.posts.create") }}</h3>
    <form>
      <div class="form-row">
        <div class="col-md-8">
          <div class="form-group">
            <validation-error :errors="errors" name="title" v-slot="{ e }">
              <label for="title">{{ $t("modules.posts.title") }}</label>
              <input
                type="text"
                name="title"
                class="form-control"
                v-model="new_post.title"
                :class="[{ 'is-invalid': e }]"
              />
            </validation-error>
          </div>
        </div>
        <div class="col-md-4">
          <label>{{ $t("modules.posts.options") }}</label>
          <div class="custom-control custom-switch">
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
          <div class="custom-control custom-switch">
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
        <validation-error :errors="errors" name="description" v-slot="{ e }">
          <label for="description">{{ $t("modules.posts.description") }}</label>
          <textarea
            name="description"
            class="form-control"
            v-model="new_post.description"
            :class="[{ 'is-invalid': e }]"
          ></textarea>
        </validation-error>
      </div>
    </form>
    <div class="form-group">
      <validation-error :errors="errors" name="content" v-slot="{ e }">
        <label for="content">{{ $t("modules.posts.content") }}</label>
        <text-editor
          v-model="new_post.content"
          :class="[{ 'is-invalid': e }]"
        ></text-editor>
      </validation-error>
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
import ValidationError from "../../shared/components/ValidationError";
import { is422 } from "../../shared/utils/responses";
export default {
  components: {
    TextEditor,
    ValidationError,
  },
  data() {
    return {
      submitting: false,
      errors: null,
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