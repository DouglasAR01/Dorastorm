<template>
  <div class="container wp bg-light">
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div v-else>
      <h3>{{ $t("modules.posts.update") }}</h3>
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
                  v-model="post.title"
                  :class="[{ 'is-invalid': e }]"
                />
              </validation-error>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="upload">{{ $t("modules.posts.banner") }}</label>
              <div v-if="bannerURL">
                <image-modal ref="imgm" />
                <image-hover-options :src="bannerURL" max-height="37">
                  <!-- <image-view-option-button> </image-view-option-button> -->
                  <button
                    class="btn btn-info btn-sm"
                    @click.prevent="
                      $refs.imgm.show({
                        src: bannerURL,
                      })
                    "
                  >
                    {{ $t("message.view") }}
                  </button>
                  <button
                    class="btn btn-warning btn-sm"
                    @click.prevent="replaceBanner()"
                  >
                    {{ $t("modules.posts.replace_banner_ok") }}
                  </button>
                </image-hover-options>
              </div>
              <div v-else>
                <single-file-upload
                  name="upload"
                  endpoint="/api/upload/image"
                  disk="public"
                  v-model="tempPath"
                ></single-file-upload>
              </div>
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
                  v-model="post.description"
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
                v-model="post.visible"
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
                v-model="post.private"
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
            v-model="post.content"
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
    </div>
    <confirm-dialogue-modal ref="cdm" />
  </div>
</template>
<script>
import { is404, is422 } from "../../shared/utils/responses";
import TextEditor from "../../components/text-editor/RichTextEditor";
import ValidationError from "../../components/ValidationError";
import ImageHoverOptions from "../../components/ImageHoverOptions";
import ImageModal from "../../components/modals/ImageModal";
import ConfirmDialogueModal from "../../components/modals/ConfirmDialogueModal";
import SingleFileUpload from "../../components/SingleFileUpload";
import PermissionsHandling from "../../shared/mixins/permissions-handling";
import TagsInput from "@voerro/vue-tagsinput";
import TagsMixin from "../../shared/mixins/tags";
export default {
  components: {
    ValidationError,
    TextEditor,
    ImageHoverOptions,
    ImageModal,
    SingleFileUpload,
    ConfirmDialogueModal,
    TagsInput,
  },
  mixins: [PermissionsHandling, TagsMixin],
  data() {
    return {
      loading: false,
      submitting: false,
      ogPost: null,
      post: null,
      errors: null,
      tempPath: null,
    };
  },
  async created() {
    this.loading = true;
    if (!("postId" in this.$route.params)) {
      this.$toasts.error(this.$t("error.fatal"));
      return;
    }
    try {
      this.post = (
        await axios.get("/api/posts/" + this.$route.params.postId + "/edit")
      ).data.data;
      this.selectedTags = this.arrayToTagsArray(this.post.tags);
      if (!this._checkIfUpdatable()) {
        this.$toasts.error(this.$t("error.403.default_title"));
        this.$router.push({
          name: "posts-index",
          params: { locale: this.$route.params.locale },
        });
      }
      this.existingTags = (await axios.get("/api/tags/posts")).data.data;
      this.ogPost = _.clone(this.post);
    } catch (error) {
      if (is404(error)) {
        this.$toasts.error(this.$t("error.404.specific.post"));
        this.$router.push({
          name: "404",
          params: { 0: "/", locale: this.$route.params.locale },
        });
      } else {
        this.$toasts.error(this.$t("error.fatal"));
      }
    }
    this.loading = false;
  },
  computed: {
    bannerURL() {
      if (this.post.banner) {
        return this.post.banner;
      }
      if (this.tempPath) {
        this.post.banner = this.tempPath;
        return "/storage/" + this.tempPath;
      }
      return null;
    },
  },
  methods: {
    async submit() {
      this.submitting = true;
      try {
        this.post.tags = this.tagsToString();
        const post = (
          await axios.patch("/api/posts/" + this.post.id, this.post)
        ).data.data;
        this.$toasts.success(this.$t("message.data_changed"));
        this.$router.push({
          name: "posts-read",
          params: {
            slug: post.slug,
            locale: this.$route.params.locale,
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
    async replaceBanner() {
      const ok = await this.$refs.cdm.show({
        title: this.$t("modules.posts.replace_banner_title"),
        message: this.$t("modules.posts.replace_banner_message"),
        okButton: this.$t("modules.posts.replace_banner_ok"),
        okButtonColor: "btn-danger",
      });
      if (ok) {
        this.post.banner = null;
      }
    },

    _checkIfUpdatable() {
      const user = this.loggedUser;
      // User is the owner
      if (this.post.author && this.post.author.id === user.id) {
        return true;
      }
      return this.checkUserPermission(user, this.corePms.UPDATE_ELSES_POSTS);
    },
  },
  async beforeRouteLeave(to, from, next) {
    if (_.isEqual(this.ogPost, this.post)) next();
    const ok = await this.$refs.cdm.show({
      title: this.$t("modules.posts.check_save_title"),
      message: this.$t("modules.posts.check_save_message"),
      okButton: this.$t("modules.posts.check_save_ok"),
      okButtonColor: "btn-warning",
    });
    if (ok) next();
  },
};
</script>