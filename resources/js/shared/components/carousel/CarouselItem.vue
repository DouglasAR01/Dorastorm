<template>
  <div class="carousel-item">
    <picture>
      <source :srcset="item.sources[0]" media="(min-width: 1400px)" />
      <source :srcset="item.sources[1]" media="(min-width: 769px)" />
      <source :srcset="item.sources[2]" media="(min-width: 577px)" />
      <img
        :srcset="item.sources[3]"
        alt="responsive image"
        class="d-block img-fluid"
      />
    </picture>
    <div class="carousel-caption" v-if="item.caption">
      <div>
        <h2>{{item.caption.title}}</h2>
        <p>{{item.caption.content}}</p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
      validator(item) {
        if (item.sources.length === 4) {
          return true;
        }
        return false;
      },
    },
  },
};
</script>