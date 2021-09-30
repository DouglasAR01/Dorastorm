<template>
  <div
    class="carousel slide carousel-fade"
    :id="cid"
    data-ride="carousel"
    data-interval="600000"
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
    <div class="carousel-inner h-100" role="listbox">
      <carousel-item
        v-for="(item, index) in content"
        :key="'ci' + index"
        :src="item.src"
        :caption="item.caption"
        :set="item.set"
        :class="[{ active: index === 0 }]"
      />
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
/**
 * This component takes an array of objects composed by the following props:
 * src = The URL of the default image (or the single image if you are not going to use the srcset). Required.
 * caption = An object containing "title" and "content" to show over the image. This isn't mandatory.
 * set = An array of items objects (see below). This isn't mandatory.
      item = {
        src = The URL of the image.
        condition = The media condition to be met.
      }
 */
import CarouselItem from "./CarouselItem";
export default {
  components: {
    CarouselItem,
  },
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
    this.cid = "carousel" + this._uid;
  },
};
</script>