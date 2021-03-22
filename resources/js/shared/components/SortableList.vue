<template>
  <ul class="list-group">
    <slot></slot>
  </ul>
</template>
<script>
import { ContainerMixin } from 'vue-slicksort';
export default {
  mixins: [
    ContainerMixin
  ]
}
</script>