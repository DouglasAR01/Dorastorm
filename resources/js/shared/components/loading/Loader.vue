<template>
  <content-loader :width="width" :height="height" :speed="speed" :primary-color="primaryColor" :secondary-color="secondaryColor">
      <slot></slot>
  </content-loader>
</template>
<script>
import { ContentLoader } from "vue-content-loader";
export default {
  components: {
    ContentLoader,
  },
  props: ["width", "height", "speed"],
  props: {
    width: {
      type: Number,
      required: true
    },
    height: {
      type: Number,
      required: true
    },
    speed: {
      type: Number,
      default: function() {
        return 2;
      }
    },
    primaryColor: {
      type: String,
      default: function() {
        return "#fff";
      }
    },
    secondaryColor: {
      type: String,
      default: function() {
        return "#ecebeb";
      }
    }
  }
};
</script>