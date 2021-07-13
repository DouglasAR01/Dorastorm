<template>
  <div>
    <slot :e="hasErrors" />
    <div v-if="hasErrors">
      <div
        class="invalid-feedback"
        v-for="error in errorFor"
        :key="'err' + error"
      >
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    errors: {
      type: Object,
      required: false,
    },
    name: {
      type: String,
      required: true,
    },
  },
  computed: {
    hasErrors() {
      return !!(
        this.errors &&
        this.errors[this.name] &&
        this.errors[this.name].length > 0
      );
    },
    errorFor() {
      if (this.hasErrors) {
        return this.errors[this.name];
      }
      return [];
    },
  },
};
</script>

<style scoped>
.is-invalid ~ div > .invalid-feedback {
  display: block;
}
</style>