<template>
  <nav class="navbar navbar-expand-sm navbar-light bg-white border-bottom">
    <router-link :to="{ name: 'home' }" class="navbar-brand">
      Dorastorm
    </router-link>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#responsive"
      aria-controls="responsive"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="responsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item" v-if="!isLoggedIn">
          <router-link :to="{ name: 'login' }" class="nav-link">
            Log in
          </router-link>
        </li>
        <li
          class="nav-item dropdown"
          v-if="
            isLoggedIn &&
            checkUserAnyPermission(loggedUser, [
              corePms.CREATE_USERS,
              corePms.READ_USERS,
              corePms.UPDATE_USERS,
            ])
          "
        >
          <a
            href="#"
            class="nav-link dropdown-toggle"
            role="button"
            data-toggle="dropdown"
            id="users"
            aria-haspopup="true"
            aria-expanded="false"
            >Users</a
          >
          <div class="dropdown-menu" id="users">
            <router-link
              :to="{ name: 'users-index' }"
              v-if="checkUserPermission(loggedUser, corePms.READ_USERS)"
              class="nav-link"
            >
              View all users
            </router-link>
            <router-link
              :to="{ name: 'users-create' }"
              v-if="checkUserPermission(loggedUser, corePms.CREATE_USERS)"
              class="nav-link"
            >
              Create users
            </router-link>
          </div>
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
              v-if="checkUserPermission(loggedUser, corePms.READ_USERS)"
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
          <a
            href="#"
            class="nav-link"
            @click.prevent="logout"
            :class="[{ disabled: isLoggingOut }]"
            >Log out</a
          >
        </li>
      </ul>
    </div>
  </nav>
</template>
<script>
import PermissionsHandling from "../shared/mixins/permissions-handling";
import Auth from "../services/auth";
// Log-in and Log-out buttons logic idea taken from https://www.youtube.com/watch?v=8Uwn5M6WTe0
export default {
  mixins: [PermissionsHandling],
  data() {
    return {
      isLoggingOut: false,
    };
  },
  methods: {
    async logout() {
      this.isLoggingOut = true;
      if (!(await Auth.logout())) {
        this.$toasts.error("Unable to connect with the server.");
        this.isLoggingOut = false;
        return;
      }
      this.$store.dispatch("logout");
      this.isLoggingOut = false;
      this.$router.push({ name: "login" });
    },
  },
};
</script>