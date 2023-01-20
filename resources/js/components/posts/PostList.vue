<template>
  <section v-if="posts.length > 0" key="posts-list">
    <article v-for="post in posts" :key="post.id">
      <post-data-card :post="post" class="mb-1"></post-data-card>
    </article>
  </section>
  <section v-else key="posts-list" class="d-flex justify-content-center">
    <i class="fas fa-search-minus fa-10x"></i>
  </section>
</template>
<script>
import PostDataCard from "./PostDataCard";
export default {
  props: {
    posts: {
      type: Array,
      required: true
    }
  },
  components: {
    PostDataCard
  }
}
</script>