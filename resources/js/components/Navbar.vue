<template>
  <nav class="navbar navbar-expand-sm navbar-light bg-white border-bottom">
    <router-link :to="{ name: 'home' }" class="navbar-brand mr-auto">
      Dorastorm
    </router-link>
    <ul class="navbar-nav">
      <li class="nav-item" v-if="!isLoggedIn">
        <router-link :to="{ name: 'login' }" class="nav-link">
          Log-in
        </router-link>
      </li>
      <li class="nav-item" v-if="isLoggedIn">
        <a href="#" class="nav-link" @click.prevent="logout">Log-out</a>
      </li>
    </ul>
  </nav>
</template>
<script>
import { mapState } from "vuex";
// Log-in and Log-out buttons logic idea taken from https://www.youtube.com/watch?v=8Uwn5M6WTe0
export default {
  computed: {
    ...mapState({
      isLoggedIn: (state) => state.isLoggedIn,
    }),
  },
  methods: {
    async logout() {
      try {
        await axios.post("/api/logout");
      } catch (error) {
        // No internet conection
      }
      this.$store.dispatch("logout");
      this.$router.push({ name: "login" });
    },
  },
};
</script>