<template>
  <nav
    class="navbar navbar-expand-sm navbar-light bg-light border-bottom">
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
          <router-link class="nav-link" :to="{ name: 'posts-index' }">
            {{ $t("modules.posts.index") }}
          </router-link>
        </li>
        <li class="nav-item" v-if="!isLoggedIn">
          <router-link :to="{ name: 'login' }" class="nav-link">
            {{ $t("navbar.login") }}
          </router-link>
        </li>

        <li class="nav-item dropdown" v-if="isLoggedIn">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="posts"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            {{ $t("modules.posts.posts") }}
          </a>
          <div class="dropdown-menu" id="posts">
            <router-link
              :to="{ name: 'posts-create' }"
              class="nav-link"
              v-if="checkUserPermission(loggedUser, corePms.CREATE_POSTS)"
            >
              {{ $t("modules.posts.create") }}
            </router-link>
            <router-link
              :to="{ name: 'user-posts' }"
              class="nav-link"
              v-if="checkUserPermission(loggedUser, corePms.CREATE_POSTS)"
            >
              {{ $t("modules.posts.my_posts") }}
            </router-link>
            <hr class="w-75 my-1">
            <router-link class="nav-link" :to="{ name: 'posts-index' }">
              {{ $t("modules.posts.index") }}
            </router-link>
            <router-link class="nav-link" :to="{ name: 'private-posts-index' }">
              {{ $t("modules.posts.private_index") }}
            </router-link>
          </div>
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
            >{{ $t("navbar.users.users") }}</a
          >
          <div class="dropdown-menu" id="users">
            <router-link
              :to="{ name: 'users-index' }"
              v-if="checkUserPermission(loggedUser, corePms.READ_USERS)"
              class="nav-link"
            >
              {{ $t("navbar.users.read") }}
            </router-link>
            <router-link
              :to="{ name: 'users-create' }"
              v-if="checkUserPermission(loggedUser, corePms.CREATE_USERS)"
              class="nav-link"
            >
              {{ $t("navbar.users.create") }}
            </router-link>
          </div>
        </li>
        <li
          class="nav-item dropdown"
          v-if="
            isLoggedIn &&
            checkUserAnyPermission(loggedUser, [
              corePms.CREATE_ROLES,
              corePms.READ_ROLES,
              corePms.UPDATE_ROLES,
            ])
          "
        >
          <a
            href="#"
            id="roles"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            class="nav-link dropdown-toggle"
          >
            {{ $t("navbar.roles.title") }}
          </a>
          <div
            class="dropdown-menu"
            id="roles"
            v-if="checkUserPermission(loggedUser, corePms.CREATE_ROLES)"
          >
            <router-link
              :to="{ name: 'roles-index' }"
              v-if="checkUserPermission(loggedUser, corePms.READ_ROLES)"
              class="nav-link"
            >
              {{ $t("navbar.roles.read") }}
            </router-link>
            <router-link class="nav-link" :to="{ name: 'roles-create' }">{{
              $t("navbar.roles.create")
            }}</router-link>
          </div>
        </li>

        <li class="ml-sm-3 nav-item" v-if="isLoggedIn">
          <router-link :to="{ name: 'me' }" class="nav-link">
            <span>
              <i class="fas fa-user"></i> {{userName}}
            </span>
          </router-link>
        </li>
        
        <li class="nav-item" v-if="isLoggedIn">
          <a
            href="#"
            class="nav-link"
            @click.prevent="logout"
            :class="[{ disabled: isLoggingOut }]"
            >{{ $t("navbar.logout") }}</a
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
  computed: {
    userName() {
      return this.loggedUser.name.split(" ")[0];
    }
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