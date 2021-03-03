<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div class="row mr-auto mb-4">
        <user-data-card v-bind="user" class="col-md-12" />
      </div>
    </div>
  </div>
</template>
<script>
import UserDataCard from "./UserDataCard";
import ItemArrangement from "../../shared/mixins/item-arrangement";
export default {
  mixins: [ItemArrangement],
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