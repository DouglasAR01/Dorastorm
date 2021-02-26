<template>
  <nav class="navbar navbar-expand-sm navbar-light bg-white border-bottom">
    <router-link :to="{ name: 'home' }" class="navbar-brand mr-auto">
      Dorastorm
    </router-link>
    <ul class="navbar-nav">
      <li class="nav-item" v-if="!isLoggedIn">
        <router-link :to="{ name: 'login' }" class="nav-link">
          Log in
        </router-link>
      </li>
      <li class="nav-item dropdown" v-if="isLoggedIn">
        <a
          class="nav-link dropdown-toggle"
          href="#"
          id="permissions"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          Permissions test
        </a>
        <div class="dropdown-menu" id="permissions">
          <router-link
            :to="{ name: 'me2' }"
            class="nav-link"
            v-if="
              permissions.checkUserPermission(user, permissions.core.READ_USERS)
            "
          >
            Showed only if you have permission
          </router-link>
          <router-link :to="{ name: 'me2' }" class="nav-link">
            Showed even if you don't have permission
          </router-link>
          <router-link :to="{ name: 'me' }" class="nav-link">
            You don't need permission here
          </router-link>
        </div>
      </li>
      <li class="nav-item" v-if="isLoggedIn">
        <a href="#" class="nav-link" @click.prevent="logout" :class="[{'disabled':isLoggingOut}]">Log out</a>
      </li>
    </ul>
  </nav>
</template>
<script>
import { mapState } from "vuex";
import Permissions from "../services/role-permissions";
// Log-in and Log-out buttons logic idea taken from https://www.youtube.com/watch?v=8Uwn5M6WTe0
export default {
  data () {
    return {
      isLoggingOut: false
    };
  },
  computed: {
    ...mapState({
      isLoggedIn: (state) => state.isLoggedIn,
      user: (state) => state.user,
    }),
    permissions() {
      return Permissions;
    },
  },
  methods: {
    async logout() {
      this.isLoggingOut = true;
      try {
        await axios.post("/api/logout");
      } catch (error) {
        // No internet conection
      }
      this.$store.dispatch("logout");
      this.isLoggingOut = false;
      this.$router.push({ name: "login" });
    },
  },
};
</script>