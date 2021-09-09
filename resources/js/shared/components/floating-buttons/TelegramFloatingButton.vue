<template>
  <base-floating-button
    target="https://telegram.me/nuwebsco"
    class="brand-colors"
    :yb="120"
  >
    <i class="fab fa-telegram-plane" style="margin-right: 4px;"></i>
  </base-floating-button>
</template>
<script>
import BaseFloatingButton from "./BaseFloatingButton.vue";
export default {
  components: {
    BaseFloatingButton,
  },
};
</script>
<style scoped>
.brand-colors {
  background-color: #0088cc;
  color: #fff;
}
</style>