<template>
  <div class="container wp bg-light">
    <h3>{{ $t("modules.posts.create") }}</h3>
    <form>
      <div class="form-row">
        <div class="col-md-8">
          <div class="form-group">
            <validation-error :errors="errors" name="title" #default="{ e }">
              <label for="title">{{ $t("modules.posts.title") }}</label>
              <input
                type="text"
                name="title"
                class="form-control"
                v-model="newPost.title"
                :class="[{ 'is-invalid': e }]"
              />
            </validation-error>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="upload">{{ $t("modules.posts.banner") }}</label>
            <single-file-upload
              name="upload"
              endpoint="/api/upload/image"
              disk="public"
              v-model="newPost.banner"
            ></single-file-upload>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-8">
          <div class="form-group">
            <validation-error
              :errors="errors"
              name="description"
              #default="{ e }"
            >
              <label for="description">{{
                $t("modules.posts.description")
              }}</label>
              <textarea
                name="description"
                class="form-control"
                v-model="newPost.description"
                :class="[{ 'is-invalid': e }]"
              ></textarea>
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
              v-model="newPost.visible"
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
              v-model="newPost.private"
            />
            <label class="custom-control-label" for="private">{{
              $t("modules.posts.private")
            }}</label>
          </div>
        </div>
      </div>
    </form>
    <div class="form-group">
      <validation-error :errors="errors" name="content" #default="{ e }">
        <label for="content">{{ $t("modules.posts.content") }}</label>
        <text-editor
          v-model="newPost.content"
          :class="[{ 'is-invalid': e }]"
        ></text-editor>
      </validation-error>
    </div>
    <div class="form-group" v-if="!loading">
      <label for="tags">{{ $t("modules.posts.tags.tags") }}</label>
      <tags-input
        name="tags"
        v-model="selectedTags"
        :existing-tags="existingTags"
        :typeahead="true"
        :typeahead-max-results="15"
        :id-field="cKey"
        :text-field="cValue"
        :add-tags-on-comma="true"
        :discard-search-text="$t('modules.posts.tags.discard')"
        :placeholder="$t('modules.posts.tags.placeholder')"
      />
    </div>
    <button
      class="btn btn-primary btn-block"
      :disabled="submitting"
      @click.prevent="submit"
    >
      {{ $t("message.submit") }}
    </button>
    <confirm-dialogue-modal ref="cdm" />
  </div>
</template>
<script>
import TextEditor from "../../components/text-editor/RichTextEditor";
import ValidationError from "../../components/ValidationError";
import SingleFileUpload from "../../components/SingleFileUpload";
import { is422 } from "../../shared/utils/responses";
import TagsInput from "@voerro/vue-tagsinput";
import TagsMixin from "../../shared/mixins/tags";
import ConfirmDialogueModal from "../../components/modals/ConfirmDialogueModal";
export default {
  components: {
    TextEditor,
    ValidationError,
    SingleFileUpload,
    TagsInput,
    ConfirmDialogueModal
  },
  mixins: [TagsMixin],
  data() {
    return {
      submitting: false,
      errors: null,
      loading: false,
      newPost: {
        title: null,
        description: null,
        content: null,
        visible: false,
        private: false,
        banner: null,
      },
    };
  },
  async mounted() {
    this.loading = true;
    try {
      this.existingTags = (await axios.get("/api/tags/posts")).data.data;
      this.loading = false;
    } catch (error) {
      this.$toasts.error(this.$t("error.fatal"));
    }
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        this.newPost.tags = this.tagsToString();
        const post = (await axios.post("/api/posts", this.newPost)).data.data;
        this.$toasts.success(this.$t("modules.posts.created"));
        this.$router.push({
          name: "posts-read",
          params: {
            slug: post.slug,
            locale: this.$route.params.locale,
          },
        });
      } catch (error) {
        console.log(error);
        if (is422(error)) {
          this.errors = error.response.data.errors;
        } else {
          this.$toasts.error(this.$t("error.fatal"));
        }
      }
      this.submitting = false;
    },
    hasContent() {
      let baseChecks = [
        'title',
        'description',
        'banner'
      ];
      for (let check of baseChecks){
        if (this.newPost[check] != '' && this.newPost[check] != null){
          return true;
        }
      }
      if (this.newPost.content != '<p></p>' && this.newPost.content!= null) return true;
      if (this.selectedTags.length > 0) return true;
      
      return false;
    }
  },
  async beforeRouteLeave(to, from, next) {
    if (!this.hasContent()) next();
    const ok = await this.$refs.cdm.show({
      title: this.$t("modules.posts.check_save_title"),
      message: this.$t("modules.posts.check_save_message"),
      okButton: this.$t("modules.posts.check_save_ok"),
      okButtonColor: "btn-warning",
    });
    if (ok) next();
  }
};
</script>