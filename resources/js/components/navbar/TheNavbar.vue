<template>
  <header>
    <base-navbar>
      <template #brand>
        <router-link
          :to="{ name: 'home', params: { locale: $route.params.locale } }"
          class="navbar-brand"
        >
          Dorastorm
        </router-link>
      </template>
    </base-navbar>
  </header>
</template>
<script>
/**
 * BaseNavbar includes #brand, #after-content and default slots.
 * #brand in intented to use it as a Brand logo or button.
 * The default slot put everything before the default features.
 * #after-content slot put everything AFTER the default features.
 */
import BaseNavbar from "./BaseNavbar";
export default {
  components: {
    BaseNavbar,
  },
};
</script>