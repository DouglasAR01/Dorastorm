<template>
  <div class="container-fluid">
    <base-carousel :content="images" class="test"/>
    
  </div>
</template>
<script>
import BaseCarousel from "../shared/components/carousel/Carousel.vue";
export default {
  components: {
    BaseCarousel,
  },
  data() {
    return {
      images: [
        {
          src: require("../../assets/test.jpg"),
          caption: {
            title: "hola",
            content: "¿cómo estás?",
          },
        },
        {
          src: require("../../assets/default-image.svg")
        },
      ],
    };
  },
};
</script>
<style scoped>
.test{
  height: 500px;
}
</style>