<template>
  <nav class="navbar navbar-expand-sm navbar-light bg-light border-bottom">
    <slot name="brand"></slot>
    <navbar-collapse-button />
    <base-navbar-collapse-list>
      <slot></slot>
      <!-- Post index -->
      <single-navbar-list-element v-if="!isLoggedIn && features.AUTH && features.POSTS">
        <router-link
          :to="{
            name: 'posts-index',
            params: { locale: $route.params.locale },
          }"
          class="nav-link"
        >
          {{ $t("modules.posts.index") }}
        </router-link>
      </single-navbar-list-element>
      <div class="navbar-nav" v-if="features.AUTH && features.LOGIN_BUTTON" key="f-auth">
        <!-- Login -->
        <single-navbar-list-element v-if="!isLoggedIn">
          <router-link
            :to="{ name: 'login', params: { locale: $route.params.locale } }"
            class="nav-link"
          >
            {{ $t("navbar.login") }}
          </router-link>
        </single-navbar-list-element>
        <!-- Quotes -->
        <single-navbar-list-element
          v-if="
            features.QUOTES &&
            isLoggedIn && 
            checkUserPermission(loggedUser, corePms.READ_QUOTES)
          "
        >
          <router-link
            :to="{
              name: 'quotes-index',
              params: { locale: $route.params.locale },
            }"
            class="nav-link"
          >
            {{ $t("modules.quotes.index") }}
          </router-link>
        </single-navbar-list-element>
        <!-- Post dropdown -->
        <navbar-list-dropdown-element cid="posts" v-if="isLoggedIn && features.POSTS">
          <template #header>
            {{ $t("modules.posts.posts") }}
          </template>
          <router-link
            :to="{
              name: 'posts-create',
              params: { locale: $route.params.locale },
            }"
            class="nav-link"
            v-if="checkUserPermission(loggedUser, corePms.CREATE_POSTS)"
          >
            {{ $t("modules.posts.create") }}
          </router-link>
          <router-link
            :to="{
              name: 'user-posts',
              params: { locale: $route.params.locale },
            }"
            class="nav-link"
            v-if="checkUserPermission(loggedUser, corePms.CREATE_POSTS)"
          >
            {{ $t("modules.posts.my_posts") }}
          </router-link>
          <hr class="w-75 my-1" />
          <router-link
            class="nav-link"
            :to="{
              name: 'posts-index',
              params: { locale: $route.params.locale },
            }"
          >
            {{ $t("modules.posts.index") }}
          </router-link>
          <router-link
            class="nav-link"
            :to="{
              name: 'private-posts-index',
              params: { locale: $route.params.locale },
            }"
          >
            {{ $t("modules.posts.private_index") }}
          </router-link>
        </navbar-list-dropdown-element>
        <!-- Users dropdown -->
        <navbar-list-dropdown-element
          cid="users"
          v-if="
            features.USERS_MANAGEMENT &&
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
            :to="{
              name: 'users-index',
              params: { locale: $route.params.locale },
            }"
            v-if="checkUserPermission(loggedUser, corePms.READ_USERS)"
            class="nav-link"
          >
            {{ $t("navbar.users.read") }}
          </router-link>
          <router-link
            :to="{
              name: 'users-create',
              params: { locale: $route.params.locale },
            }"
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
            features.USERS_MANAGEMENT &&
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
            :to="{
              name: 'roles-index',
              params: { locale: $route.params.locale },
            }"
            v-if="checkUserPermission(loggedUser, corePms.READ_ROLES)"
            class="nav-link"
          >
            {{ $t("navbar.roles.read") }}
          </router-link>
          <router-link
            class="nav-link"
            :to="{
              name: 'roles-create',
              params: { locale: $route.params.locale },
            }"
            v-if="checkUserPermission(loggedUser, corePms.CREATE_ROLES)"
            >{{ $t("navbar.roles.create") }}</router-link
          >
        </navbar-list-dropdown-element>
        <single-navbar-list-element classes="ml-sm-3" v-if="isLoggedIn">
          <router-link
            :to="{ name: 'me', params: { locale: $route.params.locale } }"
            class="nav-link"
          >
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
      </div>
      <slot name="after-content"></slot>
    </base-navbar-collapse-list>
  </nav>
</template>
<script>
import NavbarCollapseButton from "./NavbarCollapseButton.vue";
import BaseNavbarCollapseList from "./BaseNavbarCollapseList.vue";
import SingleNavbarListElement from "./SingleNavbarListElement.vue";
import NavbarListDropdownElement from "./NavbarListDropdownElement.vue";
import PermissionsHandling from "../../shared/mixins/permissions-handling";
import Auth from "../../services/auth";
import CONFIG from "../../app.config";
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
      if (this.isLoggedIn) {
        return this.loggedUser.name.split(" ")[0];
      }
      return null;
    },
    features() {
      return CONFIG.features;
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
      this.$router.push({
        name: "login",
        params: { locale: this.$route.params.locale },
      });
    },
  },
};
</script>