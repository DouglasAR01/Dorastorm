<template>
  <div v-if="errors != null">
    <div class="invalid-feedback" v-for="error in errors" :key="'err'+error">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: ["errors"],
};
</script>

<style scoped>
.form-control.is-invalid ~ div > .invalid-feedback {
  display: block;
}
</style>