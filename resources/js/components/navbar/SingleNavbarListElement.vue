<template>
  <li :class="cl">
    <slot></slot>
  </li>
</template>
<script>
export default {
  props: {
    classes: {
      type: String,
      required: false,
      default: () => null,
    }
  },
  computed: {
    cl() {
      var cl = "nav-item";
      if (this.classes) {
        cl += " " + this.classes;
      }
      return cl;
    },
  },
};
</script>