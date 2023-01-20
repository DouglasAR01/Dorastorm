<template>
  <article class="container wp">
    <section v-if="loading">{{ $t("message.loading") }}</section>
    <section v-else>
      <user-data-card :user="user" class="mb-2"/>
    </section>
  </article>
</template>
<script>
import UserDataCard from "../../components/users/UserDataCard";
export default {
  components: {
    UserDataCard
  },
  data() {
    return {
      loading: false,
      user: null,
    };
  },
  created() {
    this.loading = true;
    this.user = this.$store.state.user;
    this.loading = false;
  },
};
</script>