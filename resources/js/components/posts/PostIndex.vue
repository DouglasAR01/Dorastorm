<template>
  <div class="container bg-white rounded p-3">
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div v-else>
      <post-list :posts="posts" class="mb-2"></post-list>
      <simple-pagination
        :meta="meta"
        :links="links"
        :messages="{
          prev: $t('modules.posts.previous'),
          first: $t('modules.posts.first'),
          next: $t('modules.posts.next'),
        }"
        @navigating="navigate"
      ></simple-pagination>
    </div>
  </div>
</template>
<script>
import PostList from "./PostList";
import SimplePagination from "../../shared/components/SimplePagination";
export default {
  components: {
    PostList,
    SimplePagination,
  },
  data() {
    return {
      loading: false,
      posts: null,
      meta: null,
      links: null,
    };
  },
  created() {
    this.navigate(1);
  },
  methods: {
    setData(resp) {
      this.posts = resp.data.data;
      this.meta = resp.data.meta;
      this.links = resp.data.links;
    },
    async navigate(page) {
      this.loading = true;
      try {
        const resp = await axios.get("/api/posts?page=" + page);
        this.setData(resp);
        this.loading = false;
      } catch (error) {
        this.$toasts.error(this.$t("error.fatal"));
      }
    },
  },
};
</script>