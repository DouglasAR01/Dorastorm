<template>
  <div class="container bg-white rounded p-3">
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div v-else>
      <post-list :posts="posts" class="mb-2"></post-list>
      <div class="row d-flex justify-content-center">
        <button
          class="btn btn-primary mr-1"
          v-if="hasPrev"
          @click.prevent="navigate(meta.current_page - 1)"
        >
          <i class="fas fa-backward"></i>
          {{ $t("modules.posts.previous") }}
        </button>
        <button
          class="btn btn-primary mr-1"
          v-if="hasPrev"
          @click.prevent="navigate(1)"
        >
          <i class="fas fa-home"></i>
          {{ $t("modules.posts.first") }}
        </button>
        <button
          class="btn btn-primary"
          v-if="hasNext"
          @click.prevent="navigate(meta.current_page + 1)"
        >
          {{ $t("modules.posts.next") }}
          
          <i class="fas fa-forward"></i>
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import PostList from "./PostList";
export default {
  components: {
    PostList,
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
  computed: {
    hasNext() {
      return this.links.next != null;
    },
    hasPrev() {
      return this.links.prev != null;
    },
  },
  methods: {
    setData(resp) {
      this.posts = resp.data.data;
      this.meta = resp.data.meta;
      this.links = resp.data.links;
    },
    async navigate(page) {
      console.log(page);
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