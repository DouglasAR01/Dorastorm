<template>
  <div class="jumbotron jumbotron-fluid mt-auto">
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
    <div class="row justify-content-center">Lang</div>
    <div class="row justify-content-center">
      <p>©Copyright {{ year }}</p>
    </div>
  </div>
</template>
<script>
import dayjs from "dayjs";
export default {
  computed: {
    year() {
      return dayjs().year();
    },
  },
};
</script>