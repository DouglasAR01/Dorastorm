<template>
  <div v-if="relatedPosts.length > 0">
    <div class="card mb-2" v-for="post in relatedPosts" :key="post.id">
      <div class="clickable" @click="reload(post.slug)">
        <img
          :src="post.banner"
          alt="F"
          class="card-img-top"
          v-if="post.banner"
        />
        <img
          :src="require('../../../assets/default-image.svg')"
          alt="F"
          class="card-img-top"
          v-else
        />
      </div>
      <div class="card-body">
        <h5 class="card-title clickable" @click="reload(post.slug)">
          {{ post.title }}
        </h5>
        <p class="card-text">
          {{ post.description }}
        </p>
      </div>
    </div>
  </div>
  <div v-else>Oops.</div>
</template>
<script>
export default {
  props: {
    relatedPosts: {
      type: Array,
      required: true,
    },
  },
  methods: {
    reload(slug) {
      this.$router.push({
        to: "post-read",
        params: {
          slug: slug,
          locale: this.$route.params.locale,
        },
      });
    },
  },
};
</script>
<style scoped>
.clickable {
  cursor: pointer;
}
.card-title:hover {
  text-decoration: underline;
}
</style>