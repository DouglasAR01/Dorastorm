<template>
  <li class="nav-item dropdown">
    <a
      class="nav-link dropdown-toggle"
      href="#"
      :id="cid"
      role="button"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <slot name="header"></slot>
    </a>
    <div class="dropdown-menu" :id="cid">
      <slot></slot>
    </div>
  </li>
</template>
<script>
export default {
  props: {
    cid: {
      type: String,
      required: true,
    },
  },
};
</script>