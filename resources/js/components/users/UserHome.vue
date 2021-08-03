<template>
  <div class="container wp">
    <div v-if="loading">{{ $t("message.loading") }}</div>
    <div v-else>
      <div class="row mb-2">
        <user-data-card :user="user"/>
      </div>
    </div>
  </div>
</template>
<script>
import UserDataCard from "./UserDataCard";
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