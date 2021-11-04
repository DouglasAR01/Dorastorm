<template>
  <div class="container-fluid">
    <base-carousel :content="images" class="test"/>
    <whatsapp-floating-button number="573184301032" text="ola"/>
    <telegram-button t-user="nuwebsco"></telegram-button>
  </div>  
</template>
<script>
import BaseCarousel from "../components/carousel/Carousel.vue";
import WhatsappFloatingButton from "../components/floating-buttons/WhatsappFloatingButton";
import TelegramButton from "../components/floating-buttons/TelegramFloatingButton";
export default {
  components: {
    BaseCarousel,
    WhatsappFloatingButton,
    TelegramButton
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