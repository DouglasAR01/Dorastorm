<template>
  <footer class="jumbotron jumbotron-fluid mt-auto mb-0">
    <div class="row justify-content-between">
      <div class="col-auto"></div>
      <div class="col-auto">
        <a href="https://twitter.com/nuwebsco" target="_blank"
          ><i class="fab fa-twitter fa-2x"></i
        ></a>
      </div>
      <div class="col-auto">
        <a href="https://www.facebook.com/nuwebsco" target="_blank"
          ><i class="fab fa-facebook-f fa-2x"></i
        ></a>
      </div>
      <div class="col-auto">
        <a href="https://www.instagram.com/nuwebsco/" target="_blank"
          ><i class="fab fa-instagram fa-2x"></i
        ></a>
      </div>
      <div class="col-auto"></div>
    </div>
    <hr class="w-75" />
    <div class="row">
      <div
        class="col-sm-6 d-flex justify-content-center justify-content-sm-end"
      >
        <p class="m-0 align-self-center" v-html="$t('message.powered')"></p>
      </div>

      <div
        class="col-sm-6 d-flex justify-content-center justify-content-sm-start"
      >
        <the-lang-switcher></the-lang-switcher>
      </div>
    </div>
    <div class="text-center">
      <p>© Copyright {{ year }}</p>
    </div>
  </footer>
</template>
<script>
import dayjs from "dayjs";
import TheLangSwitcher from "./TheLangSwitcher";
export default {
  components: {
    TheLangSwitcher,
  },
  computed: {
    year() {
      return dayjs().year();
    },
  },
};
</script>
<style scoped>
.row {
  margin-right: auto;
  margin-left: auto;
}
</style>