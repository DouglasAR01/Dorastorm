<template>
  <div class="container bg-white rounded p-3">
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div v-else>
      <h1>{{ post.title }}</h1>
      <div class="editor__content" v-html="post.content"></div>
    </div>
  </div>
</template>
<script>
import { is404 } from "../../shared/utils/responses";
export default {
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
