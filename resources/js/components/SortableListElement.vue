<template>
  <li class="list-group-item">
    <slot></slot>
  </li>
</template>
<script>
import { ElementMixin } from 'vue-slicksort';
export default {
  mixins: [
    ElementMixin
  ]
}
</script>