<template>
  <div>
    <div v-for="post in posts" :key="post.id">
      <post-data-card :post="post" class="mb-1"></post-data-card>
    </div>
  </div>
</template>
<script>
import PostDataCard from "./PostDataCard";
export default {
  props: ["posts"],
  components: {
    PostDataCard
  }
}
</script>