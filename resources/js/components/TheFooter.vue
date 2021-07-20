<template>
  <div class="jumbotron jumbotron-fluid mt-auto mb-0">
    <div class="row justify-content-between">
      <div class="col-auto"></div>
      <div class="col-auto">
        <a href="https://twitter.com/nuwebsco"
          ><i class="fab fa-twitter"></i
        ></a>
      </div>
      <div class="col-auto">
        <a href="https://www.facebook.com/nuwebsco"
          ><i class="fab fa-facebook-f"></i
        ></a>
      </div>
      <div class="col-auto">
        <a href="https://www.instagram.com/nuwebsco/"
          ><i class="fab fa-instagram"></i
        ></a>
      </div>
      <div class="col-auto"></div>
    </div>
    <div class="row justify-content-center">
      <the-lang-switcher></the-lang-switcher>
    </div>
    <hr class="w-75">
    <div class="row justify-content-center">{{$t("message.powered")}}</div>
    <div class="row justify-content-center">
      <p>©Copyright {{ year }}</p>
    </div>
  </div>
</template>
<script>
import dayjs from "dayjs";
import TheLangSwitcher from "./TheLangSwitcher";
export default {
  components: {
    TheLangSwitcher
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