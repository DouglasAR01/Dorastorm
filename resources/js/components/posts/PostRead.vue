<template>
  <div class="container bg-white rounded p-3">
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div v-else>
      <post-creator-options :author-id="post.author.id" :post-id="post.id"></post-creator-options>
      <h1>{{ post.title }}</h1>
      <div class="editor__content" v-html="post.content"></div>
      <hr>
      <div class="row">
        <div class="col-6">
          <span>
            <b>{{$t("modules.posts.author")}}</b> {{post.author.name}}
          </span>
        </div>
        <div class="col-6">
          <span class="d-block">
            <b>{{$t("message.created_at")}}</b> {{post.created | humanDate}}
          </span>
          <span class="d-block text-muted">
            <b>{{$t("message.updated_at")}}</b> {{post.modified | humanDate}}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { is404 } from "../../shared/utils/responses";
import PostCreatorOptions from "./PostCreatorOptions";
export default {
  components: {
    PostCreatorOptions
  },
  data() {
    return {
      loading: false,
      post: null,
    };
  },
  async created() {
    this.loading = true;
    try {
      this.post = (
        await axios.get("/api/posts/" + this.$route.params.slug)
      ).data.data;
      this.loading = false;
    } catch (error) {
      if (is404(error)) {
        this.$toasts.error(this.$t("error.404.specific.post"));
        this.$router.push({
          name: "404",
          params: { 0: "/" },
        });
      } else {
        this.$toasts.error(this.$t("error.fatal"));
      }
    }
  },
};
</script>
