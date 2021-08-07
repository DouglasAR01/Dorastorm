<template>
  <div
    class="carousel slide carousel-fade"
    :id="cid"
    data-ride="carousel"
    data-interval="6000"
  >
    <ol class="carousel-indicators">
      <li
        v-for="(item, index) in content"
        :key="index"
        :data-target="`#${cid}`"
        :data-slide-to="index"
        :class="[{ active: index === 0 }]"
      ></li>
    </ol>
    <div class="carousel-inner" role="listbox">
      <slot></slot>
    </div>
    <a
      class="carousel-control-prev"
      :href="`#${cid}`"
      role="button"
      data-slide="prev"
    >
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a
      class="carousel-control-next"
      :href="`#${cid}`"
      role="button"
      data-slide="next"
    >
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</template>
<script>
export default {
  props: {
    content: {
      type: Array,
      required: true,
      validator(payload) {
        // There must be at least 2 elements for the carousel
        if (payload.length < 2) {
          return false;
        }
        return true;
      },
    },
  },
  data() {
    return {
      cid: null,
    };
  },
  mounted() {
    this.cid = "carousel"+ this._uid;
  },
};
</script>