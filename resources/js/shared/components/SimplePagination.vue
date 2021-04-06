<template>
  <div class="row d-flex justify-content-center">
    <button
      class="btn btn-primary mr-1"
      v-if="hasPrev"
      @click.prevent="$emit('navigating', meta.current_page - 1)"
    >
      <i class="fas fa-backward"></i>
      {{ messages.prev }}
    </button>
    <button
      class="btn btn-primary mr-1"
      v-if="hasPrev"
      @click.prevent="$emit('navigating', 1)"
    >
      <i class="fas fa-home"></i>
      {{ messages.first }}
    </button>
    <button
      class="btn btn-primary"
      v-if="hasNext"
      @click.prevent="$emit('navigating', meta.current_page + 1)"
    >
      {{ messages.next }}
      <i class="fas fa-forward"></i>
    </button>
  </div>
</template>
<script>
export default {
  props: {
    meta: {
      type: Object,
      required: true
    },
    links: {
      type: Object,
      required: true
    },
    messages: {
      type: Object,
      default: function () {
        return {
          first: this.$t("message.paginate_first"),
          next: this.$t("message.paginate_next"),
          prev: this.$t("message.paginate_prev")
        }
      }
    }
  },
  computed: {
    hasNext() {
      return this.links.next != null;
    },
    hasPrev() {
      return this.links.prev != null;
    },
  }
}
</script>