<template>
  <div class="card border-bottom">
    <div class="row no-gutters">
      <div class="col-md-4 p-2">
        <router-link :to="postLink">
          <img :src="post.banner" alt="" class="card-img post-card-img" v-if="post.banner" />
          <img
            :src="require('../../../assets/default-image.svg')"
            alt=""
            class="card-img post-card-img"
            v-else
          />
        </router-link>
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <router-link class="h5 card-title" :to="postLink">
            {{ post.title }}
            <div class="d-inline" v-if="isUserOwner">
              <span class="badge badge-success" v-if="isUserOwner">
                {{$t("modules.posts.tag_yours")}}
              </span>
              <span class="badge badge-secondary" v-if="!post.visible">
                {{$t("modules.posts.tag_invisible")}}
              </span>
              <span class="badge badge-info" v-if="post.private">
                {{$t("modules.posts.tag_private")}}
              </span>
            </div>
          </router-link>
          <p class="card-text">
            {{ post.description }}
            <span>
              <router-link class="text-muted" :to="postLink">
                {{ $t("modules.posts.read_more") }}
              </router-link>
            </span>
          </p>
          <p class="card-text text-muted">
            {{ post.author.name }} - {{ post.created | fromNow }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapState({
      loggedUser: (state) => state.user,
      isLoggedIn: (state) => state.isLoggedIn,
    }),
    postLink() {
      return {
        name: "posts-read",
        params: {
          slug: this.post.slug,
          locale: this.$route.params.locale
        },
      };
    },
    isUserOwner(){
      if (this.isLoggedIn && this.post.author.id == this.loggedUser.id){
        return true;
      }
      return false;
    }
  },
};
</script>
<style scoped>
.post-card-img {
  height: 20rem;
  object-fit: cover;
}
@media only screen and (max-width: 768px) {
  .post-card-img {
    max-height: 15rem;
    height: auto;
  }  
}
</style>