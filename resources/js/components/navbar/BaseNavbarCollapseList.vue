<template>
  <div class="collapse navbar-collapse" :id="cid">
    <ul class="navbar-nav ml-auto">
      <slot></slot>
    </ul>
  </div>
</template>
<script>
export default {
  props: {
    cid: {
      type: String,
      required: false,
      default: () => "base-navbar-collapse",
    },
  },
};
</script>