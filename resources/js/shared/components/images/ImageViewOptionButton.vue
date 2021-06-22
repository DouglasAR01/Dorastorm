<template>
  <button
    class="btn btn-info btn-sm"
    @click.prevent="$modal.show(modalId)"
  >
    <slot>View</slot>
  </button>
</template>
<script>
export default {
  props: {
    imageModalId: {
      type: Number,
      default: 1
    }
  },
  computed: {
    modalId() {
      return "image-modal" + this.imageModalId;
    }
  }
}
</script>