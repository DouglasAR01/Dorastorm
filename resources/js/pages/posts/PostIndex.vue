<template>
  <article class="container wp">
    <posts-loading v-if="loading"></posts-loading>
    <div v-else>
      <section class="row mb-2">
        <div class="col-sm-8">
          <h3>{{ $t("modules.posts.recent") }}</h3>
        </div>
        <div class="col-sm-4">
          <search-input @search="navigate(1)" v-model="params.q"></search-input>
        </div>
      </section>
      <post-list :posts="data" class="mb-2"></post-list>
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
  </article>
</template>
<script>
import PostList from "../../components/posts/PostList";
import SimplePagination from "../../components/SimplePagination";
import SearchInput from "../../components/SearchInput";
import IndexPaginationTraits from "../../shared/mixins/index-pagination-traits";
import PostsLoading from "../../components/loading/PostsAnimation";
export default {
  components: {
    PostList,
    SimplePagination,
    SearchInput,
    PostsLoading,
  },
  metaInfo() {
    return {
      meta: [
        {
          name: "description",
          content: this.$t("modules.posts.recent"),
        },
      ],
      title: this.$t("modules.posts.recent"),
    };
  },
  mixins: [IndexPaginationTraits],
  created() {
    this.ep = "/api/posts";
  },
};
</script>