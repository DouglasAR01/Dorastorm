<template>
  <div v-if="isLoggedIn && userCanTouchThisPost">
    <div class="row m-auto">
      <h4>{{ $t("modules.posts.tools") }}</h4>
    </div>
    <div class="row d-flex justify-content-around">
      <div class="col">
        <router-link
          class="btn btn-primary btn-block"
          :to="{ name: 'posts-update', params: { postId: postId } }"
          v-if="userCanUpdate"
        >
          <i class="fas fa-pen"></i>
          {{ $t("modules.posts.update") }}
        </router-link>
      </div>
      <div class="col">
        <button class="btn btn-danger btn-block" v-if="userCanDelete" @click.prevent="deleteThis">
          <i class="fas fa-trash-alt"></i>
          {{ $t("modules.posts.delete") }}
        </button>
      </div>
    </div>
    <hr />
    <confirm-dialogue ref="confirmDialogue"></confirm-dialogue>
  </div>
</template>
<script>
import PermissionsHandling from "../../shared/mixins/permissions-handling";
import ConfirmDialogue from "../../shared/components/ConfirmDialogue";
export default {
  mixins: [PermissionsHandling],
  props: ["authorId", "postId"],
  components: {
    ConfirmDialogue
  },
  data () {
    return {
      deleting: false
    };
  },
  computed: {
    isUserOwner() {
      return this.authorId == this.loggedUser.id;
    },
    userCanUpdate() {
      return (
        this.isUserOwner ||
        this.checkUserPermission(
          this.loggedUser,
          this.corePms.UPDATE_ELSES_POSTS
        )
      );
    },
    userCanDelete() {
      return (
        this.isUserOwner ||
        this.checkUserPermission(
          this.loggedUser,
          this.corePms.DELETE_ELSES_POSTS
        )
      );
    },
    userCanTouchThisPost() {
      return this.userCanUpdate || this.userCanDelete;
    },
  },
  methods: {
    async deleteThis() {
      const ok = await this.$refs.confirmDialogue.show({
        title: this.$t("modules.posts.delete"),
        message:
          this.$t("modules.posts.delete_warning"),
        okButton: this.$t("message.delete"),
        okButtonColor: "btn-danger"
      });
      if (ok) {
        this.deleting = true;
        try {
          await axios.delete("/api/posts/" + this.postId);
          this.$toasts.success(this.$t("modules.posts.deleted"));
          this.$router.push({
            name: "me"
          });
        } catch (error) {
          if (is404(error)) {
            this.$toasts.error(this.$t("error.404.specific.user"));
          } else {
            this.$toasts.error(this.$t("error.fatal"));
          }
        }
        this.deleting = false;
      }
    }
  }
};
</script>