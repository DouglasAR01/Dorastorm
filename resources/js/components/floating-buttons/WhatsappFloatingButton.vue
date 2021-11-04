<template>
  <base-floating-button
    :target="url"
    class="brand-colors"
  >
    <i class="fab fa-whatsapp"></i>
  </base-floating-button>
</template>
<script>
/**
 * Usage:
 * You must pass a number with the country code (ex: Colombia = 57, phone number = 3184301032,
 * then number = 573184301032) to the number prop.
 * The text is optional. The component does the encoding, so you don't have to pre-encode it.
 */
import BaseFloatingButton from "./BaseFloatingButton.vue";
export default {
  components: {
    BaseFloatingButton,
  },
  props: {
    number: {
      type: String,
      required: true,
    },
    text: {
      type: String,
      required: false,
      default() {
        return null;
      },
    },
  },
  computed: {
    url() {
      let url = `https://wa.me/${this.number}`;
      if (this.text){
        let encoded = encodeURI(this.text);
        url += `?text=${encoded}`
      }
      return url;
    }
  }
};
</script>
<style scoped>
.brand-colors {
  background-color: #25d366;
  color: #fff;
}
</style>