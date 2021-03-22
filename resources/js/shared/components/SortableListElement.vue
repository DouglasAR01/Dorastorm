<template>
  <li class="list-item">
    <slot></slot>
  </li>
</template>
<script>
import { ElementMixin } from 'vue-slicksort';
export default {
  mixins: [
    ElementMixin
  ],
  props: ['item']
}
</script>