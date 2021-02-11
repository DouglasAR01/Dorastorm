<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div class="row mr-auto mb-4">
        <user v-bind="user" class="col-md-12" />
      </div>
      <div class="row mb-4" v-for="row in rows" :key="'r' + row">
        <div
          class="col d-flex align-items-stretch"
          v-for="(secret, index) in elementsInRow(row)"
          :key="index"
        >
          <secret v-bind="secret" />
        </div>
        <div
          class="col d-flex align-items-stretch"
          v-for="(filler, index) in fillerElements(elementsInRow(row))"
          :key="'f' + index"
        ></div>
      </div>
    </div>
  </div>
</template>
<script>
import User from "./users/User";
import Secret from "./secrets/Secret";
import item_arrangement from "../shared/mixins/item_arrangement";
export default {
  mixins: [item_arrangement],
  components: {
    User,
    Secret,
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
      this.items = this.user.secrets;
    } catch (error) {
      //
    }
    this.loading = false;
  },
};
</script>