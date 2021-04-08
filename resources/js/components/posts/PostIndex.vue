<template>
  <div class="container bg-white rounded p-3">
    <div v-if="loading">
      {{ $t("message.loading") }}
    </div>
    <div v-else>
      <div class="row mb-2">
        <div class="col-md-8">
          <h3>{{ $t("modules.posts.recent") }}</h3>
        </div>
        <div class="col-md-4">
          <search-input @search="navigate(1)" v-model="q"></search-input>
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
export default {
  components: {
    PostList,
    SimplePagination,
    SearchInput
  },
  mixins: [IndexPaginationTraits],
  created() {
    this.ep = "/api/posts";
  },
};
</script>