<template>
  <div class="carousel-item">
    <div class="w-100 h-100" :class="[{ 'img-gradient': caption }]">
      <picture>
        <source
          :srcset="item.src"
          :media="item.condition"
          v-for="(item, index) in set"
          :key="index"
        />
        <img
          :srcset="src"
          alt="responsive image"
          class="d-block w-100 h-100 img-responsive"
        />
      </picture>
    </div>

    <div class="carousel-caption" v-if="caption">
      <div>
        <h2>{{ caption.title }}</h2>
        <p>{{ caption.content }}</p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    src: {
      type: String,
      required: true,
    },
    caption: {
      type: Object,
      required: false,
      validator(caption) {
        if ("title" in caption && "content" in caption) {
          return true;
        }
        return false;
      },
    },
    set: {
      type: Array,
      required: false,
      validator(set) {
        if (set.length < 0) {
          return false;
        }
        for (var item of set) {
          if (!("src" in item && "condition" in item)) {
            return false;
          }
        }
        return true;
      },
    },
  },
};
</script>
<style scoped>
.img-gradient {
  position: relative;
  height: 100%;
  width: 100%;
}

.carousel-item {
  height: 100%;
}

.img-responsive {
  object-fit: cover;
}
/* 
#002f4b,#dc4225 
Convert HEX to RGBA - http://hex2rgba.devoth.com/
*/
.img-gradient:after {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: inline-block;
  background: -moz-linear-gradient(
    top,
    rgba(255, 255, 255, 0) 0%,
    rgba(29, 29, 27, 0.5) 100%
  ); /* FF3.6+ */
  background: -webkit-gradient(
    linear,
    left top,
    left bottom,
    color-stop(0%, rgba(29, 29, 27, 0.5)),
    color-stop(100%, rgba(255, 255, 255, 0))
  ); /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(
    top,
    rgba(255, 255, 255, 0) 0%,
    rgba(29, 29, 27, 0.5) 100%
  ); /* Chrome10+,Safari5.1+ */
  background: -o-linear-gradient(
    top,
    rgba(255, 255, 255, 0) 0%,
    rgba(29, 29, 27, 0.5) 100%
  ); /* Opera 11.10+ */
  background: -ms-linear-gradient(
    top,
    rgba(255, 255, 255, 0) 0%,
    rgba(29, 29, 27, 0.5) 100%
  ); /* IE10+ */
  background: linear-gradient(
    to bottom,
    rgba(255, 255, 255, 0) 0%,
    rgba(29, 29, 27, 0.5) 100%
  ); /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#002f4b', endColorstr='#00000000',GradientType=0 ); /* IE6-9 */
}
</style>