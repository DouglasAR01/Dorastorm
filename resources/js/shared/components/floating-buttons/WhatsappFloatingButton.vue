<template>
  <base-floating-button target="https://api.whatsapp.com/send?phone=573184301032" class="brand-colors">
    <i class="fab fa-whatsapp"></i>
  </base-floating-button>
</template>
<script>
import BaseFloatingButton from "./BaseFloatingButton.vue";
export default {
  components: {
    BaseFloatingButton
  }
}
</script>
<style scoped>
.brand-colors {
  background-color:#25d366;
	color:#FFF;
}
</style>