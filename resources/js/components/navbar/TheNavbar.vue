<template>
  <base-navbar>
    <template #brand>
      <router-link
        :to="{ name: 'home', params: { locale: $route.params.locale } }"
        class="navbar-brand"
      >
        Dorastorm
      </router-link>
    </template>
    <!-- Links inside the collapse button -->
  </base-navbar>
</template>
<script>
import BaseNavbar from "./BaseNavbar";
export default {
  components: {
    BaseNavbar,
  },
};
</script>