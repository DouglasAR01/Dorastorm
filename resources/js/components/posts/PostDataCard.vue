<template>
  <div class="card border-bottom">
    <div class="row no-gutters">
      <div class="col-md-4 p-2">
        <router-link :to="postLink">
          <img src="https://socialsciences.uottawa.ca/human-motivation/sites/socialsciences.uottawa.ca.human-motivation/files/generic_female_0.jpg" alt="" class="card-img">
        </router-link>
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <router-link class="h5 card-title" :to="postLink">
            {{post.title}}
          </router-link>
          <p class="card-text">
            {{post.description}}
            <span>
              <router-link class="text-muted" :to="postLink">
                {{$t("modules.posts.read_more")}}
              </router-link>
            </span>
          </p>
          <p class="card-text text-muted">
            {{post.author.name}} - {{post.created | fromNow}}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["post"],
  computed: {
    postLink() {
      return {
        name: 'posts-read',
        params: {
          slug: this.post.slug
        }
      }
    }
  }
}
</script>