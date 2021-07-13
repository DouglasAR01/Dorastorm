<template>
  <div class="img-container">
    <img :src="src" alt="img" :style="gen">
    <div class="opt-container">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    src: {
      type: String,
      required: true
    },
    maxHeight: {
      type: String,
      default: () => {
        return "300"
      }
    }
  },
  computed: {
    gen () {
      return `width: 100%; transition: .5s ease; object-fit: cover; max-height: ${this.maxHeight}px`
    }
  }
}
</script>
<style scoped>
.img-container {
  position: relative;
}
.opt-container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  opacity: 0;
}
.img-container:hover > img{
  opacity: 0.3;
}
.img-container:hover .opt-container{
  opacity: 1;
}
</style>