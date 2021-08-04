<template>
  <nav class="navbar navbar-expand-sm navbar-light bg-light border-bottom">
    <router-link :to="{ name: 'home' }" class="navbar-brand">
      Dorastorm
    </router-link>
    <navbar-collapse-button />
    <base-navbar-collapse-list>
      <!-- Post index -->
      <single-navbar-list-element v-if="!isLoggedIn">
        <router-link :to="{ name: 'posts-index' }" class="nav-link">
          {{ $t("modules.posts.index") }}
        </router-link>
      </single-navbar-list-element>
      <!-- Login -->
      <single-navbar-list-element v-if="!isLoggedIn">
        <router-link :to="{ name: 'login' }" class="nav-link">
          {{ $t("navbar.login") }}
        </router-link>
      </single-navbar-list-element>
      <!-- Quotes -->
      <single-navbar-list-element v-if="isLoggedIn && checkUserPermission(loggedUser, corePms.READ_QUOTES)">
        <router-link :to="{ name: 'quotes-index' }" class="nav-link">
          {{ $t("modules.quotes.index") }}
        </router-link>
      </single-navbar-list-element>
      <!-- Post dropdown -->
      <navbar-list-dropdown-element cid="posts" v-if="isLoggedIn">
        <template #header>
          {{ $t("modules.posts.posts") }}
        </template>
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
        <hr class="w-75 my-1" />
        <router-link class="nav-link" :to="{ name: 'posts-index' }">
          {{ $t("modules.posts.index") }}
        </router-link>
        <router-link class="nav-link" :to="{ name: 'private-posts-index' }">
          {{ $t("modules.posts.private_index") }}
        </router-link>
      </navbar-list-dropdown-element>
      <!-- Users dropdown -->
      <navbar-list-dropdown-element
        cid="users"
        v-if="
          isLoggedIn &&
          checkUserAnyPermission(loggedUser, [
            corePms.CREATE_USERS,
            corePms.READ_USERS,
            corePms.UPDATE_USERS,
          ])
        "
      >
        <template #header>
          {{ $t("navbar.users.users") }}
        </template>
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
      </navbar-list-dropdown-element>
      <!-- Roles dropdown -->
      <navbar-list-dropdown-element
        cid="roles"
        v-if="
          isLoggedIn &&
          checkUserAnyPermission(loggedUser, [
            corePms.CREATE_ROLES,
            corePms.READ_ROLES,
            corePms.UPDATE_ROLES,
          ])
        "
      >
        <template #header>
          {{ $t("navbar.roles.title") }}
        </template>
        <router-link
          :to="{ name: 'roles-index' }"
          v-if="checkUserPermission(loggedUser, corePms.READ_ROLES)"
          class="nav-link"
        >
          {{ $t("navbar.roles.read") }}
        </router-link>
        <router-link
          class="nav-link"
          :to="{ name: 'roles-create' }"
          v-if="checkUserPermission(loggedUser, corePms.CREATE_ROLES)"
          >{{ $t("navbar.roles.create") }}</router-link
        >
      </navbar-list-dropdown-element>
      <single-navbar-list-element classes="ml-sm-3" v-if="isLoggedIn">
        <router-link :to="{ name: 'me' }" class="nav-link">
          <span> <i class="fas fa-user"></i> {{ userName }} </span>
        </router-link>
      </single-navbar-list-element>
      <single-navbar-list-element v-if="isLoggedIn">
        <a
          href="#"
          class="nav-link"
          @click.prevent="logout"
          :class="[{ disabled: isLoggingOut }]"
          >{{ $t("navbar.logout") }}</a
        >
      </single-navbar-list-element>
    </base-navbar-collapse-list>
  </nav>
</template>
<script>
import NavbarCollapseButton from "./navbar/NavbarCollapseButton.vue";
import BaseNavbarCollapseList from "./navbar/BaseNavbarCollapseList.vue";
import SingleNavbarListElement from "./navbar/SingleNavbarListElement.vue";
import NavbarListDropdownElement from "./navbar/NavbarListDropdownElement.vue";
import PermissionsHandling from "../shared/mixins/permissions-handling";
import Auth from "../services/auth";
export default {
  components: {
    NavbarCollapseButton,
    BaseNavbarCollapseList,
    SingleNavbarListElement,
    NavbarListDropdownElement,
  },
  mixins: [PermissionsHandling],
  data() {
    return {
      isLoggingOut: false,
    };
  },
  computed: {
    userName() {
      return this.loggedUser.name.split(" ")[0];
    },
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