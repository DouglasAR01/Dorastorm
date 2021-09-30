<template>
  <a
    :href="target"
    target="_blank"
    class="floating"
    v-if="externalLink"
    key="thelink"
    :style="`bottom:${yb}px; right: ${xr}px;`"
  >
    <slot></slot>
  </a>
  <router-link
    v-else
    key="thelink"
    class="floating"
    :to="target"
    :style="`bottom:${yb}px; right: ${xr}px;`"
  >
    <slot></slot>
  </router-link>
</template>
<script>
export default {
  props: {
    externalLink: {
      type: Boolean,
      required: false,
      default() {
        return true;
      }
    },
    target: {
      type: [Object, String],
      required: true,
    },
    yb: {
      type: Number,
      required: false,
      default() {
        return 40;
      },
    },
    xr: {
      type: Number,
      required: false,
      default() {
        return 40;
      },
    }
  },
};
</script>
<style scoped>
.floating {
  position: fixed;
  width: 60px;
  height: 60px;
  right: 40px;
  border-radius: 50px;
  text-align: center;
  font-size: 30px;
  z-index: 100;
}
.floating > * {
  margin-top:16px;
}
</style>