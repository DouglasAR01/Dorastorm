<template>
  <button
    class="navbar-toggler"
    type="button"
    data-toggle="collapse"
    :data-target="`#${cid}`"
    :aria-controls="cid"
    aria-expanded="false"
    aria-label="Toggle navigation"
  >
    <span class="navbar-toggler-icon"></span>
  </button>
</template>
<script>
export default {
  props: {
    cid: {
      type: String,
      required: false,
      default: () => ("base-navbar-collapse")
    }
  }
}
</script>