<template>
  <div>
    <posts-loading v-if="loading"></posts-loading>
    <div v-else>
      <div class="row mb-2">
        <div class="col-sm-8">
          <h3>{{ $t("modules.posts.recent") }}</h3>
        </div>
        <div class="col-sm-4">
          <search-input @search="navigate(1)" v-model="params.q"></search-input>
        </div>
      </div>
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
  </div>
</template>
<script>
import PostList from "./PostList";
import SimplePagination from "../../shared/components/SimplePagination";
import SearchInput from "../../shared/components/SearchInput";
import IndexPaginationTraits from "../../shared/mixins/index-pagination-traits";
import PostsLoading from "../../shared/components/loading/Posts";
export default {
  components: {
    PostList,
    SimplePagination,
    SearchInput,
    PostsLoading,
  },
  mixins: [IndexPaginationTraits],
  created() {
    this.ep = "/api/posts";
  }
};
</script>