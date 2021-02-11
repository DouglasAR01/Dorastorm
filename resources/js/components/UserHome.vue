<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div class="row mr-auto mb-4">
        <user v-bind="user" class="col-md-12" />
      </div>
    </div>
  </div>
</template>
<script>
import User from "./users/User";
import item_arrangement from "../shared/mixins/item_arrangement";
export default {
  mixins: [item_arrangement],
  components: {
    User,
  },
  data() {
    return {
      loading: false,
      user: null,
    };
  },
  async created() {
    this.loading = true;
    try {
      this.user = (await axios.get("/api/user")).data.data;
    } catch (error) {
      //
    }
    this.loading = false;
  },
};
</script>