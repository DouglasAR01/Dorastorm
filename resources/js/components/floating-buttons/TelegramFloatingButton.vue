<template>
  <base-floating-button
    :target="`https://telegram.me/${tUser}`"
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
  props: {
    tUser: {
      type: String,
      required: true
    }
  }
};
</script>
<style scoped>
.brand-colors {
  background-color: #0088cc;
  color: #fff;
}
</style>